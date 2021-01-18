<?php
declare(strict_types = 1);

namespace Guzwrap\Wrapper;


use InvalidArgumentException;

class Form
{
    protected array $values = array();
    protected array $formParams = ['form_params' => []];

    /**
     * Form action url
     * @param string $url
     * @return $this
     */
    public function action(string $url): Form
    {
        $this->values['url'] = $url;
        return $this;
    }

    /**
     * Form method
     * @param string $method
     * @return $this
     */
    public function method(string $method): Form
    {
        $this->values['method'] = $method;
        return $this;
    }

    /**
     * @param string|File $name
     * @param string|null $value
     * @return Form
     */
    public function input($name, ?string $value = null): Form
    {
        return $this->field($name, $value);
    }

    /**
     * Add input filed
     * @param mixed $name name of input or multidimensional array of ['name' => 'value'].
     * You can alternatively pass Guzwrap\Wrapper\File object to this parameter too
     * @param string|null $value
     * @return $this
     */
    public function field($name, ?string $value = null): Form
    {
        if (is_array($name)) {
            $this->formParams['form_params'] = array_merge($this->formParams['form_params'], $name);
            return $this;
        }

        if ($name instanceof File){
            $this->values['multipart'][] = $name->getValues();
            return $this;
        }

        $this->formParams['form_params'][$name] = $value;
        return $this;
    }

    /**
     * Add file input
     * @param string|array|File|callable $filePathOrValue
     * @param string|null $value
     * @return $this
     */
    public function file($filePathOrValue, string $value = null): self
    {
        $options = [];
        switch (gettype($filePathOrValue)) {
            case 'object':
                if (is_callable($filePathOrValue)) {
                    $fileObj = new File();
                    $filePathOrValue($fileObj);
                    $options = $fileObj->getValues();
                }elseif ($filePathOrValue instanceof File){
                    $options = $filePathOrValue->getValues();
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid callable, array or string.");
                }
                break;
            case 'array':
                $options = $filePathOrValue;
                break;
            case 'string':
                $fileObj = new File();
                $fileObj->field($filePathOrValue);
                $fileObj->path($value);
                $options = $fileObj->getValues();
                break;
        }

        $this->values['multipart'][] = $options;

        return $this;
    }

    /**
     * Get defined data
     * @return array
     */
    public function getValues(): array
    {
        return array_merge(
            $this->formParams,
            $this->values
        );
    }
}