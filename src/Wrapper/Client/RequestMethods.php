<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper\Client;

use Guzwrap\RequestInterface;

/**
 * @internal for internal use only
 */
trait RequestMethods
{
    /**
     * @inheritDoc
     * @return $this
     */
    public function get(string $uri): RequestInterface
    {
        return $this->request('GET', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function head(string $uri): RequestInterface
    {
        return $this->request('HEAD', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function post($formOrClosure): RequestInterface
    {
        return $this->request('POST', $formOrClosure);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function put(string $uri): RequestInterface
    {
        return $this->request('PUT', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function delete(string $uri): RequestInterface
    {
        return $this->request('DELETE', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function connect(string $uri): RequestInterface
    {
        return $this->request('CONNECT', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function options(string $uri): RequestInterface
    {
        return $this->request('OPTIONS', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function trace(string $uri): RequestInterface
    {
        return $this->request('TRACE', $uri);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function patch(string $uri): RequestInterface
    {
        return $this->request('PATCH', $uri);
    }

}