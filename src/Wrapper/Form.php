<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper;


use InvalidArgumentException;

class Form
{
    protected array $values = [
        'method' => 'GET',
        'form_params' => [],
    ];

    /**
     * Form action uri
     * @param string $uri
     * @return $this
     */
    public function action(string $uri): Form
    {
        $this->values['uri'] = $uri;
        return $this;
    }

    /**
     * Form method
     * @param string $method
     * @return $this
     */
    public function method(string $method): Form
    {
        $this->values['method'] = strtoupper($method);
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
            $this->values['form_params'] = array_merge($this->values['form_params'], $name);
            return $this;
        }

        if ($name instanceof File) {
            $this->values['multipart'][] = $name->getValues();
            return $this;
        }

        $this->values['form_params'][$name] = $value;
        return $this;
    }

    /**
     * Add file input
     * @param string|array|File|callable $fieldName File field name, array of file data retrieved from \Guzwrap\Wrapper\File,
     * An object of \Guzwrap\Wrapper\File or callable
     * @param string|resource|null $filePath
     * @return $this
     */
    public function file($fieldName, $filePath = null): self
    {
        $values = [];
        $dataType = gettype($fieldName);
        switch ($dataType) {
            case 'object':
                if (is_callable($fieldName)) {
                    $file = new File();
                    $fieldName($file);
                    $values = $file->getValues();
                } elseif ($fieldName instanceof File) {
                    $values = $fieldName->getValues();
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid callable, array or string.");
                }
                break;
            case 'array':
                $values = $fieldName;
                break;
            case 'string':
                if (is_resource($filePath)) {
                    $file = new File();
                    $file->name($fieldName);
                    $file->resource($fieldName, $filePath);
                    $values = $file->getValues();
                } else {
                    $file = new File();
                    $file->field($fieldName);
                    $file->path($filePath);
                    $values = $file->getValues();
                }
                break;
            default:
                throw new InvalidArgumentException("Field name parameter can only accept value of type string, array and object.");
        }

        $this->values['multipart'][] = $values;

        return $this;
    }

    /**
     * Get defined data
     * @return array
     */
    public function getValues(): array
    {
        if ('POST' != $this->values['method']) {
            $this->values['query'] = $this->values['form_params'];
            //Since its not POST request, we won't be needing form parameters
            unset($this->values['form_params']);
        }

        return $this->values;
    }
}