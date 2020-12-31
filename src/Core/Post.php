<?php

namespace Guzwrap\Core;

use InvalidArgumentException;

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
        if (is_array($name)) {
            $this->formParams['form_params'] = array_merge($this->formParams['form_params'], $name);
            return $this;
        }

        $this->formParams['form_params'][$name] = $value;
        return $this;
    }

    /**
     * Add file input
     * @param string|array|callable $fileOrKeyOrClosure
     * @param string|null $value
     * @return $this
     */
    public function file($fileOrKeyOrClosure, string $value = null): self
    {
        $this->hasFile = true;

        $options = [];
        switch (gettype($fileOrKeyOrClosure)) {
            case 'object':
                if (is_callable($fileOrKeyOrClosure)) {
                    $fileObj = new File();
                    $fileOrKeyOrClosure($fileObj);
                    $options = $fileObj->getOptions();
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid callable, array or string.");
                }
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