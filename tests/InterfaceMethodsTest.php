<?php


namespace Guzwrap\Tests;


use Guzwrap\Request;
use Guzwrap\Wrapper\Form;
use Guzwrap\Wrapper\Guzzle;
use PHPUnit\Framework\TestCase;

class InterfaceMethodsTest extends TestCase
{
    public function testRequestMethods()
    {
        //GET
        $request = Request::get('localhost');
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['method'], 'GET');
        $this->assertSame($requestData['the_url'], 'localhost');

        //POST
        $request = Request::head('localhost1');
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['method'], 'HEAD');
        $this->assertSame($requestData['the_url'], 'localhost1');

        //PATCH
        $request = Request::patch('localhost2');
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['method'], 'PATCH');
        $this->assertSame($requestData['the_url'], 'localhost2');

        //DELETE
        $request = Request::delete('localhost3');
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['method'], 'DELETE');
        $this->assertSame($requestData['the_url'], 'localhost3');

        //CONNECT
        $request = Request::connect('localhost4');
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['method'], 'CONNECT');
        $this->assertSame($requestData['the_url'], 'localhost4');

        //FORM
        $request = Request::form(function (Form $form){
            $form->action('localhost5');
            $form->field('name', 'guzwrap');
        });
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['the_url'], 'localhost5');
        $this->assertSame($requestData['method'], 'GET');
        $this->assertSame($requestData['form_params']['name'], 'guzwrap');

        //POST
        $request = Request::form(function (Form $form){
            $form->action('localhost6');
            $form->method('POST');
            $form->file('php_file', __FILE__);
        });
        $requestData = $request->getRequestData();
        $this->assertSame($requestData['the_url'], 'localhost6');
        $this->assertSame($requestData['method'], 'POST');
        $this->assertSame($requestData['multipart'][0]['name'], 'php_file');
    }

    public function testGuzzleMethods()
    {
        $request = new Guzzle();
    }
}