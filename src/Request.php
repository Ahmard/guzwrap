<?php
namespace Guzwrap;

class Request
{
    public function __construct()
    {
        
    }
    
    /**
     * handle first static call
     * @return Queliwrap\Client
     */
    public static function __callStatic($method, $args)
    {
        return (new TheWrapper())->$method(...$args);
    }
    
}