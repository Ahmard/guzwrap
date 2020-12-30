<?php

namespace Guzwrap\Core;

use Guzwrap\RequestInterface;
use Guzwrap\UserAgent;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GuzzleWrapper implements RequestInterface
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
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getRequestData(): array
    {
        $options = array_merge(
            $this->options,
            $this->getCookieOptions()
        );

        $options['the_url'] = $options[0] ?? $this->url;
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

        return $options;
    }

    /**
     * @inheritDoc
     */
    public function exec(): ResponseInterface
    {
        $options = $this->getRequestData();
        $client = new Client($options);

        return $client->request(
            $this->requestType,
            $options['the_url'],
            $this->oneTimedOption
        );
    }

    /**
     * @inheritDoc
     */
    public function url(string $url): GuzzleWrapper
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function allowRedirects(bool $options = true): GuzzleWrapper
    {
        return $this->addOption('allow_redirects', $options);
    }

    /**
     * @inheritDoc
     */
    public function redirects(callable $callback): GuzzleWrapper
    {
        $redirectObject = new Redirect();
        $callback($redirectObject);
        $options = $redirectObject->getOptions();
        return $this->addOption('allow_redirects', $options);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function body($body): GuzzleWrapper
    {
        return $this->addOption('body', $body);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function connectTimeout(float $seconds): GuzzleWrapper
    {
        return $this->addOption('connect_timeout', $seconds);
    }

    /**
     * @inheritDoc
     */
    public function debug(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('debug', $bool);
    }

    /**
     * @inheritDoc
     */
    public function decodeContent(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('decode_content', $bool);
    }

    /**
     * @inheritDoc
     */
    public function delay(float $delay): GuzzleWrapper
    {
        return $this->addOption('delay', $delay);
    }

    /**
     * @inheritDoc
     */
    public function expect($expect): GuzzleWrapper
    {
        return $this->addOption('expect', $expect);
    }

    /**
     * @inheritDoc
     */
    public function forceIPResolve(string $version): GuzzleWrapper
    {
        return $this->addOption('force_ip_resolve', $version);
    }

    /**
     * @inheritDoc
     */
    public function formParams(array $params): GuzzleWrapper
    {
        return $this->addOption('form_params', $params);
    }

    /**
     * @inheritDoc
     */
    public function header($headersOrKeyOrClosure, string $value = null): GuzzleWrapper
    {
        $firstParamType = gettype($headersOrKeyOrClosure);

        switch ($firstParamType) {
            case 'object':
                if (is_callable($headersOrKeyOrClosure)) {
                    $headerObj = new Header();
                    $headersOrKeyOrClosure($headerObj);
                    $options = array_merge(($this->options['headers'] ?? []), $headerObj->getOptions());
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new \InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid callable, array or string.");
                }
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
     * @inheritDoc
     */
    public function httpErrors(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('http_errors', $bool);
    }

    /**
     * @inheritDoc
     */
    public function idnConversion(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('idn_conversion', $bool);
    }

    /**
     * @inheritDoc
     */
    public function json(string $json): GuzzleWrapper
    {
        return $this->addOption('json', $json);
    }

    /**
     * @inheritDoc
     */
    public function multipart(array $data): GuzzleWrapper
    {
        return $this->addOption('multipart', $data);
    }

    /**
     * @inheritDoc
     */
    public function onHeaders(callable $callback): GuzzleWrapper
    {
        return $this->addOption('on_headers', $callback);
    }

    /**
     * @inheritDoc
     */
    public function onStats(callable $callback): GuzzleWrapper
    {
        return $this->addOption('on_stats', $callback);
    }

    /**
     * @inheritDoc
     */
    public function progress(callable $callback): GuzzleWrapper
    {
        return $this->addOption('progress', $callback);
    }

    /**
     * @inheritDoc
     */
    public function proxy(string $url): GuzzleWrapper
    {
        return $this->addOption('proxy', $url);
    }

    /**
     * @inheritDoc
     */
    public function query($queriesOrName, string $queryValue = null): GuzzleWrapper
    {
        if (is_string($queriesOrName)) {
            return $this->addOption('query', [$queriesOrName => $queryValue]);
        }

        return $this->addOption('query', $queriesOrName);
    }

    /**
     * @inheritDoc
     */
    public function readTimeout(float $seconds): GuzzleWrapper
    {
        return $this->addOption('read_timeout', $seconds);
    }

    /**
     * @inheritDoc
     */
    public function sink($file): GuzzleWrapper
    {
        return $this->addOption('sink', $file);
    }

    /**
     * @inheritDoc
     */
    public function saveTo(StreamInterface $stream): GuzzleWrapper
    {
        return $this->addOption('save_to', $stream);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function stream(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('stream', $bool);
    }

    /**
     * @inheritDoc
     */
    public function synchronous(bool $bool = true): GuzzleWrapper
    {
        return $this->addOption('synchronous', $bool);
    }

    /**
     * @inheritDoc
     */
    public function verify($verify): GuzzleWrapper
    {
        return $this->addOption('verify', $verify);
    }

    /**
     * @inheritDoc
     */
    public function timeout(float $seconds): GuzzleWrapper
    {
        return $this->addOption('timeout', $seconds);
    }

    /**
     * @inheritDoc
     */
    public function version(string $version): GuzzleWrapper
    {
        return $this->addOption('version', $version);
    }

    /**
     * @inheritDoc
     */
    public function referer(string $refererUrl): GuzzleWrapper
    {
        return $this->header('Referer', $refererUrl);
    }
}