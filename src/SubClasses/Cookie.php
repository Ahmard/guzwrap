<?php
namespace Guzwrap\SubClasses;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Cookie\FileCookieJar;

trait Cookie
{
    protected boolean $willUseCookie;
    
    protected array $willUseThisCookie = [];
    
    protected boolean $willUseCookieSession;
    
    protected string $willUseThisCookieFile;
    
    
    /**
     * Use cookie provided by guzzle
     * @param void
     * @return Queliwrap\Client
     */
    public function withCookie($jar = null)
    {
        if($jar == null){
            $jar = new CookieJar();
        }
        $this->userCookieChoice = true;
        return $this;
    }
    
    
    /**
     * Send request with cookie from file and stored to file
     * @param string 'fileloc/filename'
     * @return Queliwrap\Client
     */
    public function withCookieFile(string $file)
    {
        $jar = new FileCookieJar($file);
        $this->userCookieChoice = $jar;
        return $this;
    }
    
    
    /**
     * Send request with cookie session
     * @param void
     * @return Queliwrap\Client
     */
    public function withCookieSession($name)
    {
        $jar = new SessionCookieJar($name, true);
        $this->willUseCookieSession = $jar;
        return $this;
    }
    
    
    /**
     * If user have cookie in hand
     * @param array cookie list
     * @return Queliwrap\Client
     */
    public function withCookieArray(array $cookies)
    {
        $jar = CookieJar::fromArray($cookies);
        $this->userCookieChoice = $jar;
        return $this;
    }
    
    
    protected function getCookieRequestOptions()
    {
        return ['cookies' => $this->userCookieChoice];
    }
}