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

Let's try sending request with cookies
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

Handle redirects
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