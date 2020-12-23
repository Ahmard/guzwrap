<?php

namespace Guzwrap\Core;

use Guzwrap\UserAgent;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

class GuzzleWrapper
{
    //Import cookie handler
    use Cookie;

    //Import requests methods
    use RequestMethods;

    private string $url;

    private string $requestType;

    /**
     * Guzzle request options
     * @var array
     */
    protected array $options = array();

    protected array $oneTimedOption = array();

    /**
     * Add option to this request
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addOption(string $name, $value): GuzzleWrapper
    {
        if (is_array($value)) {
            $value = array_merge(
                ($this->options[$name] ?? []),
                $value
            );
        }

        $this->options = array_merge(
            $this->options,
            [$name => $value]
        );

        return $this;
    }

    /**
     * Make http request
     * @param string $type
     * @param mixed ...$argsOrClosure
     * @return $this
     */
    public function request(string $type, ...$argsOrClosure): GuzzleWrapper
    {
        if ($type == 'POST') {
            if (gettype($argsOrClosure[0]) == 'object') {
                $post = new Post();
                $argsOrClosure[0]($post);
                //let the $argsOrClosure hold the retrieved options
                $argsOrClosure = $post->getOptions();
                //If url from post method is used
                if (isset($argsOrClosure['url'])) {
                    $this->url = $argsOrClosure['url'];
                }
            }
        }

        //lets check if its one timed options are provided
        if (isset($argsOrClosure[1])) {
            $this->oneTimedOption = $argsOrClosure[1];
        }

        $this->requestType = $type;
        $this->options = array_merge(
            $this->options,
            ($argsOrClosure ?? [])
        );

        return $this;
    }


    /**
     * Execute the request
     * @return ResponseInterface
     * @throws Throwable
     */
    public function exec(): ResponseInterface
    {
        $options = array_merge(
            $this->options,
            $this->getCookieOptions()
        );

        $url = $options[0] ?? $this->url;
        // unset($options[0]);

        /**
         * Let's check if the request has file
         * If there is file, we will merge form_params with multipart
         */
        $formParams = $options['form_params'] ?? null;

        if (isset($options['multipart']) && isset($formParams)) {
            $keys = array_keys($formParams);

            for ($i = 0; $i < count($formParams); $i++) {
                $options['multipart'][] = [
                    'name' => $keys[$i],
                    'contents' => $formParams[$keys[$i]]
                ];
            }

            unset($options['form_params'], $formParams);
        }

        $client = new Client($options);
        //dd($options);
        return $client->request($this->requestType, $url, $this->oneTimedOption);
    }

    /**
     * Set request url
     * @param string $url
     * @return $this
     */
    public function url(string $url): GuzzleWrapper
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Choose user agent
     * @param string $userAgent
     * @param string|null $chosen
     * @return $this
     */
    public function userAgent(string $userAgent, string $chosen = null): GuzzleWrapper
    {
        if ($chosen || strlen($userAgent) < 8) {
            $userAgent = (new UserAgent)->get($userAgent, $chosen);
        }
        $this->addOption('headers', array_merge(
            $this->options['headers'],
            ['user-agent' => $userAgent]
        ));
        return $this;
    }


    /**
     * Describes the redirect behavior of a request.
     * @param bool $options
     * @return GuzzleWrapper
     */
    public function allowRedirects(bool $options = true): GuzzleWrapper
    {
        return $this->addOption('allow_redirects', $options);
    }

    /**
     * Set redirect handler
     * @param callable $callback
     * @return $this
     */
    public function redirects(callable $callback): GuzzleWrapper
    {
        $redirectObject = new Redirect();
        $callback($redirectObject);
        $options = $redirectObject->getOptions();
        return $this->addOption('allow_redirects', $options);
    }

    /**
     * Set request authentication credentials
     * @param string|array $optionOrUsername
     * @param string|null $typeOrPassword
     * @param string|null $type
     * @return $this
     */
    public function auth($optionOrUsername, string $typeOrPassword = null, string $type = null): GuzzleWrapper
    {
        $option = $optionOrUsername;
        if (!is_array($optionOrUsername)) {
            $option = array();
            $option[] = $optionOrUsername;
            $option[] = $typeOrPassword;

            if ($type != null) {
                $option[] = $typeOrPassword;
            }
        }

        return $this->addOption('auth', $option);
    }

    /**
     * Set request body
     * @param mixed $body
     * @return $this
     */
    public function body($body): GuzzleWrapper
    {
        return $this->addOption('body', $body);
    }

    /**
     * Set certificate
     * @param string|array $optionOrFile
     * @param string|null $password
     * @return $this
     */
    public function cert($optionOrFile, string $password = null): GuzzleWrapper
    {
        $option = $optionOrFile;
        if (!is_array($optionOrFile)) {
            $option = array();
            $option[] = $optionOrFile;
            $option[] = $password;
        }

        return $this->addOption('cert', $option);
    }

    /**
     * Set connection timeout
     * @param float $seconds
     * @return $this
     */
    public function connectTimeout(float $seconds): GuzzleWrapper
    {
        return $this->addOption('connect_timeout', $seconds);
    }

    /**
     * Whether to display debug information
     * @param bool $bool
     * @return $this
     */
    public function debug(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('debug', $bool);
    }

    /**
     * Decode content
     * @param bool $bool
     * @return $this
     */
    public function decodeContent(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('decode_content', $bool);
    }

    /**
     * Set delay to a request
     * @param float $delay
     * @return $this
     */
    public function delay(float $delay): GuzzleWrapper
    {
        return $this->addOption('delay', $delay);
    }

