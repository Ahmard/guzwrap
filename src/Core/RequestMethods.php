<?php

namespace Guzwrap\Core;

trait RequestMethods
{
    /**
     * Send GET request
     * @param string $url
     * @return $this
     */
    public function get(string $url)
    {
        return $this->request('GET', $url);
    }

    /**
     * Send HEAD request
     * @param string $url
     * @return $this
     */
    public function head(string $url)
    {
        return $this->request('HEAD', $url);
    }

    /**
     * Send POST request
     * @param string|callable $urlOrClosure
     * @return $this
     */
    public function post($urlOrClosure)
    {
        return $this->request('POST', $urlOrClosure);
    }

    /**
     * Send http put request
     * @param string $url
     * @return $this
     */
    public function put(string $url)
    {
        return $this->request('PUT', $url);
    }

    /**
     * Send http delete request
     * @param string $url
     * @return $this
     */
    public function delete(string $url)
    {
        return $this->request('DELETE', $url);
    }

    /**
     * Send http connect request
     * @param string $url
     * @return $this
     */
    public function connect(string $url)
    {
        return $this->request('CONNECT', $url);
    }

    /**
     * Send http options request
     * @param string $url
     * @return $this
     */
    public function options(string $url)
    {
        return $this->request('OPTIONS', $url);
    }

    /**
     * Send http trace request
     * @param string $url
     * @return $this
     */
    public function trace(string $url)
    {
        return $this->request('TRACE', $url);
    }

    /**
     * Send http patch request
     * @param string $url
     * @return $this
     */
    public function patch(string $url)
    {
        return $this->request('PATCH', $url);
    }

}