Guzwrap, PHP GuzzleHttp Wrapper.
==============================================

Guzwrap is a wrapper that provides easy helper functions around PHP popular web client library, GuzzleHttp.

# Installation

Make sure that you have [Composer](http://getcomposer.org) installed .

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
$result = Request::get('http://localhost:8002')->exec();

//with authentication
Request::get('http://localhost:8002')
    ->auth('username', 'password')
    ->exec();
```

- get Guzwrap Instance

```php
use Guzwrap\Request;

$instance = Request::getInstance();
//Do something...
```

- Request with cookies

```php
use Guzwrap\Request;

Request::get('http://localhost:8002')
    ->withCookie()
    //or use cookie file
    ->withCookieFile('path/to/file')
    //use cookie session
    ->withCookieSession('session_name')
    //use array too
    ->withCookieArray([
        'first_name' => 'Jane',
        'other_names' => 'Doe'
    ], 'localhost')
    ->exec();
```

- Handle redirects

```php
use Guzwrap\Request;

Request::get('http://localhost:8002')
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
use Guzwrap\Request;

Request::get('http://localhost:8002')
    ->header(function($h){
        $h->add('hello', 'world');
        $h->add('planet', 'earth');
    })
    ->exec();
```

- Query

```php
use Guzwrap\Request;

Request::get('https://google.com')
    ->query('q', 'Who is jane doe')
    ->exec();
```

- Post form data

```php
use Guzwrap\Core\File;
use Guzwrap\Request;

Request::url('http://localhost:8002')
    ->post(function($req){
        $req->field('first_name', 'Jane');
        $req->field('last_name', 'Doe');
    })
    ->exec();

//Post with multipart data
Request::url('http://localhost:8002')->post(function($req){
    $req->field('full_name', 'Jane Doe');
    $req->file('avatar', 'C:\jane_doe.jpg');
})->exec();

//Send file with custom information
Request::url('http://localhost:8002')->post(function($req){
    $req->field('full_name', 'Jane Doe');
    $req->file(function(File $file){
        $file->field('avatar');
        $file->path('C:\jane_doe.jpg');
        $file->name('John_doe.gif');
    });
})->exec();
```

### UserAgent 
  We provide custom user agents to help send request easily.

```php
use Guzwrap\Request;
use Guzwrap\UserAgent;

Request::userAgent(UserAgent::CHROME);

//Choose specific useragent index from array
Request::userAgent(UserAgent::CHROME, '1');

//Choose sub-useragent
Request::userAgent(UserAgent::CHROME, '9.1');
```

- List user agents

```php
use Guzwrap\UserAgent;

$userAgents = UserAgent::init()->getAvailable();
```

- Get random user agent
```php
use Guzwrap\UserAgent;

$randomUA = UserAgent::init()->getRandom();
```

- Add user agents to the collection. <br/>
  Please take a look at user agent [definition sample](/src/data/ua/chrome.json)
```php
use Guzwrap\UserAgent;

UserAgent::init()->addFile('/path/to/user-agents.json');
```