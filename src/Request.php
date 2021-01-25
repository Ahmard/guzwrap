<?php
declare(strict_types=1);

namespace Guzwrap;

use Guzwrap\Wrapper\Form;
use Guzwrap\Wrapper\Guzzle;
use GuzzleHttp\Cookie\CookieJar;
use Psr\Http\Message\StreamInterface;

/**
 * This Class servers as proxy to Guzzle\Wrapper which help provide convenience usage to this package
 * @package Guzwrap
 * @method static Guzzle useRequest(RequestInterface ...$requests) Merge an array of request data with provided one
 * @method static Guzzle useData(array $requestData) Merge an array of request data with provided one
 * @method static Guzzle addOption(string $name, mixed $value) Add option to this request
 * @method static Guzzle request(string $type, mixed $argsOrClosure) Make http request
 * @method static Guzzle exec() Execute the request
 * @method static Guzzle uri(string $uri) Set request uri
 * @method static Guzzle form(callable|Form $callback) Create form
 * @method static Guzzle userAgent(string $userAgent, ?string $chosen = null) Choose user agent
 * @method static Guzzle allowRedirects($options) Whether to allow redirect during this request
 * @method static Guzzle redirects(callable $callback) Describes the redirect behavior of a request.
 * @method static Guzzle auth($optionOrUsername, $typeOrPassword, $type) Set request authentication credentials
 * @method static Guzzle body(mixed $body) he body option is used to control the body of an entity enclosing request (e.g., PUT, POST, PATCH).
 * @method static Guzzle cert($optionOrFile, $password) Set to a string to specify the path to a file containing a PEM formatted client side certificate.
 * @method static Guzzle connectTimeout($seconds) Float describing the number of seconds to wait while trying to connect to a server.
 * @method static Guzzle debug(bool|resource $boolOrStream) Set to true or set to a PHP stream returned by fopen() to enable debug output with the handler used to send a request.
 * @method static Guzzle decodeContent(bool $bool) Decode content
 * @method static Guzzle delay(float $delay) Specify whether or not Content-Encoding responses (gzip, deflate, etc.) are automatically decoded.
 * @method static Guzzle expect($expect) Controls the behavior of the "Expect: 100-Continue" header.
 * @method static Guzzle forceIPResolve($version) Set to "v4" if you want the HTTP handlers to use only ipv4 protocol or "v6" for ipv6 protocol.
 * @method static Guzzle formParams(array $params) Used to send an application/x-www-form-uriencoded POST request.
 * @method static Guzzle header(string|array|callable $headersOrKeyOrClosure, ?string $value = null) Associative array of headers to add to the request.
 * @method static Guzzle httpErrors($bool) Set to false to disable throwing exceptions on an HTTP protocol errors (i.e., 4xx and 5xx responses).
 * @method static Guzzle idnConversion($bool) Internationalized Domain Name (IDN) support (enabled by default if intl extension is available).
 * @method static Guzzle json(string $json) The json option is used to easily upload JSON encoded data as the body of a request.
 * @method static Guzzle multipart(array $data) Sets the body of the request to a multipart/form-data form.
 * @method static Guzzle onHeaders(callable $callback) A callable that is invoked when the HTTP headers of the response have been received but the body has not yet begun to download.
 * @method static Guzzle onStats(callable $callback) Allows you to get access to transfer statistics of a request and access the lower level transfer details of the handler associated with your client.
 * @method static Guzzle onProgress(callable $callback) Monitor request progress
 * @method static Guzzle proxy(string $uri) Pass a string to specify an HTTP proxy, or an array to specify different proxies for different protocols.
 * @method static Guzzle query(string|array $queriesOrName, ?string $queryValue = null) Associative array of query string values or query string to add to the request.
 * @method static Guzzle readTimeout(float $seconds) Float describing the timeout to use when reading a streamed body
 * @method static Guzzle sink(string $file) Specify file path where the body of a response will be saved.
 * @method static Guzzle saveTo(StreamInterface $stream) Specify stream where the body of a response will be saved.
 * @method static Guzzle sslKey(string $fileOrPassword, $password) Specify the path to a file containing a private SSL key in PEM format.
 * @method static Guzzle stream(bool $bool)Set to true to stream a response rather than download it all up-front.
 * @method static Guzzle synchronous(bool $bool) Set to true to inform HTTP handlers that you intend on waiting on the response.
 * @method static Guzzle verify($verify) Describes the SSL certificate verification behavior of a request.
 * @method static Guzzle timeout(float $seconds) Float describing the total timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior).
 * @method static Guzzle version(string $version) Protocol version to use with the request.
 * @method static Guzzle referer(string $refererUri) Set http referrer
 * @method static Guzzle withCookie(?CookieJar $cookie) Use cookie provided by guzzle
 * @method static Guzzle withCookieFile(string $file) Send request with cookie from file and stored to file
 * @method static Guzzle withCookieSession(string $name) Send request with cookie session
 * @method static Guzzle withCookieArray(array $cookies, string $domain) Use multidimensional array as cookie, [key => value]
 * @method static Guzzle withSharedCookie() Use single shared cookie across all requests
 * @method static Guzzle curlOption($option, $value) Define cUrl options
 * @method static Guzzle streamContext($callbackOrStreamContext) Control request stream option
 * @method static Guzzle stack($callbackOrStack) A handler stack represents a stack of middleware to apply to a base handler function.
 * @method static Guzzle middleware(callable $callback) Middleware augments the functionality of handlers by invoking them in the process of generating responses.
 * @method static Guzzle get(string $uri) Send GET request
 * @method static Guzzle head(string $uri) Send HEAD request
 * @method static Guzzle post($uriOrClosure) Send POST request
 * @method static Guzzle put(string $uri) Send http put request
 * @method static Guzzle delete(string $uri) Send http delete request
 * @method static Guzzle connect(string $uri) Send http connect request
 * @method static Guzzle options(string $uri) Send http options request
 * @method static Guzzle trace(string $uri) Send http trace request
 * @method static Guzzle patch(string $uri) Send http patch request
 */
class Request
{
    /**
     * handle first static call
     * @param string $name
     * @param array $arguments
     * @return Guzzle
     */
    public static function __callStatic(string $name, array $arguments): Guzzle
    {
        return self::create()->$name(...$arguments);
    }

    /**
     * Get guzzle wrapper instance
     * @return Guzzle
     */
    public static function create(): Guzzle
    {
        return new Guzzle();
    }

    /**
     * Proxy object calls to Wrapper\Guzzle
     * @param string $name
     * @param array $arguments
     * @return Guzzle
     */
    public function __call(string $name, array $arguments): Guzzle
    {
        return self::create()->$name(...$arguments);
    }
}