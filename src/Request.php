<?php

namespace Guzwrap;

use Guzwrap\Classes\TheWrapper;

/**
 * Class Request
 * @package Guzwrap
 * @method static TheWrapper addOption(string $name, $value) Add option to this request
 * @method static TheWrapper request(string $type, $argsOrClosure) Make http request
 * @method static TheWrapper exec() Execute the request
 * @method static TheWrapper url(string $url) Set request url
 * @method static TheWrapper userAgent(string $userAgent, $chosen) Choose user agent
 * @method static TheWrapper allowRedirects($options) Describes the redirect behavior of a request.
 * @method static TheWrapper redirects(callable $callback) Set redirect handler
 * @method static TheWrapper auth($optionOrUsername, $typeOrPassword, $type) Set request authentication credentials
 * @method static TheWrapper body($body) Set request body
 * @method static TheWrapper cert($optionOrFile, $password) Set certificate
 * @method static TheWrapper connectTimeout($seconds) Set connection timeout
 * @method static TheWrapper debug(bool $bool) Whether to display debug information
 * @method static TheWrapper decodeContent($bool) Decode content
 * @method static TheWrapper delay(float $delay) Set delay to a request
 * @method static TheWrapper expect($expect) Set expect value
 * @method static TheWrapper forceIPResolve($version) Force to resolve ip address
 * @method static TheWrapper formParams($params) Set request form parameters
 * @method static TheWrapper header($headersOrKeyOrClosure, $value) Set request headers
 * @method static TheWrapper httpErrors($bool) Request http erros
 * @method static TheWrapper idnConversion($bool) IDN Conversion
 * @method static TheWrapper json($json) Mark request's content-type as json
 * @method static TheWrapper multipart($data) Set request as multipart
 * @method static TheWrapper onHeaders(callable $callback) Listen to headers event
 * @method static TheWrapper onStats(callable $callback) Listen to stats event
 * @method static TheWrapper progress(callable $callback) Monitor request progress
 * @method static TheWrapper proxy($url) Set request proxy url
 * @method static TheWrapper query($queries) Url queries
 * @method static TheWrapper readTimeout(float $seconds) Set read timeout
 * @method static TheWrapper sink(string $file) Save request response body to file
 * @method static TheWrapper saveTo(resource $stream) Save request response body to file
 * @method static TheWrapper sslKey(string $fileOrPassword, $password) Provide sslkey for this request
 * @method static TheWrapper stream(bool $bool) Whether to stream this request
 * @method static TheWrapper synchronous(bool $bool) Whether the request should be asynchronous
 * @method static TheWrapper verify($verify) Request verification
 * @method static TheWrapper timeout(float $seconds) Set request timeout
 * @method static TheWrapper version(string $version) Set request version
 * @method static TheWrapper withCookie($jar) Use cookie provided by guzzle
 * @method static TheWrapper withCookieFile($file) Send request with cookie from file and stored to file
 * @method static TheWrapper withCookieSession(string $name) Send request with cookie session
 * @method static TheWrapper withCookieArray($cookies, $domain) If user have cookie in hand
 * @method static TheWrapper get(string $url) Send GET request
 * @method static TheWrapper head(string $url) Send HEAD request
 * @method static TheWrapper post($urlOrClosure) Send POST request
 * @method static TheWrapper put(string $url) Send http put request
 * @method static TheWrapper delete(string $url) Send http delete request
 * @method static TheWrapper connect(string $url) Send http connect request
 * @method static TheWrapper options(string $url) Send http options request
 * @method static TheWrapper trace(string $url) Send http trace request
 * @method static TheWrapper patch(string $url) Send http patch request
 */
class Request
{
    /**
     * handle first static call
     * @param $method
     * @param $args
     * @return TheWrapper
     */
    public static function __callStatic($method, $args)
    {
        return (new TheWrapper())->$method(...$args);
    }

    /**
     * Get the request wrapper instance
     * @param void
     * @return TheWrapper
     */
    public static function getInstance()
    {
        return new TheWrapper();
    }
}