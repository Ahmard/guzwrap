<?php
namespace Guzwrap;

use DirectoryIterator;

class UserAgent
{
    protected $dirPath;
    
    protected $files = array();
    
    protected $fileNames = array();
    
    
    public function __construct()
    {
        $this->dirPath = __DIR__ . '/data/ua/';
        //Find available user agents
        $this->files = new DirectoryIterator($this->dirPath);
    }
    
    
    public function getFiles() : DirectoryIterator
    {
        return $this->files;
    }
    
    
    public function getAvailable()
    {
        $names = array();
        foreach ($this->files as $file){
            $name = trim(current(explode('.', $file->getFileName())));
            if($name) $names[] = $name;
        }
        
        return $names;
    }
    
    
    public function getFile($file)
    {
        return "{$this->dirPath}{$file}.json";
    }
    
    
    public function getAgents($browser)
    {
        $uaFile = $this->getFile($browser);
        $jsonUA = file_get_contents($uaFile);
        return json_decode($jsonUA);
    }
    
    
    public function get($browser = 'chrome', $chosen = null)
    {
        $userAgents = $this->getAgents($browser);
        
        $firstKey = $chosen;
        if(strpos($chosen, '.')){
            $expChosen = explode('.', $chosen);
            [$firstKey, $secondKey] = $expChosen;
        }
        
        //If no ua is chosen, get random
        $chosenKey ??= $firstKey ?? rand(0, (count($userAgents) - 1));
        
        //get ua
        $ua = $userAgents[$chosenKey];
        
        //If we got ourselves childs
        if(is_array($ua)){
            $secondKey ??= rand(0, (count($ua) - 1));
            $ua = $ua[$secondKey];
        }
        
        return $ua;
    }
}