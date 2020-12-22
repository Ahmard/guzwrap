<?php

namespace Guzwrap;

use Guzwrap\Core\GuzzleWrapper;

/**
 * Class Request
 * @package Guzwrap
 * @method static GuzzleWrapper addOption(string $name, $value) Add option to this request
 * @method static GuzzleWrapper request(string $type, $argsOrClosure) Make http request
 * @method static GuzzleWrapper exec() Execute the request
 * @method static GuzzleWrapper url(string $url) Set request url
 * @method static GuzzleWrapper userAgent(string $userAgent, $chosen) Choose user agent
 * @method static GuzzleWrapper allowRedirects($options) Describes the redirect behavior of a request.
 * @method static GuzzleWrapper redirects(callable $callback) Set redirect handler
 * @method static GuzzleWrapper auth($optionOrUsername, $typeOrPassword, $type) Set request authentication credentials
 * @method static GuzzleWrapper body($body) Set request body
 * @method static GuzzleWrapper cert($optionOrFile, $password) Set certificate
 * @method static GuzzleWrapper connectTimeout($seconds) Set connection timeout
 * @method static GuzzleWrapper debug(bool $bool) Whether to display debug information
 * @method static GuzzleWrapper decodeContent($bool) Decode content
 * @method static GuzzleWrapper delay(float $delay) Set delay to a request
 * @method static GuzzleWrapper expect($expect) Set expect value
 * @method static GuzzleWrapper forceIPResolve($version) Force to resolve ip address
 * @method static GuzzleWrapper formParams($params) Set request form parameters
 * @method static GuzzleWrapper header($headersOrKeyOrClosure, $value) Set request headers
 * @method static GuzzleWrapper httpErrors($bool) Request http erros
 * @method static GuzzleWrapper idnConversion($bool) IDN Conversion
 * @method static GuzzleWrapper json($json) Mark request's content-type as json
 * @method static GuzzleWrapper multipart($data) Set request as multipart
 * @method static GuzzleWrapper onHeaders(callable $callback) Listen to headers event
 * @method static GuzzleWrapper onStats(callable $callback) Listen to stats event
 * @method static GuzzleWrapper progress(callable $callback) Monitor request progress
 * @method static GuzzleWrapper proxy($url) Set request proxy url
 * @method static GuzzleWrapper query($queries) Url queries
 * @method static GuzzleWrapper readTimeout(float $seconds) Set read timeout
 * @method static GuzzleWrapper sink(string $file) Save request response body to file
 * @method static GuzzleWrapper saveTo(resource $stream) Save request response body to file
 * @method static GuzzleWrapper sslKey(string $fileOrPassword, $password) Provide sslkey for this request
 * @method static GuzzleWrapper stream(bool $bool) Whether to stream this request
 * @method static GuzzleWrapper synchronous(bool $bool) Whether the request should be asynchronous
 * @method static GuzzleWrapper verify($verify) Request verification
 * @method static GuzzleWrapper timeout(float $seconds) Set request timeout
 * @method static GuzzleWrapper version(string $version) Set request version
 * @method static GuzzleWrapper withCookie($jar) Use cookie provided by guzzle
 * @method static GuzzleWrapper withCookieFile($file) Send request with cookie from file and stored to file
 * @method static GuzzleWrapper withCookieSession(string $name) Send request with cookie session
 * @method static GuzzleWrapper withCookieArray($cookies, $domain) If user have cookie in hand
 * @method static GuzzleWrapper get(string $url) Send GET request
 * @method static GuzzleWrapper head(string $url) Send HEAD request
 * @method static GuzzleWrapper post($urlOrClosure) Send POST request
 * @method static GuzzleWrapper put(string $url) Send http put request
 * @method static GuzzleWrapper delete(string $url) Send http delete request
 * @method static GuzzleWrapper connect(string $url) Send http connect request
 * @method static GuzzleWrapper options(string $url) Send http options request
 * @method static GuzzleWrapper trace(string $url) Send http trace request
 * @method static GuzzleWrapper patch(string $url) Send http patch request
 */
class Request
{
    /**
     * handle first static call
     * @param $method
     * @param $args
     * @return GuzzleWrapper
     */
    public static function __callStatic($method, $args): GuzzleWrapper
    {
        return (new GuzzleWrapper())->$method(...$args);
    }

    /**
     * Get the request wrapper instance
     * @param void
     * @return GuzzleWrapper
     */
    public static function getInstance(): GuzzleWrapper
    {
        return new GuzzleWrapper();
    }
}