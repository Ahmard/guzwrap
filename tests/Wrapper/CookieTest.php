<?php


namespace Guzwrap\Tests\Wrapper;


use Guzwrap\Wrapper\Client\Factory;
use Guzwrap\Wrapper\Guzzle;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;
use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase
{
    public function testMethods()
    {
        //RequestInterface::withCookie()
        $jar = new CookieJar();
        $request = new Guzzle();
        $request->withCookie($jar, false, []);
        self::assertSame($jar, $request->getData()['cookies']);

        //RequestInterface::withCookieArray()
        $request = new Guzzle();
        $request->withCookieArray([
            'name' => 'Guzwrap',
            'id' => 1
        ], 'localhost');
        self::assertInstanceOf(CookieJar::class, $request->getData()['cookies']);

        //RequestInterface::withCookieFile()
        $request = new Guzzle();
        $request->withCookieFile(__DIR__ . '/cookie-file');
        self::assertInstanceOf(FileCookieJar::class, $request->getData()['cookies']);

        //RequestInterface::withCookieSession()
        $request = new Guzzle();
        $request->withCookieSession('PHP_SES_ID', false);
        self::assertInstanceOf(SessionCookieJar::class, $request->getData()['cookies']);

        //RequestInterface::withSharedCookie()
        $request = new Guzzle();
        $request->withSharedCookie();
        $requestData = $request->getData();
        Factory::create($requestData);
        self::assertTrue($request->getData()['cookies']);

        //Test shared cookie with imported request data
        $request1 = new Guzzle();
        $request1->useData($requestData);
        $requestData1 = $request1->getData();
        Factory::create($requestData1);
        self::assertTrue($requestData1['guzwrap']['shared_cookie']);
     }
}