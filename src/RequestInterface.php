<?php


namespace Guzwrap;


use Guzwrap\Wrapper\Form;
use GuzzleHttp\Cookie\CookieJar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

interface RequestInterface
{
    /**
     * Send GET request
     * @param string $uri
     * @return $this
     */
    public function get(string $uri): RequestInterface;

    /**
     * Send HEAD request
     * @param string $uri
     * @return $this
     */
    public function head(string $uri): RequestInterface;

    /**
     * Send POST request
     * @param callable|Form $formOrClosure
     * @return $this
     */
    public function post($formOrClosure): RequestInterface;

    /**
     * Send http delete request
     * @param string $uri
     * @return $this
     */
    public function delete(string $uri): RequestInterface;

    /**
     * Send http connect request
     * @param string $uri
     * @return $this
     */
    public function connect(string $uri): RequestInterface;

    /**
     * Use cookie provided by guzzle
     * @param CookieJar|null $jar
     * @return RequestInterface
     */
    public function withCookie(?CookieJar $jar = null): RequestInterface;

    /**
     * Send request with cookie from file and stored to file
     * @param string $file 'file location/filename'
     * @return static
     */
    public function withCookieFile(string $file): RequestInterface;

    /**
     * Send request with cookie session
     * @param string $name
     * @return static
     */
    public function withCookieSession(string $name): RequestInterface;

    /**
     * Send request with an array of cookies
     * @param array $cookies cookie list
     * @param string $domain
     * @return static
     */
    public function withCookieArray(array $cookies, string $domain): RequestInterface;

    /**
     * Send http options request
     * @param string $uri
     * @return $this
     */
    public function options(string $uri): RequestInterface;

    /**
     * Send http trace request
     * @param string $uri
     * @return $this
     */
    public function trace(string $uri): RequestInterface;

    /**
     * Send http patch request
     * @param string $uri
     * @return $this
     */
    public function patch(string $uri): RequestInterface;

    /**
     * Send http put request
     * @param string $uri
     * @return $this
     */
    public function put(string $uri): RequestInterface;

    /**
     * Add option to this request
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addOption(string $name, $value): RequestInterface;

    /**
     * Make http request
     * @param string $method A request method, e.g: GET, POST...
     * @param string|callable|array|Form $data A callable or an array of request data
     * @param array $onceData This data will be one, i.e used for this request only
     * @return $this
     */
    public function request(string $method, $data, array $onceData = []): RequestInterface;

    /**
     * Use request data and construct new request with it
     * @param RequestInterface ...$request
     * @return $this
     */
    public function useRequest(RequestInterface ...$request): RequestInterface;

    /**
     * Merge an array of request data with provided one
     * @param array $options
     * @return $this
     */
    public function useData(array $options): RequestInterface;

    /**
     * Get generated request data,
     * this data can be passed to guzzle directly
     * @return mixed[]
     */
    public function getData(): array;

    /**
     * Execute the request
     * @return ResponseInterface
     * @throws Throwable
     */
    public function exec(): ResponseInterface;

    /**
     * Set request uri
     * @param string $uri
     * @return $this
     */
    public function uri(string $uri): RequestInterface;

    /**
     * Create form
     * @param callable|Form $callback
     * @return $this
     */
    public function form($callback): RequestInterface;

    /**
     * Choose user agent
     * @param string|UserAgent $userAgent
     * @param string|null $chosen
     * @return $this
     */
    public function userAgent($userAgent, ?string $chosen = null): RequestInterface;

    /**
     * Describes the redirect behavior of a request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#allow-redirects
     * @param bool $options
     * @return $this
     */
    public function allowRedirects(bool $options = true): RequestInterface;

    /**
     * Set redirect handler
     * @param callable $callback
     * @return $this
     */
    public function redirects(callable $callback): RequestInterface;

    /**
     * Pass an array of HTTP authentication parameters to use with the request.
     * The array must contain the username in index [0],
     * the password in index [1],
     * and you can optionally provide a built-in authentication type in index [2].
     * Pass null to disable authentication for a request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#auth
     * @param string|array $optionOrUsername
     * @param string|null $typeOrPassword
     * @param string|null $type
     * @return $this
     */
    public function auth($optionOrUsername, ?string $typeOrPassword = null, ?string $type = null): RequestInterface;

    /**
     * The body option is used to control the body of an entity enclosing request (e.g., PUT, POST, PATCH).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#body
     * @param mixed $body
     * @return $this
     */
    public function body($body): RequestInterface;

    /**
     * Set to a string to specify the path to a file containing a PEM formatted client side certificate.
     * If a password is required, then set to an array containing the path to the PEM file in the
     * first array element followed by the password required for the certificate in the second array element.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#cert
     * @param string|array $optionOrFilePath
     * @param string|null $password
     * @return $this
     */
    public function cert($optionOrFilePath, ?string $password = null): RequestInterface;

    /**
     * Float describing the number of seconds to wait while trying to connect to a server.
     * Use 0 to wait indefinitely (the default behavior).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#connect-timeout
     * @param float $seconds
     * @return $this
     */
    public function connectTimeout(float $seconds): RequestInterface;

    /**
     * Set to true or set to a PHP stream returned by fopen() to enable debug output with the handler used to send a request.
     * For example, when using curi to transfer requests, curi's verbose of CuriOPT_VERBOSE will be emitted.
     * When using the PHP stream wrapper, stream wrapper notifications will be emitted.
     * If set to true, the output is written to PHP's STDOUT.
     * If a PHP stream is provided, output is written to the stream.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#debug
     * @param bool|resource $boolOrStream
     * @return $this
     */
    public function debug($boolOrStream = true): RequestInterface;

