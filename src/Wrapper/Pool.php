<?php
declare(strict_types=1);


namespace Guzwrap\Wrapper;


use Closure;
use GuzzleHttp\Psr7\Request;

class Pool
{
    protected array $values = [
        'options' => [],
    ];


    /**
     * Set number of concurrent requests
     * @param int $numberOfConcurrences number of concurrent requests
     * @return $this
     */
    public function concurrency(int $numberOfConcurrences): Pool
    {
        $this->values['concurrency'] = $numberOfConcurrences;
        return $this;
    }

    /**
     * A callback to be called for delivered each successful response
     * @param Closure $closure
     * @return $this
     */
    public function fulfilled(Closure $closure): Pool
    {
        $this->values['fulfilled'] = $closure;
        return $this;
    }

    /**
     * A callback to be called for delivered each failed request
     * @param Closure $closure
     * @return $this
     */
    public function rejected(Closure $closure): Pool
    {
        $this->values['rejected'] = $closure;
        return $this;
    }

    /**
     * Add request to the pool
     * @param Request|Closure $request
     * @return $this
     */
    public function requests($request): Pool
    {
        $this->values['requests'] = $request;
        return $this;
    }

    /**
     * Add guzzle request option to each request
     * @param string|array $nameOrOptions
     * @param mixed|null $optionValue
     * @return $this
     */
    public function addOption($nameOrOptions, $optionValue = null): Pool
    {
        if (is_string($nameOrOptions)) {
            $nameOrOptions = [$nameOrOptions => $optionValue];
        }

        $this->values['options'] = array_merge(
            $this->values['options'],
            $nameOrOptions,
        );

        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}