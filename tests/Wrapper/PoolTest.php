<?php

namespace Guzwrap\Tests\Wrapper;

use Guzwrap\Wrapper\Pool;
use PHPUnit\Framework\TestCase;

class PoolTest extends TestCase
{
    public function testMethods(): void
    {
        $pool = new Pool();
        $callable1 = fn() => time();
        $callable2 = fn() => time();
        $callable3 = fn() => time();
        $pool->request($callable1);
        $pool->addOption('method', 'get');
        $pool->addOption([
            'query' => [
                'a' => 1,
                'b' => 2
            ]
        ]);
        $pool->concurrency(5);
        $pool->fulfilled($callable2);
        $pool->rejected($callable3);

        $values = $pool->getValues();
        self::assertSame($callable1, $values['request']);
        self::assertSame('get', $values['options']['method']);
        self::assertSame([
            'a' => 1,
            'b' => 2
        ], $values['options']['query']);
        self::assertSame(5, $values['concurrency']);
        self::assertSame($callable2, $values['fulfilled']);
        self::assertSame($callable3, $values['rejected']);
    }
}