    /**
     * Controls the behavior of the "Expect: 100-Continue" header.
     * @param int|bool $expect
     * @return $this
     */
    public function expect($expect): GuzzleWrapper
    {
        return $this->addOption('expect', $expect);
    }

    /**
     * Force to resolve ip address
     * @param string $version
     * @return $this
     */
    public function forceIPResolve(string $version): GuzzleWrapper
    {
        return $this->addOption('force_ip_resolve', $version);
    }

    /**
     * Set request form parameters
     * @param array $params
     * @return $this
     */
    public function formParams(array $params): GuzzleWrapper
    {
        return $this->addOption('form_params', $params);
    }

    /**
     * Set request headers
     * @param string|array $headersOrKeyOrClosure
     * @param string|null $value
     * @return $this
     */
    public function header($headersOrKeyOrClosure, string $value = null): GuzzleWrapper
    {
        $firstParamType = gettype($headersOrKeyOrClosure);

        switch ($firstParamType) {
            case 'object':
                $headerObj = new Header();
                $headersOrKeyOrClosure($headerObj);
                $options = array_merge(($this->options['headers'] ?? []), $headerObj->getOptions());
                break;
            case 'array':
                $options = $headersOrKeyOrClosure;
                break;
            case 'string':
                $options[$headersOrKeyOrClosure] = $value;
                break;
            default:
                throw new \InvalidArgumentException(
                    "First parameter must be an object of \Guzwrap\Core\Header or an array of headers or name of header
                ");
        }

        return $this->addOption('headers', $options);
    }

    /**
     * Request http errors
     * @param bool $bool
     * @return $this
     */
    public function httpErrors(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('http_errors', $bool);
    }

    /**
     * IDN Conversion
     * @param bool $bool
     * @return $this
     */
    public function idnConversion(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('idn_conversion', $bool);
    }

    /**
     * Mark request's content-type as json
     * @param string $json
     * @return $this
     */
    public function json(string $json): GuzzleWrapper
    {
        return $this->addOption('json', $json);
    }

    /**
     * Set request as multipart
     * @param array $data
     * @return $this
     */
    public function multipart(array $data): GuzzleWrapper
    {
        return $this->addOption('multipart', $data);
    }

    /**
     * Listen to headers event
     * @param callable $callback
     * @return $this
     */
    public function onHeaders(callable $callback): GuzzleWrapper
    {
        return $this->addOption('on_headers', $callback);
    }

    /**
     * Listen to stats event
     * @param callable $callback
     * @return $this
     */
    public function onStats(callable $callback): GuzzleWrapper
    {
        return $this->addOption('on_stats', $callback);
    }

    /**
     * Monitor request progress
     * @param callable $callback
     * @return $this
     */
    public function progress(callable $callback): GuzzleWrapper
    {
        return $this->addOption('progress', $callback);
    }

    /**
     * Set request proxy url
     * @param string $url
     * @return $this
     */
    public function proxy(string $url): GuzzleWrapper
    {
        return $this->addOption('proxy', $url);
    }

    /**
     * Url queries
     * @param string|array $queriesOrName an array of queries or query name
     * @param string|null $queryValue
     * @return $this
     */
    public function query($queriesOrName, string $queryValue = null): GuzzleWrapper
    {
        if (is_string($queriesOrName)) {
            return $this->addOption('query', [$queriesOrName => $queryValue]);
        }

        return $this->addOption('query', $queriesOrName);
    }

    /**
     * Set read timeout
     * @param float $seconds
     * @return $this
     */
    public function readTimeout(float $seconds): GuzzleWrapper
    {
        return $this->addOption('read_timeout', $seconds);
    }

    /**
     * Save request response body to file
     * @param mixed $file string (path to file on disk), fopen() resource, Psr\Http\Message\StreamInterface
     * @return $this
     */
    public function sink($file): GuzzleWrapper
    {
        return $this->addOption('sink', $file);
    }

    /**
     * Save request response body to file
     * @param StreamInterface $stream
     * @return $this
     */
    public function saveTo(StreamInterface $stream): GuzzleWrapper
    {
        return $this->addOption('save_to', $stream);
    }

    /**
     * Provide ssl key for this request
     * @param string|array $fileOrPassword
     * @param null $password
     * @return $this
     */
    public function sslKey($fileOrPassword, $password = null): GuzzleWrapper
    {
        $option = array();
        if (!is_array($fileOrPassword)) {
            $option[] = $fileOrPassword;
            if ($password != null) {
                $option[] = $password;
            }
        }
        return $this->addOption('ssl_key', $option);
    }

    /**
     * Whether to stream this request
     * @param bool $bool
     * @return $this
     */
    public function stream(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('stream', $bool);
    }

    /**
     * Whether the request should be asynchronous
     * @param bool $bool
     * @return $this
     */
    public function synchronous(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('synchronous', $bool);
    }

    /**
     * Describes the SSL certificate verification behavior of a request.
     * @param string|bool $verify
     * @return $this
     */
    public function verify($verify): GuzzleWrapper
    {
        return $this->addOption('verify', $verify);
    }

    /**
     * Set request timeout
     * @param float $seconds
     * @return $this
     */
    public function timeout(float $seconds): GuzzleWrapper
    {
        return $this->addOption('timeout', $seconds);
    }

    /**
     * Set request version
     * @param string $version
     * @return $this
     */
    public function version(string $version): GuzzleWrapper
    {
        return $this->addOption('version', $version);
    }

    /**
     * Send http request with preferred referer url
     * @param string $refererUrl
     * @return $this
     */
    public function referer(string $refererUrl): GuzzleWrapper
    {
        return $this->header('Referer', $refererUrl);
    }
}