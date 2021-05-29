<?php

namespace Guzwrap\Tests\Wrapper\Client;

use Guzwrap\Request;
use Guzwrap\Wrapper\Client\Concurrent;
use GuzzleHttp\Promise\PromiseInterface;
use PHPUnit\Framework\TestCase;

class ConcurrentTest extends TestCase
{
    public function testMethods(): void
    {
        $promise = Request::get('http://localhost:8002')->execAsync();
        $concurrent = new Concurrent([$promise]);

        self::assertIsArray($concurrent->unwrap());
        self::assertIsArray($concurrent->inspectAll());
        self::assertInstanceOf(PromiseInterface::class, $concurrent->all());
        self::assertInstanceOf(PromiseInterface::class, $concurrent->any());
        self::assertInstanceOf(PromiseInterface::class, $concurrent->settle());
        self::assertInstanceOf(PromiseInterface::class, $concurrent->some(1));

    }
}
