<?php
declare(strict_types = 1);

namespace Guzwrap\Wrapper;

use Guzwrap\RequestInterface;
use Guzwrap\UserAgent;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Guzzle implements RequestInterface
{
    //Import cookie handler
    use Cookie;

    //Import requests methods
    use RequestMethods;

    /**
     * Guzzle request options
     * @var array
     */
    protected array $values = array();
    protected array $oneTimedOption = array();
    /**
     * @var RequestInterface[]
     */
    protected array $requestsToBeUsed = [];
    private string $url;
    private string $requestType;

    /**
     * @inheritDoc
     * @return static
     */
    public function request(string $type, ...$argsOrClosure): Guzzle
    {
        if ($type == 'POST') {
            if (gettype($argsOrClosure[0]) == 'object') {
                $post = new Form();
                $argsOrClosure[0]($post);
                //let the $argsOrClosure hold the retrieved options
                $argsOrClosure = $post->getValues();
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
        $this->values = array_merge(
            $this->values,
            ($argsOrClosure ?? [])
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exec(): ResponseInterface
    {
        $options = $this->getRequestData();

        $options['headers'] ??= [];
        if (!array_key_exists('user-agent', $options['headers'])) {
            $options['headers']['user-agent'] = UserAgent::init()->getRandom();
        }

        //Create guzzle client
        $client = new Client($options);

        if (!$options['the_url']) {
            throw new InvalidArgumentException('You cannot send request without providing request url.');
        }

        return $client->request(
            $options['method'],
            $options['the_url'],
            $this->oneTimedOption
        );
    }

    /**
     * @inheritDoc
     */
    public function getRequestData(): array
    {
        //Merge used request data if any is used
        if (!empty($this->requestsToBeUsed)) {
            foreach ($this->requestsToBeUsed as $request) {
                $this->useRequestData($request->getRequestData());
            }
        }

        $options = array_merge(
            $this->values,
            $this->getCookieOptions()
        );

        if (isset($options[0])) {
            $options['the_url'] = $options[0];
        } elseif (isset($this->url)) {
            $options['the_url'] = $this->url;
        }
        //Set request method
        if (isset($this->requestType)) {
            $options['method'] = $this->requestType;
        }

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
    public function useRequestData(array $options): Guzzle
    {
        $this->values = array_merge_recursive($this->values, $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function useRequest(RequestInterface ...$request): Guzzle
    {
        $this->requestsToBeUsed = $request;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function url(string $url): Guzzle
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function form($callback): Guzzle
    {
        if (is_callable($callback)){
            $form = new Form();
            $callback($form);
            $values = $form->getValues();
        }

        if (!isset($values)){
            $values = $callback->getValues();
        }

        if (isset($values['url'])){
            $this->url($values['url']);
            unset($values['url']);
        }

        $this->values = array_merge($this->values, $values);
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function userAgent($userAgent, ?string $chosen = null): Guzzle
    {
        //If instance user agent is ued
        if ($userAgent instanceof UserAgent) {
            $userAgent = $userAgent->getRaw();
        }

        if (!$userAgent && ($chosen || strlen($userAgent) < 8)) {
            $userAgent = UserAgent::init()->get($userAgent, $chosen);
        }

        $this->addOption('headers', array_merge(
            $this->values['headers'] ??= [],
            ['user-agent' => $userAgent]
        ));
        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function addOption(string $name, $value): Guzzle
    {
        if (is_array($value)) {
            $value = array_merge(
                ($this->values[$name] ?? []),
                $value
            );
        }

        $this->values = array_merge(
            $this->values,
            [$name => $value]
        );

        return $this;
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function allowRedirects(bool $options = true): Guzzle
    {
        return $this->addOption('allow_redirects', $options);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function redirects(callable $callback): Guzzle
    {
        $redirectObject = new Redirect();
        $callback($redirectObject);
        $options = $redirectObject->getValues();
        return $this->addOption('allow_redirects', $options);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function auth($optionOrUsername, string $typeOrPassword = null, string $type = null): Guzzle
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
     * @return static
     */
    public function body($body): Guzzle
    {
        return $this->addOption('body', $body);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function cert($optionOrFilePath, string $password = null): Guzzle
    {
        $option = $optionOrFilePath;
        if (!is_array($optionOrFilePath)) {
            $option = array();
            $option[] = $optionOrFilePath;
            $option[] = $password;
        }

        return $this->addOption('cert', $option);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function connectTimeout(float $seconds): Guzzle
    {
        return $this->addOption('connect_timeout', $seconds);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function debug($boolOrStream = true): Guzzle
    {
        return $this->addOption('debug', $boolOrStream);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function decodeContent(bool $bool = true): Guzzle
    {
        return $this->addOption('decode_content', $bool);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function delay(float $delay): Guzzle
    {
        return $this->addOption('delay', $delay);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function expect($expect): Guzzle
    {
        return $this->addOption('expect', $expect);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function forceIPResolve(string $version): Guzzle
    {
        return $this->addOption('force_ip_resolve', $version);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function formParams(array $params): Guzzle
    {
        return $this->addOption('form_params', $params);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function httpErrors(bool $bool = true): Guzzle
    {
        return $this->addOption('http_errors', $bool);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function idnConversion(bool $bool = true): Guzzle
    {
        return $this->addOption('idn_conversion', $bool);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function json(string $json): Guzzle
    {
        return $this->addOption('json', $json);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function multipart(array $data): Guzzle
    {
        return $this->addOption('multipart', $data);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function onHeaders(callable $callback): Guzzle
    {
        return $this->addOption('on_headers', $callback);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function onStats(callable $callback): Guzzle
    {
        return $this->addOption('on_stats', $callback);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function onProgress(callable $callback): Guzzle
    {
        return $this->addOption('progress', $callback);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function proxy(string $url): Guzzle
    {
        return $this->addOption('proxy', $url);
    }

    /**
     * @inheritDoc
     * @return static
     * @return static
     */
    public function query($queriesOrName, string $queryValue = null): Guzzle
    {
        if (is_string($queriesOrName)) {
            return $this->addOption('query', [$queriesOrName => $queryValue]);
        }

        return $this->addOption('query', $queriesOrName);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function readTimeout(float $seconds): Guzzle
    {
        return $this->addOption('read_timeout', $seconds);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function sink($file): Guzzle
    {
        return $this->addOption('sink', $file);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function saveTo(StreamInterface $stream): Guzzle
    {
        return $this->addOption('save_to', $stream);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function sslKey($fileOrPassword, $password = null): Guzzle
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
     * @return static
     */
    public function stream(bool $bool = true): Guzzle
    {
        return $this->addOption('stream', $bool);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function synchronous(bool $bool = true): Guzzle
    {
        return $this->addOption('synchronous', $bool);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function verify($verify): Guzzle
    {
        return $this->addOption('verify', $verify);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function timeout(float $seconds): Guzzle
    {
        return $this->addOption('timeout', $seconds);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function version(string $version): Guzzle
    {
        return $this->addOption('version', $version);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function referer(string $refererUrl): Guzzle
    {
        return $this->header('Referer', $refererUrl);
    }

    /**
     * @inheritDoc
     * @return static
     */
    public function header($headersOrKeyOrClosure, string $value = null): Guzzle
    {
        $firstParamType = gettype($headersOrKeyOrClosure);

        switch ($firstParamType) {
            case 'object':
                if (is_callable($headersOrKeyOrClosure)) {
                    $headerObj = new Header();
                    $headersOrKeyOrClosure($headerObj);
                    $options = array_merge(($this->values['headers'] ?? []), $headerObj->getOptions());
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid callable, array or string.");
                }
                break;
            case 'array':
                $options = $headersOrKeyOrClosure;
                break;
            case 'string':
                $options[$headersOrKeyOrClosure] = $value;
                break;
            default:
                throw new InvalidArgumentException(
                    "First parameter must be an object of \Guzwrap\Core\Header or an array of headers or name of header
                ");
        }

        return $this->addOption('headers', $options);
    }
}