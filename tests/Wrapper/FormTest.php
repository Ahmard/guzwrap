<?php

namespace Guzwrap\Tests\Wrapper;

use Guzwrap\Wrapper\Form;
use Guzwrap\Wrapper\Guzzle;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testMethods()
    {
        $request = new Guzzle();
        $request->form(function (Form $form) {
            $form->action('localhost');
            $form->method('POST');
            $form->input('input', 'value1');
            $form->field('field', 'value2');
            $form->file('file', __DIR__ . '/cookie-file');
        });

        $data = $request->getData();
        //action
        self::assertSame('localhost', $data['guzwrap']['uri']);
        //method
        self::assertSame('POST', $data['method']);
        //file
        self::assertSame([
            'name' => 'file',
            'file_path' => __DIR__ . '/cookie-file',
            'contents' => $data['multipart'][0]['contents'], //resource
            'headers' => []
        ], $data['multipart'][0]);
        //first input
        self::assertSame([
            'name' => 'input',
            'contents' => 'value1'
        ], $data['multipart'][1]);
        //second input
        self::assertSame([
            'name' => 'field',
            'contents' => 'value2'
        ], $data['multipart'][2]);
    }

    public function testFormWithFormObject()
    {
        $request = new Guzzle();
        $form = new Form();
        $form->method('post');
        $form->input('name', 'guzwrap');
        $request->form($form);
        self::assertSame('guzwrap', $request->getData()['form_params']['name']);
    }

    public function testFormWithoutMethod()
    {
        $request = new Guzzle();
        $form = new Form();
        $form->input('name', 'guzwrap');
        $request->form($form);
        self::assertSame('guzwrap', $request->getData()['query']['name']);
    }
}
