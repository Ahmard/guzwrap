<?php

namespace Guzwrap\Wrapper;

use Guzwrap\RequestInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;

trait Cookie
{
    /**
     * @var mixed $preferredCookie
     */
    protected $preferredCookie;


    /**
     * @inheritDoc
     * @return static
     */
    public function withCookie(?CookieJar $jar = null): RequestInterface
    {
        if ($jar == null) {
            $jar = new CookieJar();
        }

        $this->preferredCookie = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function withCookieFile(string $file): RequestInterface
    {
        $jar = new FileCookieJar($file);
        $this->preferredCookie = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function withCookieSession(string $name): RequestInterface
    {
        $jar = new SessionCookieJar($name, true);
        $this->preferredCookie = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function withCookieArray(array $cookies, string $domain): RequestInterface
    {
        $this->preferredCookie = CookieJar::fromArray($cookies, $domain);
        return $this;
    }

    protected function getCookieOptions(): array
    {
        if ($this->preferredCookie == null) {
            return [];
        }

        return ['cookies' => $this->preferredCookie];
    }
}