    /**
     * Decode content
     * @param bool $bool
     * @return $this
     */
    public function decodeContent(bool $bool = true): RequestInterface;

    /**
     * Specify whether or not Content-Encoding responses (gzip, deflate, etc.) are automatically decoded.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#decode-content
     * @param float $delay
     * @return $this
     */
    public function delay(float $delay): RequestInterface;

    /**
     * Controls the behavior of the "Expect: 100-Continue" header.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#expect
     * @param int|bool $expect
     * @return $this
     */
    public function expect($expect): RequestInterface;

    /**
     * Set to "v4" if you want the HTTP handlers to use only ipv4 protocol or "v6" for ipv6 protocol.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#force-ip-resolve
     * @param string $version
     * @return $this
     */
    public function forceIPResolve(string $version): RequestInterface;

    /**
     * Used to send an application/x-www-form-uriencoded POST request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#form-params
     * @param array $params
     * @return $this
     */
    public function formParams(array $params): RequestInterface;

    /**
     * Associative array of headers to add to the request.
     * Each key is the name of a header, and each value is a string or array of strings representing the header field values.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#headers
     * @param string|array|callable $headersOrKeyOrClosure
     * @param string|null $value
     * @return $this
     */
    public function header($headersOrKeyOrClosure, ?string $value = null): RequestInterface;

    /**
     * Set to false to disable throwing exceptions on an HTTP protocol errors (i.e., 4xx and 5xx responses).
     * Exceptions are thrown by default when HTTP protocol errors are encountered.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#http-errors
     * @param bool $bool
     * @return $this
     */
    public function httpErrors(bool $bool = true): RequestInterface;

    /**
     * Internationalized Domain Name (IDN) support (enabled by default if intl extension is available).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#idn-conversion
     * @param bool $bool
     * @return $this
     */
    public function idnConversion(bool $bool = true): RequestInterface;

    /**
     * The json option is used to easily upload JSON encoded data as the body of a request.
     * A Content-Type header of application/json will be added if no Content-Type header is already present on the message.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#json
     * @param string $json
     * @return $this
     */
    public function json(string $json): RequestInterface;

    /**
     * Sets the body of the request to a multipart/form-data form.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#multipart
     * @param array $data
     * @return $this
     */
    public function multipart(array $data): RequestInterface;

    /**
     * A callable that is invoked when the HTTP headers of the response have been received but the body has not yet begun to download.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#on-headers
     * @param callable $callback
     * @return $this
     */
    public function onHeaders(callable $callback): RequestInterface;

    /**
     * Allows you to get access to transfer statistics of a request and access the lower level transfer details of the handler associated
     * with your client. on_stats is a callable that is invoked when a handler has finished sending a request.
     * The callback is invoked with transfer statistics about the request, the response received,
     * or the error encountered. Included in the data is the total amount of time taken to send the request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#on-stats
     * Listen to stats event
     * @param callable $callback
     * @return $this
     */
    public function onStats(callable $callback): RequestInterface;

    /**
     * Defines a function to invoke when transfer progress is made.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#progress
     * @param callable $callback
     * @return $this
     */
    public function onProgress(callable $callback): RequestInterface;

    /**
     * Pass a string to specify an HTTP proxy, or an array to specify different proxies for different protocols.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#proxy
     * @param string $uri
     * @return $this
     */
    public function proxy(string $uri): RequestInterface;

    /**
     * Associative array of query string values or query string to add to the request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#query
     * @param string|array $queriesOrName an array of queries or query name
     * @param string|null $queryValue
     * @return $this
     */
    public function query($queriesOrName, ?string $queryValue = null): RequestInterface;

    /**
     * Float describing the timeout to use when reading a streamed body
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#read-timeout
     * @param float $seconds
     * @return $this
     */
    public function readTimeout(float $seconds): RequestInterface;

    /**
     * Specify file path where the body of a response will be saved.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#sink
     * @param mixed $file string (path to file on disk), fopen() resource, Psr\Http\Message\StreamInterface
     * @return $this
     */
    public function sink($file): RequestInterface;

    /**
     * Specify resource/stream where the body of a response will be saved.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#sink
     * @param StreamInterface $stream
     * @return $this
     */
    public function saveTo(StreamInterface $stream): RequestInterface;

    /**
     * Specify the path to a file containing a private SSL key in PEM format.
     * If a password is required, then set to an array containing the path to the
     * SSL key in the first array element followed by the password required for the certificate in the second element.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#ssl-key
     * @param string|array $fileOrPassword
     * @param string|null $password
     * @return $this
     */
    public function sslKey($fileOrPassword, ?string $password = null): RequestInterface;

    /**
     * Set to true to stream a response rather than download it all up-front.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#stream
     * @param bool $bool
     * @return $this
     */
    public function stream(bool $bool = true): RequestInterface;

    /**
     * Set to true to inform HTTP handlers that you intend on waiting on the response.
     * This can be useful for optimizations.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#synchronous
     * @param bool $bool
     * @return $this
     */
    public function synchronous(bool $bool = true): RequestInterface;

    /**
     * Describes the SSL certificate verification behavior of a request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#verify
     * @param string|bool $verify
     * @return $this
     */
    public function verify($verify): RequestInterface;

    /**
     * Float describing the total timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#timeout
     * @param float $seconds
     * @return $this
     */
    public function timeout(float $seconds): RequestInterface;

    /**
     * Protocol version to use with the request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#version
     * @param string $version
     * @return $this
     */
    public function version(string $version): RequestInterface;

    /**
     * Send http request with preferred referer uri
     * @param string $refererUri
     * @return $this
     */
    public function referer(string $refererUri): RequestInterface;
}