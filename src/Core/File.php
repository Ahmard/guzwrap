<?php

namespace Guzwrap\Core;

class File
{
    protected array $options = array(
        'headers' => []
    );

    protected array $formOptions = array();

    protected ?string $filePath = null;


    public function field($name)
    {
        $this->formOptions['name'] = $name;
        return $this;
    }


    /**
     * Use file path instead of resources
     * @param string $filePath
     * @return $this
     */
    public function path(string $filePath): File
    {
        $this->filePath = $filePath;
        $this->formOptions['contents'] = fopen($filePath, 'r');
        return $this;
    }


    /**
     * Use file resource instead of path
     * @param resource $resource
     * @return $this
     */
    public function resource($resource): File
    {
        $this->formOptions['contents'] = $resource;
        return $this;
    }


    public function name(string $filename): File
    {
        $this->formOptions['filename'] = $filename;
        return $this;
    }


    /**
     * Set header
     * @param string|array $headersOrKeyOrClosure
     * @param string|null $value
     * @return File
     */
    public function header($headersOrKeyOrClosure, string $value = null): File
    {
        switch (gettype($headersOrKeyOrClosure)) {
            case 'object':
                $headerObj = new Header();
                $headersOrKeyOrClosure($headerObj);
                $options = array_merge($this->options['headers'], $headerObj->getOptions());
                break;
            case 'array':
                $options = $headersOrKeyOrClosure;
                break;
            case 'string':
                $options[$headersOrKeyOrClosure] = $value;
                break;
            default:
                throw new \InvalidArgumentException(
                    "First parameter must be an object of \Guzwrap\Core\Header or an array of headers or name of header
                ");
        }

        $this->options['headers'] = array_merge(
            $this->options['headers'],
            $options
        );

        return $this;
    }


    public function getOptions(): array
    {
        return array_merge(
            $this->formOptions,
            $this->options
        );
    }
}