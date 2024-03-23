<?php

namespace MetamatIo\AliexpressDropshipping\Exceptions;

use GuzzleHttp\Exception\GuzzleException;

/**
 *
 *
 *
 */
final class DropshippingException extends \Exception {

    public function __construct(
        string $message = "",
        int $code = 0,
        ?GuzzleException $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

}
