<?php

namespace Guzwrap;

use DirectoryIterator;
use InvalidArgumentException;
use SplFileInfo;

class UserAgent
{
    public const CHROME = 'chrome';
    public const EDGE = 'edge';
    public const FIREFOX = 'firefox';
    public const INTERNET_EXPLORER = 'ie';
    public const MOZILLA = 'mozilla';
    public const NETSCAPE = 'netscape';
    public const OPERA = 'opera';
    public const PHOENIX = 'phoenix';
    public const SAFARI = 'safari';

    protected array $directoryPaths = [];

    protected array $uaFiles = [];

    public function __construct()
    {
        array_push($this->directoryPaths, __DIR__ . '/data/ua/');
        $this->traverseDirs($this->directoryPaths);
    }

    protected function traverseDirs(array $directoryPaths): void
    {
        foreach ($directoryPaths as $directoryPath) {
            $dirIterator = new DirectoryIterator($directoryPath);
            foreach ($dirIterator as $fileInfo) {
                if ($fileInfo->isFile()) {
                    $this->addToUAList($fileInfo);
                }
            }
        }
    }

    protected function addToUAList(SplFileInfo $fileInfo): void
    {
        $this->uaFiles[$fileInfo->getFilename()] = [
            'path' => $fileInfo->getRealPath(),
        ];
    }

    public static function init(): UserAgent
    {
        return new UserAgent();
    }

    /**
     * Add user agent file to collection
     * @param string $filePath
     */
    public function addFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("UA file \"{$filePath}\" does not exists.");
        }

        $this->addToUAList(new SplFileInfo($filePath));
    }

    /**
     * Get list of loaded user agents
     * @return array
     */
    public function getLoaded(): array
    {
        return $this->uaFiles;
    }

    /**
     * Get names of loaded user agents
     * @return array
     */
    public function getAvailable(): array
    {
        $names = array();
        foreach ($this->uaFiles as $fileName => $file) {
            $names[] = substr($fileName, 0, -5);
        }

        return $names;
    }

    /**
     * Gets user agent from random file and random indexes
     * @return string
     */
    public function getRandom(): string
    {
        $randomUAName = array_rand($this->uaFiles);
        return $this->get(substr($randomUAName, 0, -5));
    }

    public function get(string $browser = 'chrome', ?string $chosen = null): string
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

    /**
     * Get browser useragent
     * @param string $browser
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getAgents(string $browser)
    {
        $browser .= '.json';
        if (!array_key_exists($browser, $this->uaFiles)) {
            throw new InvalidArgumentException("No loaded user agent with name \"{$browser}\"");
        }

        $jsonUA = file_get_contents($this->uaFiles[$browser]['path']);
        return json_decode((string)$jsonUA);
    }
}