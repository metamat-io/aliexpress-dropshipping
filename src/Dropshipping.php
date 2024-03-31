<?php

namespace MetamatIo\AliexpressDropshipping;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MetamatIo\AliexpressDropshipping\Exceptions\DropshippingException;
use MetamatIo\AliexpressDropshipping\Requests\DropshippingRequest;

/**
 *
 * It is the main class for sending requests
 *
 */
final class Dropshipping
{

    public string $app_key;
    public string $secret_key;
    public string $gateway_url;
    public int $connect_timeout;
    public int $read_timeout;

    protected string $sign_method = "sha256";
    protected string $sdk_version = "iop-sdk-php-20220608";

    public function __construct(
        string $url,
        int $app_key,
        string $secret_key,
        float $connect_timeout = 0,
        float $read_timeout = 0
    )
    {
        $this->gateway_url = $url;
        $this->app_key = $app_key;
        $this->secret_key = $secret_key;
        $this->connect_timeout = $connect_timeout;
        $this->read_timeout = $read_timeout;
    }

    /**
     *
     * Responsible for generating singleton for connection
     * security, which requires ali
     *
     * @param string $api_name
     * @param array $params
     *
     * @return string
     */
    protected function generate_sign(
        string $api_name,
        array $params
    ): string
    {
        ksort($params);

        $signed = '';

        if(str_contains($api_name, '/')) {
            $signed .= $api_name;
        }

        foreach ($params as $k => $v) {
            $signed .= "$k$v";
        }

        return strtoupper(hash_hmac('sha256', $signed, $this->secret_key));
    }

    /**
     *
     * Responsible for generating singleton for connection
     * security, which requires ali
     *
     * @param DropshippingRequest $request
     * @param string|null $access_token
     *
     * @return string
     * @throws DropshippingException
     */
    public function execute(
        DropshippingRequest $request,
        string|null $access_token = null
    ): string
    {
        $client = new Client([
            'connect_timeout'   => $this->connect_timeout,
            'read_timeout'      => $this->read_timeout,
        ]);

        $params = [
            "app_key"       => $this->app_key,
            "sign_method"   => $this->sign_method,
            "timestamp"     => round(microtime(true) * 1000),
            "method"        => $request->api_name,
            "partner_id"    => $this->sdk_version,
            "simplify"      => $request->simplify,
            "format"        => $request->format
        ];

        if (is_string($access_token)) {
            $params["session"] = $access_token;
        }

        $params["sign"] = $this->generate_sign($request->api_name, array_merge($request->udf_params, $params));

        $request_url = rtrim($this->gateway_url, '/') . '?' . http_build_query($params);

        try {
            $response = $client->request($request->http_method, $request_url, [
                'form_params' => $request->udf_params,
            ]);

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new DropshippingException($e->getMessage(), $e->getCode());
        }
    }

}
