<?php

namespace Guzwrap\Wrapper;

class Header
{
    protected array $values = array();

    public function add(string $name, string $value): self
    {
        $this->values[$name] = $value;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}