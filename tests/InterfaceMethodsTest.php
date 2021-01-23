<?php


namespace Guzwrap\Tests;


use Guzwrap\Request;
use Guzwrap\UserAgent;
use Guzwrap\Wrapper\Form;
use Guzwrap\Wrapper\Guzzle;
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
        $request = Request::form(function (Form $form){
            $form->action('localhost5');
            $form->field('name', 'guzwrap');
        });
        $requestData = $request->getData();
        $this->assertSame($requestData['guzwrap']['uri'], 'localhost5');
        $this->assertSame($requestData['method'], 'GET');
        $this->assertSame($requestData['query']['name'], 'guzwrap');

        //POST
        $request = Request::form(function (Form $form){
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
        $request->json('{key: val}');
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


        //Perform the test
        $data = $request->getData();
        self::assertSame('localhost7', $data['guzwrap']['uri']);
        self::assertSame('localhost6', $data['headers']['Referer']);
        self::assertSame([
            'name' => 'Guzwrap',
            'type' => 'lib'
        ], $data1['form_params']);
        self::assertTrue($data['stream']);
        self::assertSame('{key: val}', $data['json']);
        self::assertSame(['data'], $data['body']);
        self::assertSame('./sank.txt', $data['sink']);
        self::assertSame([
            'name' => 'value',
            'first' => 1
        ], $data['query']);
        self::assertSame('my-value', $data['my-option-name']);
        self::assertSame(['uname', 'pass'], $data['auth']);
        self::assertFalse($data['allow_redirects']);
        self::assertSame('./certificate.cert', $data['cert']);
        self::assertSame(24.5, $data['connect_timeout']);
        self::assertTrue($data['debug']);
        self::assertFalse($data['decode_content']);
        self::assertSame(23.5, $data['delay']);
        self::assertSame(100, $data['expect']);
        self::assertSame('v6', $data['force_ip_resolve']);
        self::assertFalse($data['http_errors']);
        self::assertFalse($data['idn_conversion']);
        self::assertSame([
            'one' => 'two',
            'three' => 'four'
        ], $data['multipart']);
        self::assertSame($callback1, $data['on_headers']);
        self::assertSame($callback2, $data['progress']);
        self::assertSame($callback3, $data['on_stats']);
        self::assertSame('MY UA', $data['headers']['user-agent']);
        self::assertSame(43.1, $data['read_timeout']);
        self::assertSame('my.local.proxy', $data['proxy']);
        self::assertSame($stream, $data['save_to']);
        self::assertSame(__FILE__, $data['ssl_key']);
        self::assertFalse($data['synchronous']);
        self::assertSame(23.2, $data['timeout']);
        self::assertSame('second-value', $data['second-option']);
        self::assertSame(3, $data['headers']['third']);
        self::assertFalse($data['verify']);
        self::assertSame('1.1', $data['version']);

        //echo json_encode($request->getData());
    }
}