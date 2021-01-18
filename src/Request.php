<?php

namespace Guzwrap;

use Guzwrap\Core\GuzzleWrapper;
use GuzzleHttp\Cookie\CookieJar;

/**
 * Class Request
 * @package Guzwrap
 * @method static GuzzleWrapper useRequest(RequestInterface ...$requests) Merge an array of request data with provided one
 * @method static GuzzleWrapper useRequestData(array $requestData) Merge an array of request data with provided one
 * @method static GuzzleWrapper addOption(string $name, mixed $value) Add option to this request
 * @method static GuzzleWrapper request(string $type, mixed $argsOrClosure) Make http request
 * @method static GuzzleWrapper exec() Execute the request
 * @method static GuzzleWrapper url(string $url) Set request url
 * @method static GuzzleWrapper userAgent(string $userAgent, ?string $chosen = null) Choose user agent
 * @method static GuzzleWrapper allowRedirects($options) Describes the redirect behavior of a request.
 * @method static GuzzleWrapper redirects(callable $callback) Set redirect handler
 * @method static GuzzleWrapper auth($optionOrUsername, $typeOrPassword, $type) Set request authentication credentials
 * @method static GuzzleWrapper body($body) Set request body
 * @method static GuzzleWrapper cert($optionOrFile, $password) Set certificate
 * @method static GuzzleWrapper connectTimeout($seconds) Set connection timeout
 * @method static GuzzleWrapper debug(bool $bool) Whether to display debug information
 * @method static GuzzleWrapper decodeContent(bool $bool) Decode content
 * @method static GuzzleWrapper delay(float $delay) Set delay to a request
 * @method static GuzzleWrapper expect($expect) Set expect value
 * @method static GuzzleWrapper forceIPResolve($version) Force to resolve ip address
 * @method static GuzzleWrapper formParams(array $params) Set request form parameters
 * @method static GuzzleWrapper header($headersOrKeyOrClosure, $value) Set request headers
 * @method static GuzzleWrapper httpErrors($bool) Request http errors
 * @method static GuzzleWrapper idnConversion($bool) IDN Conversion
 * @method static GuzzleWrapper json(string $json) Mark request's content-type as json
 * @method static GuzzleWrapper multipart(array $data) Set request as multipart
 * @method static GuzzleWrapper onHeaders(callable $callback) Listen to headers event
 * @method static GuzzleWrapper onStats(callable $callback) Listen to stats event
 * @method static GuzzleWrapper progress(callable $callback) Monitor request progress
 * @method static GuzzleWrapper proxy(string $url) Set request proxy url
 * @method static GuzzleWrapper query(mixed $queriesOrName, ?string $queryValue = null) Url queries
 * @method static GuzzleWrapper readTimeout(float $seconds) Set read timeout
 * @method static GuzzleWrapper sink(string $file) Save request response body to file
 * @method static GuzzleWrapper saveTo(resource $stream) Save request response body to file
 * @method static GuzzleWrapper sslKey(string $fileOrPassword, $password) Provide sslkey for this request
 * @method static GuzzleWrapper stream(bool $bool) Whether to stream this request
 * @method static GuzzleWrapper synchronous(bool $bool) Whether the request should be asynchronous
 * @method static GuzzleWrapper verify($verify) Request verification
 * @method static GuzzleWrapper timeout(float $seconds) Set request timeout
 * @method static GuzzleWrapper version(string $version) Set request version
 * @method static GuzzleWrapper referer(string $refererUrl) Set http referrer
 * @method static GuzzleWrapper withCookie(?CookieJar $cookie) Use cookie provided by guzzle
 * @method static GuzzleWrapper withCookieFile(string $file) Send request with cookie from file and stored to file
 * @method static GuzzleWrapper withCookieSession(string $name) Send request with cookie session
 * @method static GuzzleWrapper withCookieArray(array $cookies, string $domain) If user have cookie in hand
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
     * @param string $method
     * @param array $args
     * @return GuzzleWrapper
     */
    public static function __callStatic(string $method, array $args): GuzzleWrapper
    {
        return (new GuzzleWrapper())->$method(...$args);
    }

    /**
     * Get the request wrapper instance
     * @return GuzzleWrapper
     */
    public static function getInstance(): GuzzleWrapper
    {
        return new GuzzleWrapper();
    }
}