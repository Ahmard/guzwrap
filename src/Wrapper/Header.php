<?php

namespace Guzwrap\Wrapper;

class Header
{
    protected array $options = array();

    public function add(string $name, string $value): self
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}