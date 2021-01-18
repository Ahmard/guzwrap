<?php

namespace Guzwrap\Wrapper;

use Guzwrap\RequestInterface;

/**
 * @internal for internal use only
 */
trait RequestMethods
{
    /**
     * @inheritDoc
     * @return static
     */
    public function get(string $url): RequestInterface
    {
        return $this->request('GET', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function head(string $url): RequestInterface
    {
        return $this->request('HEAD', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function post($urlOrClosure): RequestInterface
    {
        return $this->request('POST', $urlOrClosure);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function put(string $url): RequestInterface
    {
        return $this->request('PUT', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function delete(string $url): RequestInterface
    {
        return $this->request('DELETE', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function connect(string $url): RequestInterface
    {
        return $this->request('CONNECT', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function options(string $url): RequestInterface
    {
        return $this->request('OPTIONS', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function trace(string $url): RequestInterface
    {
        return $this->request('TRACE', $url);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function patch(string $url): RequestInterface
    {
        return $this->request('PATCH', $url);
    }

}