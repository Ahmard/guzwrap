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
        return $this->options('max', $value);
    }
    
    
    public function strict($bool=true)
    {
        return $this->options('strict', $bool);
    }
    
    
    public function referer($ref=true)
    {
        return $this->options('referer', $ref);
    }
    
    
    public function protocols(...$protocols)
    {
        return $this->options('protocols', $protocols);
    }
    
    
    public function onRedirect($callback)
    {
        return $this->options('on_redirect', $callback);
    }
    
    
    public function trackRedirect()
    {
        return $this->options('track_redrects', true);
    }
    
    
    protected function getOptions()
    {
        return [
            'allow_redirects' => $this->options
        ];
    }
}