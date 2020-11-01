<?php

namespace Guzwrap;

use DirectoryIterator;

class UserAgent
{
    protected string $dirPath;

    protected $files = array();

    protected array $fileNames = array();


    public function __construct()
    {
        $this->dirPath = __DIR__ . '/data/ua/';
        //Find available user agents
        $this->files = new DirectoryIterator($this->dirPath);
    }


    public function getFiles(): DirectoryIterator
    {
        return $this->files;
    }


    public function getAvailable()
    {
        $names = array();
        foreach ($this->files as $file) {
            $name = trim(current(explode('.', $file->getFileName())));
            if ($name) $names[] = $name;
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
        if (strpos($chosen, '.')) {
            $expChosen = explode('.', $chosen);
            [$firstKey, $secondKey] = $expChosen;
        }

        $totalAgents = count($userAgents) - 1;

        //If no ua is chosen, get random
        $chosenKey = null;
        $chosenKey ??= $firstKey ?? rand(0, $totalAgents);
        //If chosen agent is greater than useragents list
        //We use last user agent
        if ($chosenKey > $totalAgents) {
            $chosenKey = $totalAgents;
        }

        //get ua
        $ua = $userAgents[$chosenKey];

        //If we got ourselves childs
        if (is_array($ua)) {
            $totalSubAgents = count($ua) - 1;
            $secondKey ??= rand(0, $totalSubAgents);
            //If chosen agent is greater than useragents list
            //We use last user agent
            if ($secondKey > $totalSubAgents) {
                $secondKey = $totalSubAgents;
            }
            $ua = $ua[$secondKey];
        }

        return $ua;
    }
}