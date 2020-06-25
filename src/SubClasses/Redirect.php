<?php
namespace Guzwrap\SubClasses;

class Redirect
{
    protected array $options = array();
    
    
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }
    
    
    public function max(int $value)
    {
        return $this->setOption('max', $value);
    }
    
    
    public function strict($bool=true)
    {
        return $this->setOption('strict', $bool);
    }
    
    
    public function referer($ref=true)
    {
        return $this->setOption('referer', $ref);
    }
    
    
    public function protocols(...$protocols)
    {
        return $this->setOption('protocols', $protocols);
    }
    
    
    public function onRedirect($callback)
    {
        return $this->setOption('on_redirect', $callback);
    }
    
    
    public function trackRedirect()
    {
        return $this->setOption('track_redrects', true);
    }
    
    
    public function getOptions()
    {
        if(empty($this->options)){
            return [];
        }
        return $this->options;
    }
}