<?php

namespace Guzwrap\Tests\Wrapper;

use Guzwrap\Wrapper\Header;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testMethods()
    {
        $file = new File();
        $file->field('source');
        $file->path(__FILE__);
        $file->name('test_file');
        $file->header(function (Header $header) {
            $header->add('realm', 'Admin');
            $header->add('pass', 'none');
        });

        $fileData = $file->getValues();
        $this->assertSame('source', $fileData['name']);
        $this->assertSame(__FILE__, $fileData['file_path']);
        $this->assertSame('test_file', $fileData['filename']);
        //Headers
        $this->assertSame([
            'realm' => 'Admin',
            'pass' => 'none'
        ], $fileData['headers']);
    }
}
