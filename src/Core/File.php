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
     * Use file path insteand of resources
     * @param string $filePath
     * @return $this
     */
    public function path($filePath)
    {
        $this->filePath = $filePath;
        $this->formOptions['contents'] = fopen($filePath, 'r');
        return $this;
    }


    /**
     * Use file resource instead of path
     * @param $resource
     * @return $this
     */
    public function resource($resource)
    {
        $this->formOptions['contents'] = $resource;
        return $this;
    }


    public function name($filename)
    {
        $this->formOptions['filename'] = $filename;
        return $this;
    }


    /**
     * Set header
     * @param $headersOrKeyOrClosure
     * @param null $value
     * @return File
     */
    public function header($headersOrKeyOrClosure, $value = null)
    {
        $firstParamType = gettype($headersOrKeyOrClosure);

        switch ($firstParamType) {
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