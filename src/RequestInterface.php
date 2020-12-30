<?php


namespace Guzwrap;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

interface RequestInterface
{
    /**
     * Add option to this request
     * @param string $name
     * @param mixed $value
     * @return RequestInterface
     */
    public function addOption(string $name, $value): RequestInterface;

    /**
     * Make http request
     * @param string $type
     * @param mixed ...$argsOrClosure
     * @return RequestInterface
     */
    public function request(string $type, ...$argsOrClosure): RequestInterface;

    /**
     * Get generated request data
     * this data can be passed to guzzle directly
     * @return string[]
     */
    public function getRequestData(): array;

    /**
     * Execute the request
     * @return ResponseInterface
     * @throws Throwable
     */
    public function exec(): ResponseInterface;

    /**
     * Set request url
     * @param string $url
     * @return RequestInterface
     */
    public function url(string $url): RequestInterface;

    /**
     * Choose user agent
     * @param string $userAgent
     * @param string|null $chosen
     * @return RequestInterface
     */
    public function userAgent(string $userAgent, string $chosen = null): RequestInterface;

    /**
     * Describes the redirect behavior of a request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#allow-redirects
     * @param bool $options
     * @return RequestInterface
     */
    public function allowRedirects(bool $options = true): RequestInterface;

    /**
     * Set redirect handler
     * @param callable $callback
     * @return RequestInterface
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
     * @return RequestInterface
     */
    public function auth($optionOrUsername, string $typeOrPassword = null, string $type = null): RequestInterface;

    /**
     * The body option is used to control the body of an entity enclosing request (e.g., PUT, POST, PATCH).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#body
     * @param mixed $body
     * @return RequestInterface
     */
    public function body($body): RequestInterface;

    /**
     * Set to a string to specify the path to a file containing a PEM formatted client side certificate.
     * If a password is required, then set to an array containing the path to the PEM file in the
     * first array element followed by the password required for the certificate in the second array element.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#cert
     * @param string|array $optionOrFile
     * @param string|null $password
     * @return RequestInterface
     */
    public function cert($optionOrFile, string $password = null): RequestInterface;

    /**
     * Float describing the number of seconds to wait while trying to connect to a server.
     * Use 0 to wait indefinitely (the default behavior).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#connect-timeout
     * @param float $seconds
     * @return RequestInterface
     */
    public function connectTimeout(float $seconds): RequestInterface;

    /**
     * Set to true or set to a PHP stream returned by fopen() to enable debug output with the handler used to send a request.
     * For example, when using cURL to transfer requests, cURL's verbose of CURLOPT_VERBOSE will be emitted.
     * When using the PHP stream wrapper, stream wrapper notifications will be emitted.
     * If set to true, the output is written to PHP's STDOUT.
     * If a PHP stream is provided, output is written to the stream.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#debug
     * @param bool $bool
     * @return RequestInterface
     */
    public function debug(bool $bool = true): RequestInterface;

    /**
     * Decode content
     * @param bool $bool
     * @return RequestInterface
     */
    public function decodeContent(bool $bool = true): RequestInterface;

    /**
     * Specify whether or not Content-Encoding responses (gzip, deflate, etc.) are automatically decoded.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#decode-content
     * @param float $delay
     * @return RequestInterface
     */
    public function delay(float $delay): RequestInterface;

    /**
     * Controls the behavior of the "Expect: 100-Continue" header.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#expect
     * @param int|bool $expect
     * @return RequestInterface
     */
    public function expect($expect): RequestInterface;

    /**
     * Set to "v4" if you want the HTTP handlers to use only ipv4 protocol or "v6" for ipv6 protocol.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#force-ip-resolve
     * @param string $version
     * @return RequestInterface
     */
    public function forceIPResolve(string $version): RequestInterface;

    /**
     * Used to send an application/x-www-form-urlencoded POST request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#form-params
     * @param array $params
     * @return RequestInterface
     */
    public function formParams(array $params): RequestInterface;

    /**
     * Associative array of headers to add to the request.
     * Each key is the name of a header, and each value is a string or array of strings representing the header field values.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#headers
     * @param string|array $headersOrKeyOrClosure
     * @param string|null $value
     * @return RequestInterface
     */
    public function header($headersOrKeyOrClosure, string $value = null): RequestInterface;

