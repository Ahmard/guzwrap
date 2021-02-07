<?php
declare(strict_types=1);


namespace Guzwrap\Wrapper;


class StreamContext
{
    private array $values = [];

    /**
     * Add ssl stream context option
     * @param string $key
     * @param mixed $value
     * @return StreamContext
     */
    public function ssl(string $key, $value): StreamContext
    {
        $this->values['ssl'][$key] = $value;
        return $this;
    }

    /**
     * Add socket stream context option
     * @param string $key
     * @param mixed $value
     * @return StreamContext
     */
    public function socket(string $key, $value): StreamContext
    {
        $this->values['socket'][$key] = $value;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}