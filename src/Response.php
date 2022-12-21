<?php

namespace App\Src;

class Response
{
    public static function setStatusCode(int $code)
    {
        http_response_code($code);
    }
}