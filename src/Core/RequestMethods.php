<?php

namespace Guzwrap\Core;

trait RequestMethods
{
    /**
     * Send GET request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function get(string $url): GuzzleWrapper
    {
        return $this->request('GET', $url);
    }

    /**
     * Send HEAD request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function head(string $url): GuzzleWrapper
    {
        return $this->request('HEAD', $url);
    }

    /**
     * Send POST request
     * @param string|callable $urlOrClosure
     * @return GuzzleWrapper
     */
    public function post($urlOrClosure): GuzzleWrapper
    {
        return $this->request('POST', $urlOrClosure);
    }

    /**
     * Send http put request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function put(string $url): GuzzleWrapper
    {
        return $this->request('PUT', $url);
    }

    /**
     * Send http delete request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function delete(string $url): GuzzleWrapper
    {
        return $this->request('DELETE', $url);
    }

    /**
     * Send http connect request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function connect(string $url): GuzzleWrapper
    {
        return $this->request('CONNECT', $url);
    }

    /**
     * Send http options request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function options(string $url): GuzzleWrapper
    {
        return $this->request('OPTIONS', $url);
    }

    /**
     * Send http trace request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function trace(string $url): GuzzleWrapper
    {
        return $this->request('TRACE', $url);
    }

    /**
     * Send http patch request
     * @param string $url
     * @return GuzzleWrapper
     */
    public function patch(string $url): GuzzleWrapper
    {
        return $this->request('PATCH', $url);
    }

}