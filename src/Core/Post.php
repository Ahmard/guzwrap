<?php

namespace Guzwrap\Core;

class Post
{
    protected array $options = array();

    protected array $formParams = array();

    protected bool $hasFile = false;

    /**
     * Set form action
     * @param string $url a http uri to post form to
     * @return $this
     */
    public function url(string $url): self
    {
        $this->options['url'] = $url;
        return $this;
    }

    /**
     * Add input filed
     * @param string|array $name name of inout or multidimensional array of ['name' => 'value']
     * @param string|null $value
     * @return $this
     */
    public function field($name, string $value = null): self
    {
        if (is_array($name)){
            $this->formParams['form_params'] = array_merge($this->formParams['form_params'], $name);
            return $this;
        }

        $this->formParams['form_params'][$name] = $value;
        return $this;
    }

    /**
     * Add file input
     * @param $fileOrKeyOrClosure
     * @param null $value
     * @return $this
     */
    public function file($fileOrKeyOrClosure, $value = null): self
    {
        $this->hasFile = true;

        $firstParamType = gettype($fileOrKeyOrClosure);
        $options = [];
        switch ($firstParamType) {
            case 'object':
                $fileObj = new File();
                $fileOrKeyOrClosure($fileObj);
                $options = $fileObj->getOptions();
                break;
            case 'array':
                $options = $fileOrKeyOrClosure;
                break;
            case 'string':
                $fileObj = new File();
                $fileObj->field($fileOrKeyOrClosure);
                $fileObj->path($value);
                $options = $fileObj->getOptions();
                break;
        }

        $this->options['multipart'][] = $options;

        return $this;
    }

    /**
     * Get defined data
     * @return array
     */
    public function getOptions(): array
    {
        return array_merge(
            $this->formParams,
            $this->options
        );
    }
}