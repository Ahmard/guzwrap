<?php

namespace Guzwrap\Tests\Wrapper;

use Guzwrap\Wrapper\Redirect;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    public function testMethods()
    {
        $redirect = new Redirect();
        $closure = function (){};

        $redirect->max(5);
        $redirect->trackRedirect();
        $redirect->protocols('http', 'https');
        $redirect->referer();
        $redirect->setOption('name', 'value');
        $redirect->strict();
        $redirect->onRedirect($closure);

        $redirectData = $redirect->getValues();
        $this->assertSame(5, $redirectData['max']);
        $this->assertSame(true, $redirectData['track_redirects']);
        $this->assertSame(['http', 'https'], $redirectData['protocols']);
        $this->assertTrue($redirectData['referer']);
        $this->assertSame('value', $redirectData['name']);
        $this->assertTrue($redirectData['strict']);
        $this->assertSame($closure, $redirectData['on_redirect']);
    }
}
