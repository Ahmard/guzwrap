<?php

namespace Guzwrap\Core;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;

trait Cookie
{
    protected bool $willUseCookie;

    protected array $willUseThisCookie = [];

    protected SessionCookieJar $willUseCookieSession;

    protected string $willUseThisCookieFile;

    protected string $userCookieChoice = '';


    /**
     * Use cookie provided by guzzle
     * @param null $jar
     * @return Cookie
     */
    public function withCookie($jar = null): Cookie
    {
        if ($jar == null) {
            $jar = new CookieJar();
        }
        $this->userCookieChoice = true;
        return $this;
    }


    /**
     * Send request with cookie from file and stored to file
     * @param string 'file location/filename'
     * @return Cookie
     */
    public function withCookieFile(string $file): Cookie
    {
        $jar = new FileCookieJar($file);
        $this->userCookieChoice = $jar;
        return $this;
    }


    /**
     * Send request with cookie session
     * @param string $name
     * @return Cookie
     */
    public function withCookieSession(string $name): Cookie
    {
        $jar = new SessionCookieJar($name, true);
        $this->willUseCookieSession = $jar;
        return $this;
    }


    /**
     * If user have cookie in hand
     * @param array cookie list
     * @param string $domain
     * @return Cookie
     */
    public function withCookieArray(array $cookies, string $domain): Cookie
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