    /**
     * Set to false to disable throwing exceptions on an HTTP protocol errors (i.e., 4xx and 5xx responses).
     * Exceptions are thrown by default when HTTP protocol errors are encountered.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#http-errors
     * @param bool $bool
     * @return RequestInterface
     */
    public function httpErrors(bool $bool = true): RequestInterface;

    /**
     * Internationalized Domain Name (IDN) support (enabled by default if intl extension is available).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#idn-conversion
     * @param bool $bool
     * @return RequestInterface
     */
    public function idnConversion(bool $bool = true): RequestInterface;

    /**
     * The json option is used to easily upload JSON encoded data as the body of a request.
     * A Content-Type header of application/json will be added if no Content-Type header is already present on the message.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#json
     * @param string $json
     * @return RequestInterface
     */
    public function json(string $json): RequestInterface;

    /**
     * Sets the body of the request to a multipart/form-data form.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#multipart
     * @param array $data
     * @return RequestInterface
     */
    public function multipart(array $data): RequestInterface;

    /**
     * A callable that is invoked when the HTTP headers of the response have been received but the body has not yet begun to download.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#on-headers
     * @param callable $callback
     * @return RequestInterface
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
     * @return RequestInterface
     */
    public function onStats(callable $callback): RequestInterface;

    /**
     * Defines a function to invoke when transfer progress is made.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#progress
     * @param callable $callback
     * @return RequestInterface
     */
    public function progress(callable $callback): RequestInterface;

    /**
     * Pass a string to specify an HTTP proxy, or an array to specify different proxies for different protocols.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#proxy
     * @param string $url
     * @return RequestInterface
     */
    public function proxy(string $url): RequestInterface;

    /**
     * Associative array of query string values or query string to add to the request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#query
     * @param string|array $queriesOrName an array of queries or query name
     * @param string|null $queryValue
     * @return RequestInterface
     */
    public function query($queriesOrName, string $queryValue = null): RequestInterface;

    /**
     * Float describing the timeout to use when reading a streamed body
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#read-timeout
     * @param float $seconds
     * @return RequestInterface
     */
    public function readTimeout(float $seconds): RequestInterface;

    /**
     * Specify where the body of a response will be saved.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#sink
     * @param mixed $file string (path to file on disk), fopen() resource, Psr\Http\Message\StreamInterface
     * @return RequestInterface
     */
    public function sink($file): RequestInterface;

    /**
     * Specify where the body of a response will be saved.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#sink
     * @param StreamInterface $stream
     * @return RequestInterface
     */
    public function saveTo(StreamInterface $stream): RequestInterface;

    /**
     * Specify the path to a file containing a private SSL key in PEM format.
     * If a password is required, then set to an array containing the path to the
     * SSL key in the first array element followed by the password required for the certificate in the second element.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#ssl-key
     * @param string|array $fileOrPassword
     * @param null $password
     * @return RequestInterface
     */
    public function sslKey($fileOrPassword, $password = null): RequestInterface;

    /**
     * Set to true to stream a response rather than download it all up-front.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#stream
     * @param bool $bool
     * @return RequestInterface
     */
    public function stream(bool $bool = true): RequestInterface;

    /**
     * Set to true to inform HTTP handlers that you intend on waiting on the response.
     * This can be useful for optimizations.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#synchronous
     * @param bool $bool
     * @return RequestInterface
     */
    public function synchronous(bool $bool = true): RequestInterface;

    /**
     * Describes the SSL certificate verification behavior of a request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#verify
     * @param string|bool $verify
     * @return RequestInterface
     */
    public function verify($verify): RequestInterface;

    /**
     * Float describing the total timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior).
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#timeout
     * @param float $seconds
     * @return RequestInterface
     */
    public function timeout(float $seconds): RequestInterface;

    /**
     * Protocol version to use with the request.
     * @link https://docs.guzzlephp.org/en/stable/request-options.html#version
     * @param string $version
     * @return RequestInterface
     */
    public function version(string $version): RequestInterface;

    /**
     * Send http request with preferred referer url
     * @param string $refererUrl
     * @return RequestInterface
     */
    public function referer(string $refererUrl): RequestInterface;
}