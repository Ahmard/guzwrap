<?php


namespace Guzwrap\Tests\Wrapper;


class File extends \Guzwrap\Wrapper\File
{
    public function path(string $filePath): \Guzwrap\Wrapper\File
    {
        $this->formValues['file_path'] = $filePath;
        return \Guzwrap\Wrapper\File::path($filePath);
    }
}