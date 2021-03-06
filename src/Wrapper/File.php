<?php
declare(strict_types=1);

namespace Guzwrap\Wrapper;

use InvalidArgumentException;

class File
{
    protected array $values = array(
        'headers' => []
    );

    protected array $formValues = array();

    protected ?string $filePath = null;

    /**
     * Use file path instead of resources
     * @param string $filePath
     * @return $this
     */
    public function path(string $filePath): File
    {
        $this->formValues['file_path'] = $filePath;
        $this->formValues['contents'] = fopen($filePath, 'r');
        return $this;
    }

    /**
     * Use file resource instead of path
     * @param string $fieldName
     * @param resource $resource
     * @return $this
     */
    public function resource(string $fieldName, $resource): File
    {
        $this->field($fieldName);
        $this->formValues['contents'] = $resource;
        return $this;
    }

    /**
     * Input field name
     * @param string $name
     * @return $this
     */
    public function field(string $name): File
    {
        $this->formValues['name'] = $name;
        return $this;
    }

    /**
     * Preferred file name
     * @param string $filename
     * @return $this
     */
    public function name(string $filename): File
    {
        $this->formValues['filename'] = $filename;
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
                    $options = array_merge($this->values['headers'], $headerObj->getValues());
                } else {
                    $className = __CLASS__;
                    $methodName = __METHOD__;
                    throw new InvalidArgumentException("First parameter of {$className}::{$methodName}() must be valid Closure, array or string.");
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

        $this->values['headers'] = array_merge(
            $this->values['headers'],
            $options
        );

        return $this;
    }

    public function getValues(): array
    {
        return array_merge(
            $this->formValues,
            $this->values
        );
    }
}