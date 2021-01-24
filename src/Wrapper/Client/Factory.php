<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper\Client;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

/**
 * Class ClientFactory
 * @package Guzwrap\Wrapper
 * @internal Temper with this class at your own risk
 */
class Factory
{
    protected static Factory $instance;
    protected static CookieJar $sharedCookie;


    public static function create(array &$requestData): Client
    {
        //Handle shared cookies
        if (isset($requestData['cookies']) && true === $requestData['cookies']) {
            $requestData['guzwrap']['shared_cookie'] = true;
            $requestData['cookies'] = self::getSharedCookie();
        } //The following conditional block will help use shared cookie when request data is imported.
        elseif (
            isset($requestData['guzwrap']['shared_cookie'])
            && true === $requestData['guzwrap']['shared_cookie']
        ) {
            $requestData['cookies'] = self::getSharedCookie();
        }

        return new Client($requestData);
    }

    protected static function getSharedCookie(): CookieJar
    {
        if (!isset(self::$sharedCookie)) {
            self::$sharedCookie = new CookieJar();
        }

        return self::$sharedCookie;
    }
}