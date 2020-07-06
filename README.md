Guzwrap, PHP GuzzleHttp Wrapper.
==============================================

Guzwrap is a wrapper that provides easy helper functions
around PHP popular web client library, GuzzleHttp.

# Installation

Make sure that you have composer installed
[Composer](http://getcomposer.org).

If you don't have Composer run the below command
```bash
curl -sS https://getcomposer.org/installer | php
```

Now, let's install Guzwrap:

```bash
composer require ahmard/guzwrap
```

After installing, require Composer's autoloader in your code:

```php
require 'vendor/autoload.php';
```

# Usage
```php
use Guzwrap\Request;

//simple request
$result = Request::get($url)->exec();

//with authentication
Request::get($url)
    ->auth('username', 'password')
    ->exec();
```

- get Guzwrap Instance
```php
$instance = Request::getInstance();
//Do something...
```

- Request with cookies
```php
Request::get($url)
    ->withCookie()
    //or use cookie file
    ->withCookieFile($fileLocatiom)
    //use cookie session
    ->withCookieSession($name)
    //use array too
    ->withCookieArray([
        'first_name' => 'Jane'
        'other_names' => 'Doe'
    ])->exec();
```

- Handle redirects
```php
Request::get($url)
    ->redirects(function($wrp){
        $wrp->max(5);
        $wrp->strict();
        $wrp->referer('http://goo.gl');
        $wrp->protocol('http');
        $wrp->trackRedirects();
        $wrp->onRedirect(function(){
            echo "Redirection detected!";
        });
    })->exec();
```

- Headers
```php
Request::get($url)->header(function($h){
    $h->add('hello', 'world');
    $h->add('planet', 'earth');
})->exec();
```

- Query
```php
Request::get('https://google.com')
    ->query('q', 'Who is jane doe')
    ->exec();
```

- Post form data
```php
Request::url($url)->post(function($req){
    $req->field('first_name', 'Jane');
    $req->field('last_name', 'Doe');
})->exec();
//Post with multipart data
Request::url($url)->post(function($req){
    $req->field('full_name', 'Jane Doe');
    $req->file('avatar', 'C:\jane_doe.jpg');
})->exec();
//Alter file data
Request::url($url)->post(function($req){
    $req->field('full_name', 'Jane Doe');
    $req->file(function(){
        $file->field('avatar');
        $file->path('C:\jane_doe.jpg');
        $file->name('John_doe.gif');
    });
})->exec();
```

- UserAgent
We provide custom useragents to help send request easily.
```php
Request::userAgent('chrome');
//Choose specific useragent index from array
Request::userAgent('chrome', '1');
//Choose sub-useragent
Request::userAgent('chrome', '9.1');
```

- List useragents
```php
use Guzwrap\UserAgent;
$userAgents = (new UserAgent())->getAvailable();
```