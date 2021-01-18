<?php

use Guzwrap\Wrapper\Guzzle;

class Tester extends Guzzle
{
    public function getTime(): int
    {
        return time();
    }

    public function getObj(): Tester
    {
        return $this;
    }
}

$tester = new Tester();
$tester->get('')->getObj()->head('');