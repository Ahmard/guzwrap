<?php

namespace Guzwrap\Tests\Wrapper;

use Guzwrap\Wrapper\Guzzle;
use Guzwrap\Wrapper\StreamContext;
use PHPUnit\Framework\TestCase;

class StreamContextTest extends TestCase
{
    public function testMethods()
    {
        $request = new Guzzle();
        $request->streamContext(function (StreamContext $context) {
            $context->ssl('allow_self_signed', true);
            $context->socket('bindto', 'xxx.xxx.xxx.xxx');
        });

        self::assertSame([
            'ssl' => [
                'allow_self_signed' => true
            ],
            'socket' => [
                'bindto' => 'xxx.xxx.xxx.xxx'
            ]
        ], $request->getData()['stream_context']);
    }
}
