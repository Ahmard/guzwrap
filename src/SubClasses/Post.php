<?php
namespace Guzwrap\SubClasses;

use Guzwrap\SubClasses\File;
use Guzwrap\SubClasses\Header;

class Post
{
    protected $options = array();
    
    protected $formParams = array();
    
    protected $hasFile = false;
    
    
    public function url($url)
    {
        $this->options['url'] = $url;
    }
    
    
    public function field($name, $value)
    {
        $this->formParams['form_params'] = [$name => $value];
        return $this;
    }
    
    
    public function file($fileOrKeyOrClosure, $value = null)
    {
        $this->hasFile = true;
        
        $firstParamType = gettype($fileOrKeyOrClosure);
        switch($firstParamType){
            case 'object':
                $fileObj = new File();
                $fileOrKeyOrClosure($fileObj);
                $options = $fileObj->getOptions();
                break;
            case 'array':
                $options = $fileOrKeyOrClosure;
                break;
            case 'string':
                $fileObj = new File();
                $fileObj->field($fileOrKeyOrClosure);
                $fileObj->path($value);
                $options = $fileObj->getOptions();
                break;
        }
        
        $this->options['multipart'][] = $options;
        
        return $this;
    }
    
    
    public function getOptions()
    {
        return array_merge(
            $this->formParams,
            $this->options
        );
    }
}