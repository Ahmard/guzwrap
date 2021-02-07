<?php
declare(strict_types=1);


namespace Guzwrap\Wrapper\Client;


use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class Concurrent
{
    /**
     * @var array|PromiseInterface[]
     */
    protected array $promises;


    /**
     * Concurrent constructor.
     * @param array<PromiseInterface> $promises
     */
    public function __construct(array $promises)
    {
        $this->promises = $promises;
    }

    /**
     * Waits on all of the provided promises, but does not unwrap rejected
     * promises as thrown exception.
     *
     * Returns an array of inspection state arrays.
     *
     * @return array
     * @see \GuzzleHttp\Promise\Utils::inspect() for the inspection state array format.
     *
     */
    public function inspectAll(): array
    {
        return Utils::inspectAll($this->promises);
    }

    /**
     * Waits on all of the provided promises and returns the fulfilled values.
     *
     * Returns an array that contains the value of each promise (in the same
     * order the promises were provided). An exception is thrown if any of the
     * promises are rejected.
     *
     * @return array<ResponseInterface>
     *
     * @throws Exception on error
     * @throws Throwable on error in PHP >=7
     */
    public function unwrap(): array
    {
        return Utils::unwrap($this->promises);
    }

    /**
     * Given an array of promises, return a promise that is fulfilled when all
     * the items in the array are fulfilled.
     *
     * The promise's fulfillment value is an array with fulfillment values at
     * respective positions to the original array. If any promise in the array
     * rejects, the returned promise is rejected with the rejection reason.
     *
     * @param bool $recursive If true, resolves new promises that might have been added to the stack during its own resolution.
     *
     * @return PromiseInterface
     */
    public function all(bool $recursive = false): PromiseInterface
    {
        return Utils::all($this->promises, $recursive);
    }

    /**
     * Initiate a competitive race between multiple promises or values (values
     * will become immediately fulfilled promises).
     *
     * When count amount of promises have been fulfilled, the returned promise
     * is fulfilled with an array that contains the fulfillment values of the
     * winners in order of resolution.
     *
     * This promise is rejected with a {@see AggregateException} if the number
     * of fulfilled promises is less than the desired $count.
     *
     * @param int $count Total number of promises.
     *
     * @return PromiseInterface
     */
    public function some(int $count): PromiseInterface
    {
        return Utils::some($count, $this->promises);
    }

    /**
     * Like some(), with 1 as count. However, if the promise fulfills, the
     * fulfillment value is not an array of 1 but the value directly.
     *
     * @return PromiseInterface
     */
    public function any(): PromiseInterface
    {
        return Utils::any($this->promises);
    }

    /**
     * Returns a promise that is fulfilled when all of the provided promises have
     * been fulfilled or rejected.
     *
     * The returned promise is fulfilled with an array of inspection state arrays.
     *
     * @return PromiseInterface
     * @see inspect for the inspection state array format.
     *
     *
     */
    public function settle(): PromiseInterface
    {
        return Utils::settle($this->promises);
    }
}