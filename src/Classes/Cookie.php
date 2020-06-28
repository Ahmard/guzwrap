<?php
namespace Guzwrap\Classes;

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
     * @return Guzwrap\Request
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
     * @return Guzwrap\Request
     */
    public function withCookieFile(string $file)
    {
        $jar = new FileCookieJar($file);
        $this->userCookieChoice = $jar;
        return $this;
    }
    
    
    /**
     * Send request with cookie session
     * @param string $name 
     * @return Guzwrap\Request
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
     * @return Guzwrap\Request
     */
    public function withCookieArray(array $cookies, string $domain)
    {
        $jar = CookieJar::fromArray($cookies, $domain);
        $this->userCookieChoice = $jar;
        return $this;
    }
    
    
    protected function getCookieOptions()
    {
        if($this->userCookieChoice == null){
            return [];
        }
        return ['cookies' => $this->userCookieChoice];
    }
}