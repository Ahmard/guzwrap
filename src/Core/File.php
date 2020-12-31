<?php

namespace Guzwrap\Core;

use InvalidArgumentException;

class File
{
    protected array $options = array(
        'headers' => []
    );

    protected array $formOptions = array();

    protected ?string $filePath = null;

    /**
     * Input field name
     * @param string $name
     * @return $this
     */
    public function field(string $name): File
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

    /**
     * Preferred file name
     * @param string $filename
     * @return $this
     */
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
                if (is_callable($headersOrKeyOrClosure)) {
                    $headerObj = new Header();
                    $headersOrKeyOrClosure($headerObj);
                    $options = array_merge($this->options['headers'], $headerObj->getOptions());
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid callable, array or string.");
                }
                break;
            case 'array':
                $options = $headersOrKeyOrClosure;
                break;
            case 'string':
                $options[$headersOrKeyOrClosure] = $value;
                break;
            default:
                throw new InvalidArgumentException(
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