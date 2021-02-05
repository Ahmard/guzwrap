<?php

use Guzwrap\Request;
use Guzwrap\UserAgent;
use Guzwrap\Wrapper\Form;

require 'vendor/autoload.php';

$class = new class {
    public function __invoke(Form $form)
    {
        $form->action('http://farm365.test');
        $form->field('name', 'Ahmard');
        $form->field('time', date('H:i:s'));
    }
};

$response = Request::create()
    ->post($class)
    ->userAgent(UserAgent::OPERA)
    ->withCookie()
    //->debug()
    ->exec();
;

var_dump($response->getBody()->getContents());
/*
Request::concurrent(...[Request::create()])
    ->settle();
*/


$c = fn() => time();
var_dump($c instanceof Closure);
var_dump(is_callable($c));
function t(callable $d): void
{
    echo $d();
}

t($c);