<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper\Client;

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
     * @return $this
     */
    public function withCookie(?CookieJar $cookieJar = null): RequestInterface
    {
        $this->preferredCookie = ($cookieJar ?? new CookieJar());
        return $this;
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function withCookieFile(string $file): RequestInterface
    {
        $jar = new FileCookieJar($file);
        $this->preferredCookie = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function withCookieSession(string $name): RequestInterface
    {
        $jar = new SessionCookieJar($name, true);
        $this->preferredCookie = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function withCookieArray(array $cookies, string $domain): RequestInterface
    {
        $this->preferredCookie = CookieJar::fromArray($cookies, $domain);
        return $this;
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function withSharedCookie(): RequestInterface
    {
        $this->preferredCookie = true;
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