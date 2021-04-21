<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper;

use Closure;
use Guzwrap\RequestInterface;
use Guzwrap\UserAgent;
use Guzwrap\Wrapper\Client\Concurrent;
use Guzwrap\Wrapper\Client\Cookie;
use Guzwrap\Wrapper\Client\Factory;
use Guzwrap\Wrapper\Client\RequestMethods;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\PromisorInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class Guzzle - The main wrapper
 * @package Guzwrap\Wrapper
 */
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
    protected array $values = [
        'guzwrap' => [],
        'headers' => [],
    ];
    protected array $oneTimedOption = [];
    /**
     * @var RequestInterface[]
     */
    protected array $requestsToBeUsed = [];
    private string $uri;
    private string $requestMethod;
    private bool $isPoolUsed = false;


    /**
     * @inheritDoc
     */
    public function exec(): ResponseInterface
    {
        $preparedData = $this->prepareRequestData();
        $requestData = $preparedData['requestData'];
        //Create guzzle client
        $client = Factory::create($requestData);

        //Execute the request
        return $client->request(
            $requestData['method'],
            $requestData['guzwrap']['uri'],
            $preparedData['onceData']
        );
    }

    /**
     * Prepare guzwrap
     * @return array
     */
    protected function prepareRequestData(): array
    {
        $requestData = $this->getData();

        //Check if uri() method is used instead of get()
        if (!isset($requestData['method'])) {
            $requestData['method'] = 'GET';
        }

        //Verify request method against file upload
        if (isset($this->values['multipart']) && 'POST' != $requestData['method']) {
            throw new InvalidArgumentException("File cannot uploaded through {$requestData['method']} method, try changing request method to POST");
        }

        //Verify request user-agent
        if (!array_key_exists('user-agent', $requestData['headers'])) {
            $requestData['headers']['user-agent'] = UserAgent::init()->getRandom();
        }

        //Verify that request uri is provided
        if (!isset($requestData['guzwrap']['uri']) && !$this->isPoolUsed) {
            throw new InvalidArgumentException('You cannot send request without providing request uri.');
        }

        //Retrieve and unset one-timed-options
        $onceValues = $this->oneTimedOption;
        unset($this->oneTimedOption);

        return [
            'requestData' => $requestData,
            'onceData' => $onceValues
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        //Merge used request data if any is used
        if (!empty($this->requestsToBeUsed)) {
            foreach ($this->requestsToBeUsed as $request) {
                $this->useData($request->getData());
            }
        }

        $data = array_merge(
            $this->values,
            $this->getCookieOptions()
        );

        if (isset($data[0])) {
            $data['guzwrap']['uri'] = $data[0];
            unset($data[0]);
        } elseif (isset($this->uri)) {
            $data['guzwrap']['uri'] = $this->uri;
        }

        //Set request method
        if (isset($this->requestMethod)) {
            $data['method'] = $this->requestMethod;
        }

        /**
         * Let's check if the request has file
         * If there is file, we will merge form_params with multipart and unset form_params
         */
        $formParams = $data['form_params'] ?? null;

        if (isset($data['multipart']) && isset($formParams)) {
            $keys = array_keys($formParams);

            for ($i = 0; $i < count($formParams); $i++) {
                $data['multipart'][] = [
                    'name' => $keys[$i],
                    'contents' => $formParams[$keys[$i]]
                ];
            }

            unset($data['form_params'], $formParams);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function useData(array $options): Guzzle
    {
        $this->values = array_merge_recursive($this->values, $options);
        return $this;
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function request(string $method, $data, array $onceData = []): Guzzle
    {
        switch (true) {
            case is_callable($data):
                $form = new Form();
                $data($form);
                //let the $data hold the retrieved options
                $data = $form->getValues();
                //If uri from post method is used
                if (isset($data['action'])) {
                    $this->uri = $data['action'];
                }
                break;
            case ($data instanceof Form):
                $data = $data->getValues();
                break;
            case is_string($data):
                $this->uri($data);
                break;
            default:
                throw new InvalidArgumentException("Argument 2 passed to \\Guzwrap\\Wrapper\\Guzzle must be of type string, array, Closure or an instance of \\Guzwrap\\Wrapper\\Guzzle");
        }

        //lets check if its one timed options are provided
        $this->oneTimedOption = $onceData;

        $this->requestMethod = $method;

        if (is_array($data)) {
            $this->values = array_merge(
                $this->values,
                ($data ?? [])
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function uri(string $uri): Guzzle
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function baseUri($baseUri): Guzzle
    {
        $this->values['base_uri'] = $baseUri;
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
     */
    public function form($callback): Guzzle
    {
        if (is_callable($callback)) {
            $form = new Form();
            $callback($form);
            $values = $form->getValues();
        }

        if (!isset($values)) {
            $values = $callback->getValues();
        }

        if (isset($values['uri'])) {
            $this->uri($values['uri']);
            unset($values['uri']);
        }

        $this->values = array_merge($this->values, $values);
        return $this;
    }

    /**
     * @inheritDoc
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
     */
    public function allowRedirects(bool $allowRedirects = true): Guzzle
    {
        return $this->addOption('allow_redirects', $allowRedirects);
    }

    /**
     * @inheritDoc
     */
    public function redirects($callbackOrRedirect): Guzzle
    {
        if (is_callable($callbackOrRedirect)) {
            $redirectObject = new Redirect();
            $callbackOrRedirect($redirectObject);
            $options = $redirectObject->getValues();
        } elseif ($callbackOrRedirect instanceof Redirect) {
            $options = $callbackOrRedirect->getValues();
        } else {
            $class = __CLASS__;
            $method = __METHOD__;
            throw new InvalidArgumentException("{$class}::{$method}() only accept parameter of type Closure and \\Guzwrap\\Wrapper\\Redirect");
        }

        return $this->addOption('allow_redirects', $options);
    }

    /**
     * @inheritDoc
     */
    public function auth($optionOrUsername, ?string $typeOrPassword = null, ?string $type = null): Guzzle
    {
        $option = $optionOrUsername;
        if (!is_array($optionOrUsername)) {
            $option = array();
            $option[] = $optionOrUsername;
            $option[] = $typeOrPassword;

            if (null != $type) {
                $option[] = $type;
            }
        }

        return $this->addOption('auth', $option);
    }

    /**
     * @inheritDoc
     */
    public function body($body): Guzzle
    {
        return $this->addOption('body', $body);
    }

    /**
     * @inheritDoc
     */
    public function cert($optionOrFilePath, string $password = null): Guzzle
    {
        $option = $optionOrFilePath;
        if (!is_array($optionOrFilePath)) {
            if (null == $password) {
                $option = $optionOrFilePath;
            } else {
                $option = array();
                $option[] = $optionOrFilePath;
                $option[] = $password;
            }
        }

        return $this->addOption('cert', $option);
    }

    /**
     * @inheritDoc
     */
    public function connectTimeout(float $seconds): Guzzle
    {
        return $this->addOption('connect_timeout', $seconds);
    }

    /**
     * @inheritDoc
     */
    public function debug($boolOrStream = true): Guzzle
    {
        return $this->addOption('debug', $boolOrStream);
    }

    /**
     * @inheritDoc
     */
    public function decodeContent(bool $bool = true): Guzzle
    {
        return $this->addOption('decode_content', $bool);
    }

    /**
     * @inheritDoc
     */
    public function delay(float $delay): Guzzle
    {
        return $this->addOption('delay', $delay);
    }

    /**
     * @inheritDoc
     */
    public function expect($expect): Guzzle
    {
        return $this->addOption('expect', $expect);
    }

    /**
     * @inheritDoc
     */
    public function forceIPResolve(string $version): Guzzle
    {
        return $this->addOption('force_ip_resolve', $version);
    }

    /**
     * @inheritDoc
     */
    public function formParams(array $params): Guzzle
    {
        return $this->addOption('form_params', $params);
    }

    /**
     * @inheritDoc
     */
    public function httpErrors(bool $bool = true): Guzzle
    {
        return $this->addOption('http_errors', $bool);
    }

    /**
     * @inheritDoc
     */
    public function idnConversion(bool $bool = true): Guzzle
    {
        return $this->addOption('idn_conversion', $bool);
    }

    /**
     * @inheritDoc
     */
    public function json($json): Guzzle
    {
        if (!is_string($json)) {
            $json = json_encode($json, JSON_THROW_ON_ERROR);
        }

        return $this->addOption('json', $json);
    }

    /**
     * @inheritDoc
     */
    public function multipart(array $data): Guzzle
    {
        return $this->addOption('multipart', $data);
    }

    /**
     * @inheritDoc
     */
    public function onHeaders(Closure $callback): Guzzle
    {
        return $this->addOption('on_headers', $callback);
    }

    /**
     * @inheritDoc
     */
    public function onStats(Closure $callback): Guzzle
    {
        return $this->addOption('on_stats', $callback);
    }

    /**
     * @inheritDoc
     */
    public function onProgress(Closure $callback): Guzzle
    {
        return $this->addOption('progress', $callback);
    }

    /**
     * @inheritDoc
     */
    public function proxy(string $uri): Guzzle
    {
        return $this->addOption('proxy', $uri);
    }

    /**
     * @inheritDoc
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
     */
    public function readTimeout(float $seconds): Guzzle
    {
        return $this->addOption('read_timeout', $seconds);
    }

    /**
     * @inheritDoc
     */
    public function sink($file): Guzzle
    {
        return $this->addOption('sink', $file);
    }

    /**
     * @inheritDoc
     */
    public function saveTo(StreamInterface $stream): Guzzle
    {
        return $this->addOption('save_to', $stream);
    }

    /**
     * @inheritDoc
     */
    public function sslKey($fileOrPassword, $password = null): Guzzle
    {
        $option = $fileOrPassword;
        if (!is_array($fileOrPassword)) {
            if ($password != null) {
                $option = array();
                $option[] = $fileOrPassword;
                $option[] = $password;
            }
        }

        return $this->addOption('ssl_key', $option);
    }

    /**
     * @inheritDoc
     */
    public function stream(bool $bool = true): Guzzle
    {
        return $this->addOption('stream', $bool);
    }

    /**
     * @inheritDoc
     */
    public function synchronous(bool $bool = true): Guzzle
    {
        return $this->addOption('synchronous', $bool);
    }

    /**
     * @inheritDoc
     */
    public function verify($verify): Guzzle
    {
        return $this->addOption('verify', $verify);
    }

    /**
     * @inheritDoc
     */
    public function timeout(float $seconds): Guzzle
    {
        return $this->addOption('timeout', $seconds);
    }

    /**
     * @inheritDoc
     */
    public function version(string $version): Guzzle
    {
        return $this->addOption('version', $version);
    }

    /**
     * @inheritDoc
     */
    public function referer(string $refererUri): Guzzle
    {
        return $this->header('Referer', $refererUri);
    }

    /**
     * @inheritDoc
     */
    public function header($headersOrKeyOrClosure, string $value = null): Guzzle
    {
        $firstParamType = gettype($headersOrKeyOrClosure);

        switch ($firstParamType) {
            case 'object':
                if (is_callable($headersOrKeyOrClosure)) {
                    $headerObj = new Header();
                    $headersOrKeyOrClosure($headerObj);
                    $options = array_merge(($this->values['headers'] ?? []), $headerObj->getValues());
                } elseif ($headersOrKeyOrClosure instanceof Header) {
                    $options = $headersOrKeyOrClosure->getValues();
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("
                        First parameter of {$className}::{$methodName}() must be of type 
                        \Guzwrap\Wrapper\Header, Closure, array or string.
                    ");
                }
                break;
            case 'array':
                $options = $headersOrKeyOrClosure;
                break;
            case 'string':
                $options[$headersOrKeyOrClosure] = $value;
                break;
            default:
                $className = __CLASS__;
                $methodName = __METHOD__;
                throw new InvalidArgumentException("
                    First parameter of {$className}::{$methodName}() must be of type 
                    \Guzwrap\Wrapper\Header, Closure, array or string.
                ");
        }

        return $this->addOption('headers', $options);
    }

    /**
     * @inheritDoc
     */
    public function curlOption($option, $value): Guzzle
    {
        if (is_string($option)) {
            $option = [$option => $value];
        }

        return $this->addOption('curl', $option);
    }

    /**
     * @inheritDoc
     */
    public function streamContext($callbackOrStreamContext): Guzzle
    {
        if (is_array($callbackOrStreamContext)) {
            return $this->addOption('stream_context', $callbackOrStreamContext);
        } //If Closure is passed
        elseif (is_callable($callbackOrStreamContext)) {
            $streamContext = new StreamContext();
            $callbackOrStreamContext($streamContext);
            return $this->addOption('stream_context', $streamContext->getValues());
        } //If instance of StreamContext is passed
        elseif ($callbackOrStreamContext instanceof StreamContext) {
            return $this->addOption('stream_context', $callbackOrStreamContext->getValues());
        } else {
            $class = __CLASS__;
            $method = __METHOD__;
            throw new InvalidArgumentException("
                {$class}::{$method}() parameter must be of type array, Closure 
                or an instance of Guzwrap\Wrapper\StreamContext
            ");
        }
    }

    /**
     * @inheritDoc
     */
    public function middleware(Closure $callback): Guzzle
    {
        return $this->stack(function (HandlerStack $handlerStack) use ($callback) {
            $handlerStack->push($callback);
        });
    }

    /**
     * @inheritDoc
     */
    public function stack($callbackOrStack): Guzzle
    {
        if ($callbackOrStack instanceof Closure) {
            $stack = new HandlerStack();
            $callbackOrStack($stack);
            if (!$stack->hasHandler()) {
                $stack->setHandler(new CurlHandler());
            }
        } elseif ($callbackOrStack instanceof HandlerStack) {
            if (!$callbackOrStack->hasHandler()) {
                $callbackOrStack->setHandler(new CurlHandler());
            }
        }

        return $this->addOption('handler', $stack ?? $callbackOrStack);
    }

    /**
     * @inheritDoc
     * @throws GuzzleException
     */
    public function concurrent(...$requests): Concurrent
    {
        $promises = [];
        foreach ($requests as $name => $request) {
            if ($request instanceof Guzzle) {
                $promises[$name] = $request->execAsync();
            } else {
                $promises[$name] = $request;
            }
        }

        return new Concurrent($promises);
    }

    /**
     * @inheritDoc
     */
    public function execAsync(): PromiseInterface
    {
        $preparedData = $this->prepareRequestData();
        $requestData = $preparedData['requestData'];

        //Create guzzle client
        $client = Factory::create($requestData);

        //Execute the request
        return $client->requestAsync(
            $requestData['method'],
            $requestData['guzwrap']['uri'],
            $preparedData['onceData']
        );
    }

    /**
     * @inheritDoc
     */
    public function pool($callbackOrPool): PromisorInterface
    {
        $this->isPoolUsed = true;

        if ($callbackOrPool instanceof Pool) {
            $values = $callbackOrPool->getValues();
        } else {
            $pool = new Pool();
            $callbackOrPool($pool);
            $values = $pool->getValues();
        }

        $requestData = $this->prepareRequestData();
        $client = Factory::create($requestData);
        return new \GuzzleHttp\Pool($client, $values['requests']);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->getData();
    }
}