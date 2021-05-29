<?php


namespace Guzwrap\Tests;


use Guzwrap\Request;
use Guzwrap\UserAgent;
use Guzwrap\Wrapper\Form;
use Guzwrap\Wrapper\Guzzle;
use Guzwrap\Wrapper\Pool;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\TestCase;

class InterfaceMethodsTest extends TestCase
{
    public function testRequestMethods()
    {
        //GET
        $request = Request::get('localhost');
        $requestData = $request->getData();
        $this->assertSame($requestData['method'], 'GET');
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost');

        //POST
        $request = Request::head('localhost1');
        $requestData = $request->getData();
        $this->assertSame($requestData['method'], 'HEAD');
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost1');

        //PATCH
        $request = Request::patch('localhost2');
        $requestData = $request->getData();
        $this->assertSame($requestData['method'], 'PATCH');
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost2');

        //DELETE
        $request = Request::delete('localhost3');
        $requestData = $request->getData();
        $this->assertSame($requestData['method'], 'DELETE');
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost3');

        //CONNECT
        $request = Request::connect('localhost4');
        $requestData = $request->getData();
        $this->assertSame($requestData['method'], 'CONNECT');
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost4');

        //FORM
        $request = Request::form(function (Form $form) {
            $form->action('localhost5');
            $form->field('name', 'guzwrap');
        });
        $requestData = $request->getData();
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost5');
        $this->assertSame($requestData['method'], 'GET');
        $this->assertSame($requestData['query']['name'], 'guzwrap');

        //POST
        $request = Request::form(function (Form $form) {
            $form->action('localhost6');
            $form->method('POST');
            $form->file('php_file', __FILE__);
        });
        $requestData = $request->getData();
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost6');
        $this->assertSame($requestData['method'], 'POST');
        $this->assertSame($requestData['multipart'][0]['name'], 'php_file');
    }

    public function testGuzzleMethods()
    {
        $callback1 = fn() => 1;
        $callback2 = fn() => 2;
        $callback3 = fn() => 3;
        $callback4 = fn() => 4;
        $stream = new Stream(fopen(__FILE__, 'r'));
        $testRequest = Request::header(['third' => 3]);

        $request1 = new Guzzle();
        $request1->formParams([
            'name' => 'Guzwrap',
            'type' => 'lib'
        ]);
        $data1 = $request1->getData();

        $request = new Guzzle();
        $request->uri('localhost7');
        $request->referer('localhost6');
        $request->stream(true);
        $request->json(['key' => 'val']);
        $request->body(['data']);
        $request->sink('./sank.txt');
        $request->query('name', 'value');
        $request->query(['first' => 1]);
        $request->addOption('my-option-name', 'my-value');
        $request->auth('uname', 'pass');
        $request->allowRedirects(false);
        $request->cert('./certificate.cert');
        $request->connectTimeout(24.5);
        $request->debug(true);
        $request->decodeContent(false);
        $request->delay(23.5);
        $request->expect(100);
        $request->forceIPResolve('v6');
        $request->httpErrors(false);
        $request->idnConversion(false);
        $request->multipart([
            'one' => 'two',
            'three' => 'four'
        ]);
        $request->onHeaders($callback1);
        $request->onProgress($callback2);
        $request->onStats($callback3);
        $request->userAgent(UserAgent::raw('MY UA'));
        $request->readTimeout(43.1);
        $request->proxy('my.local.proxy');
        $request->saveTo($stream);
        $request->sslKey(__FILE__);
        $request->synchronous(false);
        $request->timeout(23.2);
        $request->useData([
            'second-option' => 'second-value'
        ]);
        $request->useRequest($testRequest);
        $request->verify(false);
        $request->version('1.1');
        $request->curlOption('CURLOPT_INTERFACE', 'xxx.xxx.xxx.xxx');
        $request->stack(function (HandlerStack $handlerStack){
            $handlerStack->setHandler(new CurlHandler());
        });
        $request->middleware($callback4);
        $request->baseUri('http://localhostx:9999');


        //Perform the test
        $data = $request->getData();
        //uri
        self::assertSame('localhost7', $data['guzwrap']['uri']);
        //referer
        self::assertSame('localhost6', $data['headers']['Referer']);
        //form_params
        self::assertSame([
            'name' => 'Guzwrap',
            'type' => 'lib'
        ], $data1['form_params']);
        self::assertTrue($data['stream']);
        self::assertSame(['key' => 'val'], $data['json']);
        self::assertSame(['data'], $data['body']);
        self::assertSame('./sank.txt', $data['sink']);
        self::assertSame([
            'name' => 'value',
            'first' => 1
        ], $data['query']);
        //RequestInterface::addOption()
        self::assertSame('my-value', $data['my-option-name']);
        //auth
        self::assertSame(['uname', 'pass'], $data['auth']);
        //allow_redirects [true/false]
        self::assertFalse($data['allow_redirects']);
        //cert
        self::assertSame('./certificate.cert', $data['cert']);
        //connect_timeout
        self::assertSame(24.5, $data['connect_timeout']);
        //debug
        self::assertTrue($data['debug']);
        //decode_content
        self::assertFalse($data['decode_content']);
        //delay
        self::assertSame(23.5, $data['delay']);
        //expect
        self::assertSame(100, $data['expect']);
        //force_ip_resolve
        self::assertSame('v6', $data['force_ip_resolve']);
        //http_errors
        self::assertFalse($data['http_errors']);
        //idn_conversion
        self::assertFalse($data['idn_conversion']);
        ////RequestInterface::multipart()
        self::assertSame([
            'one' => 'two',
            'three' => 'four'
        ], $data['multipart']);
        //headers
        self::assertSame($callback1, $data['on_headers']);
        //progress
        self::assertSame($callback2, $data['progress']);
        //on_stats
        self::assertSame($callback3, $data['on_stats']);
        //headers
        self::assertSame('MY UA', $data['headers']['user-agent']);
        //read_timeout
        self::assertSame(43.1, $data['read_timeout']);
        //proxy
        self::assertSame('my.local.proxy', $data['proxy']);
        //save_to
        self::assertSame($stream, $data['save_to']);
        //ssl_key
        self::assertSame(__FILE__, $data['ssl_key']);
        //synchronous
        self::assertFalse($data['synchronous']);
        //timeout
        self::assertSame(23.2, $data['timeout']);
        //RequestInterface::useData()
        self::assertSame('second-value', $data['second-option']);
        //headers
        self::assertSame(3, $data['headers']['third']);
        //verify
        self::assertFalse($data['verify']);
        //version
        self::assertSame('1.1', $data['version']);
        //curl
        self::assertSame([
            'CURLOPT_INTERFACE' => 'xxx.xxx.xxx.xxx',
        ], $request->getData()['curl']);
        //handler
        self::assertInstanceOf(HandlerStack::class, $request->getData()['handler']);
        self::assertSame('http://localhostx:9999', $data['base_uri']);
    }
}