<?php

namespace MetamatIo\AliexpressDropshipping\Requests;

/**
 *
 * Responsible for assembling the request
 * to send the request
 *
 */
final class DropshippingRequest
{

    public string $api_name;
    public array $header_params = [];
    public array $udf_params = [];
    public array $file_params = [];
    public string $http_method = 'POST';
    public string $simplify = 'false';
    public string $format = 'json';

    public function __construct(
        string $api_name,
        string $http_method = 'POST'
    )
    {
        $this->api_name = $api_name;
        $this->http_method = $http_method;
    }

    /**
     *
     * The main function that adds parameters to
     * an array
     *
     * @param string $key
     * @param string|int $value
     *
     * @return void
     */
    public function add_param(
        string $key,
        string|int $value
    ): void
    {
        $this->udf_params[$key] = $value;
    }

    /**
     *
     * Auxiliary function for adding files to a request
     *
     * @param string $key
     * @param mixed $content
     * @param string $mime_type
     *
     * @return void
     */
    public function add_file_param(
        string $key,
        mixed $content,
        string $mime_type = 'application/octet-stream'
    ): void
    {
        $this->file_params[$key] = [
            'type'      => $mime_type,
            'content'   => $content,
            'name'      => $key
        ];
    }

    /**
     *
     * Auxiliary function for adding parameters to
     * the request header
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function add_http_header_param(
        string $key,
        string $value
    ): void
    {
        $this->header_params[$key] = $value;
    }

}
