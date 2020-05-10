<?php
use Guzwrap\SubClasses\Redirect;
use Guzwrap\SubClasses\Cookie;
use Guzwrap\SubClasses\RequestMethods;
use Guzwrap\TheWrapper;
use Guzwrap\Request;
require('src/SubClasses/Redirect.php');
require('src/SubClasses/Cookie.php');
require('src/SubClasses/RequestMethods.php');
require('src/TheWrapper.php');
require('src/Request.php');

/*
Request::withCookie()
    ->redirects(function($wrp){
        $wrp->max(5);
        $wrp->strict();
        $wrp->referer('http://goo.gl');
        $wrp->protocol('http');
        $wrp->trackRedirects();
        $wrp->onRedirect(function(){
            echo "Redirection detected!";
        });
    });
*/

$result = Request::get('https://google.com')
    ->auth('ahmard', '1234')
    ->body('hello')
    ->connectTimeout(2)
    ->debug()
    ->forceIPResolve('v4')
    ->exec();
var_dump($result);