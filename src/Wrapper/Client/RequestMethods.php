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
     */
    public function get(string $uri): RequestInterface
    {
        return $this->request('GET', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function head(string $uri): RequestInterface
    {
        return $this->request('HEAD', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function post($formOrClosure): RequestInterface
    {
        return $this->request('POST', $formOrClosure);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function put(string $uri): RequestInterface
    {
        return $this->request('PUT', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function delete(string $uri): RequestInterface
    {
        return $this->request('DELETE', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function connect(string $uri): RequestInterface
    {
        return $this->request('CONNECT', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function options(string $uri): RequestInterface
    {
        return $this->request('OPTIONS', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function trace(string $uri): RequestInterface
    {
        return $this->request('TRACE', $uri);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function patch(string $uri): RequestInterface
    {
        return $this->request('PATCH', $uri);
    }

}