<?php

namespace Guzwrap\Wrapper;

use Guzwrap\RequestInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;

trait Cookie
{
    protected SessionCookieJar $willUseCookieSession;

    /**
     * @var mixed $userCookieChoice
     */
    protected $userCookieChoice;


    /**
     * @inheritDoc
     * @return static
     */
    public function withCookie(?CookieJar $jar = null): RequestInterface
    {
        if ($jar == null) {
            $jar = new CookieJar();
        }

        $this->userCookieChoice = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function withCookieFile(string $file): RequestInterface
    {
        $jar = new FileCookieJar($file);
        $this->userCookieChoice = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function withCookieSession(string $name): RequestInterface
    {
        $jar = new SessionCookieJar($name, true);
        $this->willUseCookieSession = $jar;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function withCookieArray(array $cookies, string $domain): RequestInterface
    {
        $jar = CookieJar::fromArray($cookies, $domain);
        $this->userCookieChoice = $jar;
        return $this;
    }

    protected function getCookieOptions(): array
    {
        if ($this->userCookieChoice == null) {
            return [];
        }
        return ['cookies' => $this->userCookieChoice];
    }
}