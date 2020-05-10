<?php
namespace Guzwrap\SubClasses;

trait RequestMethods
{
    
    public function get(...$args)
    {
        return $this->request('GET', $args);
    }
    
    
    public function head(...$args)
    {
        return $this->request(...$args);
    }
    
    public function post(...$args)
    {
        return $this->request('POST', ...$args);
    }
    
    public function put(...$args)
    {
        return $this->request('PUT', ...$args);
    }
    
    public function delete(...$args)
    {
        return $this->request('delete', ...$args);
    }
    
    
    public function connect(...$args)
    {
        return $this->request('CONNECT', ...$args);
    }
    
    
    public function options(...$args)
    {
        return $this->request('OPTIONS', ...$args);
    }
    
    
    public function trace(...$args)
    {
        return $this->request('TRACE', ...$args);
    }
    
    
    public function patch(...$args)
    {
        return $this->request('PATCH', ...$args);
    }

}