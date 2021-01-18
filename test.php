<?php

use Guzwrap\Request;
use Guzwrap\Wrapper\Form;

require 'vendor/autoload.php';

$response = Request::form(function (Form $form){
    $form->action('localhost:8002/post.php');
    $form->method('post');
    $form->field('name', 'Ahmard');
    $file = new \Guzwrap\Wrapper\File();
    $file->field('file');
    $file->path('composer.json');
    $form->input($file);
    $form->field([
        'time' => time(),
        'name' => uniqid()
    ]);
})->exec();

var_dump($response->getBody()->getContents());