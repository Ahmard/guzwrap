<?php

namespace Guzwrap\Classes;

class Post
{
    protected array $options = array();

    protected array $formParams = array();

    protected bool $hasFile = false;


    public function url($url): self
    {
        $this->options['url'] = $url;
        return $this;
    }


    public function field($name, $value): self
    {
        $this->formParams['form_params'] = [$name => $value];
        return $this;
    }


    public function file($fileOrKeyOrClosure, $value = null): self
    {
        $this->hasFile = true;

        $firstParamType = gettype($fileOrKeyOrClosure);
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


    public function getOptions(): array
    {
        return array_merge(
            $this->formParams,
            $this->options
        );
    }
}