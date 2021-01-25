<?php

use Guzwrap\UserAgent;
use Guzwrap\Wrapper\Form;
use Guzwrap\Request;

require 'vendor/autoload.php';

$response = Request::create()
    ->post(function (Form $post) {
        $post->action('http://localhost:8000');
        $post->field('name', 'Ahmard');
        $post->field('time', date('H:i:s'));
    })
    ->userAgent(UserAgent::OPERA)
    ->withCookie()
    //->debug()
    ->exec();
