<?php

namespace Guzwrap\Core;

class Header
{
    protected array $options = array();

    public function add($name, $value): self
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}