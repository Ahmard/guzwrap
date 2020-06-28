<?php
namespace Guzwrap;

use Guzwrap\Classes\TheWrapper;

class Request
{
    /**
     * handle first static call
     * @return Queliwrap\Client
     */
    public static function __callStatic($method, $args)
    {
        return (new TheWrapper())->$method(...$args);
    }
    
}