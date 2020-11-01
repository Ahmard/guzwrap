<?php

namespace Guzwrap\Core;

trait RequestMethods
{
    /**
     * Send GET request
     * @param mixed ...$args
     * @return $this
     */
    public function get(...$args)
    {
        return $this->request('GET', ...$args);
    }

    /**
     * Send HEAD request
     * @param mixed ...$args
     * @return $this
     */
    public function head(...$args)
    {
        return $this->request('HEAD', ...$args);
    }

    /**
     * Send POST request
     * @param mixed ...$args
     * @return $this
     */
    public function post(...$args)
    {
        return $this->request('POST', ...$args);
    }

    /**
     * Send http put request
     * @param mixed ...$args
     * @return $this
     */
    public function put(...$args)
    {
        return $this->request('PUT', ...$args);
    }

    /**
     * Send http delete request
     * @param mixed ...$args
     * @return $this
     */
    public function delete(...$args)
    {
        return $this->request('DELETE', ...$args);
    }

    /**
     * Send http connect request
     * @param mixed ...$args
     * @return $this
     */
    public function connect(...$args)
    {
        return $this->request('CONNECT', ...$args);
    }

    /**
     * Send http options request
     * @param mixed ...$args
     * @return $this
     */
    public function options(...$args)
    {
        return $this->request('OPTIONS', ...$args);
    }

    /**
     * Send http trace request
     * @param mixed ...$args
     * @return $this
     */
    public function trace(...$args)
    {
        return $this->request('TRACE', ...$args);
    }

    /**
     * Send http patch request
     * @param mixed ...$args
     * @return $this
     */
    public function patch(...$args)
    {
        return $this->request('PATCH', ...$args);
    }

}