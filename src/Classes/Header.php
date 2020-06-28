<?php
namespace Guzwrap\Classes;

class Header
{
    protected $options = array();
    
    
    public function add($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }
    
    
    public function getOptions()
    {
        return $this->options;
    }
}