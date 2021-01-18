<?php

namespace Guzwrap;

use Guzwrap\Wrapper\Form;
use Guzwrap\Wrapper\Guzzle;
use GuzzleHttp\Cookie\CookieJar;

/**
 * Class Request
 * @package Guzwrap
 * @method static Guzzle useRequest(RequestInterface ...$requests) Merge an array of request data with provided one
 * @method static Guzzle useRequestData(array $requestData) Merge an array of request data with provided one
 * @method static Guzzle addOption(string $name, mixed $value) Add option to this request
 * @method static Guzzle request(string $type, mixed $argsOrClosure) Make http request
 * @method static Guzzle exec() Execute the request
 * @method static Guzzle url(string $url) Set request url
 * @method static Guzzle form(callable|Form $callback) Create form
 * @method static Guzzle userAgent(string $userAgent, ?string $chosen = null) Choose user agent
 * @method static Guzzle allowRedirects($options) Describes the redirect behavior of a request.
 * @method static Guzzle redirects(callable $callback) Set redirect handler
 * @method static Guzzle auth($optionOrUsername, $typeOrPassword, $type) Set request authentication credentials
 * @method static Guzzle body($body) Set request body
 * @method static Guzzle cert($optionOrFile, $password) Set certificate
 * @method static Guzzle connectTimeout($seconds) Set connection timeout
 * @method static Guzzle debug(bool $bool) Whether to display debug information
 * @method static Guzzle decodeContent(bool $bool) Decode content
 * @method static Guzzle delay(float $delay) Set delay to a request
 * @method static Guzzle expect($expect) Set expect value
 * @method static Guzzle forceIPResolve($version) Force to resolve ip address
 * @method static Guzzle formParams(array $params) Set request form parameters
 * @method static Guzzle header($headersOrKeyOrClosure, $value) Set request headers
 * @method static Guzzle httpErrors($bool) Request http errors
 * @method static Guzzle idnConversion($bool) IDN Conversion
 * @method static Guzzle json(string $json) Mark request's content-type as json
 * @method static Guzzle multipart(array $data) Set request as multipart
 * @method static Guzzle onHeaders(callable $callback) Listen to headers event
 * @method static Guzzle onStats(callable $callback) Listen to stats event
 * @method static Guzzle progress(callable $callback) Monitor request progress
 * @method static Guzzle proxy(string $url) Set request proxy url
 * @method static Guzzle query(mixed $queriesOrName, ?string $queryValue = null) Url queries
 * @method static Guzzle readTimeout(float $seconds) Set read timeout
 * @method static Guzzle sink(string $file) Save request response body to file
 * @method static Guzzle saveTo(resource $stream) Save request response body to file
 * @method static Guzzle sslKey(string $fileOrPassword, $password) Provide sslkey for this request
 * @method static Guzzle stream(bool $bool) Whether to stream this request
 * @method static Guzzle synchronous(bool $bool) Whether the request should be asynchronous
 * @method static Guzzle verify($verify) Request verification
 * @method static Guzzle timeout(float $seconds) Set request timeout
 * @method static Guzzle version(string $version) Set request version
 * @method static Guzzle referer(string $refererUrl) Set http referrer
 * @method static Guzzle withCookie(?CookieJar $cookie) Use cookie provided by guzzle
 * @method static Guzzle withCookieFile(string $file) Send request with cookie from file and stored to file
 * @method static Guzzle withCookieSession(string $name) Send request with cookie session
 * @method static Guzzle withCookieArray(array $cookies, string $domain) If user have cookie in hand
 * @method static Guzzle get(string $url) Send GET request
 * @method static Guzzle head(string $url) Send HEAD request
 * @method static Guzzle post($urlOrClosure) Send POST request
 * @method static Guzzle put(string $url) Send http put request
 * @method static Guzzle delete(string $url) Send http delete request
 * @method static Guzzle connect(string $url) Send http connect request
 * @method static Guzzle options(string $url) Send http options request
 * @method static Guzzle trace(string $url) Send http trace request
 * @method static Guzzle patch(string $url) Send http patch request
 */
class Request
{
    /**
     * handle first static call
     * @param string $method
     * @param array $args
     * @return Guzzle
     */
    public static function __callStatic(string $method, array $args): Guzzle
    {
        return (new Guzzle())->$method(...$args);
    }

    /**
     * Get the request wrapper instance
     * @return Guzzle
     */
    public static function getInstance(): Guzzle
    {
        return new Guzzle();
    }
}