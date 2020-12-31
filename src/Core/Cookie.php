<?php

namespace Guzwrap\Core;

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
     * Use cookie provided by guzzle
     * @param CookieJar|null $jar
     * @return static
     */
    public function withCookie(?CookieJar $jar = null): Cookie
    {
        if ($jar == null) {
            $jar = new CookieJar();
        }

        $this->userCookieChoice = $jar;
        return $this;
    }

    /**
     * Send request with cookie from file and stored to file
     * @param string $file 'file location/filename'
     * @return static
     */
    public function withCookieFile(string $file): GuzzleWrapper
    {
        $jar = new FileCookieJar($file);
        $this->userCookieChoice = $jar;
        return $this;
    }

    /**
     * Send request with cookie session
     * @param string $name
     * @return static
     */
    public function withCookieSession(string $name): GuzzleWrapper
    {
        $jar = new SessionCookieJar($name, true);
        $this->willUseCookieSession = $jar;
        return $this;
    }

    /**
     * Send request with an array of cookies
     * @param array $cookies cookie list
     * @param string $domain
     * @return static
     */
    public function withCookieArray(array $cookies, string $domain): GuzzleWrapper
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