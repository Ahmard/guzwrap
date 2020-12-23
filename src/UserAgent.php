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

    public function getAvailable(): array
    {
        $names = array();
        foreach ($this->files as $file) {
            $name = trim(current(explode('.', $file->getFileName())));
            if ($name) $names[] = $name;
        }

        return $names;
    }

    public function getFile(string $file): string
    {
        return "{$this->dirPath}{$file}.json";
    }

    /**
     * Get browser useragent
     * @param string $browser
     * @return mixed
     */
    public function getAgents(string $browser)
    {
        $uaFile = $this->getFile($browser);
        $jsonUA = file_get_contents($uaFile);
        return json_decode($jsonUA);
    }

    public function get(string $browser = 'chrome', string $chosen = null): string
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
        //If chosen agent is greater than useragent list
        //We use last user agent
        if ($chosenKey > $totalAgents) {
            $chosenKey = $totalAgents;
        }

        //get ua
        $ua = $userAgents[$chosenKey];

        //If we got ourselves a children
        if (is_array($ua)) {
            $totalSubAgents = count($ua) - 1;
            $secondKey ??= rand(0, $totalSubAgents);
            //If chosen agent is greater than useragent list
            //We use last user agent
            if ($secondKey > $totalSubAgents) {
                $secondKey = $totalSubAgents;
            }
            $ua = $ua[$secondKey];
        }

        return $ua;
    }
}