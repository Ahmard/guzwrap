Guzwrap, PHP GuzzleHttp Wrapper.
==============================================

Guzwrap is an object-oriented wrapper around [GuzzleHttp](http://guzzlephp.org/). <br/>
This project is founded to make sending request with Guzzle easier.

# Installation

Make sure you have [Composer](http://getcomposer.org) installed .

Now, let's install Guzwrap:

```bash
composer require ahmard/guzwrap
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

### More _Request_ usage

- Use request data

```php
use Guzwrap\Request;
use Guzwrap\UserAgent;

//Basic usage
$request = Request::query('artist', 'Taylor Swift')
    ->useRequestData([
        'headers' => [
            'pass' => 'my-random-pass',
            'user-agent' => 'My Custom Useragent',
        ],
        'query' => [
            'action' => 'create'
        ]       
    ])->exec();

//User other request's data
$request1 = Request::userAgent(UserAgent::FIREFOX)
    ->query([
        'username' => 'Ahmard',
        'realm' => 'admin'
    ]);

$realRequest = Request::useRequestData($request1->getRequestData());
```

- Use request object

```php
use Guzwrap\Request;
use Guzwrap\UserAgent;

$request1 = Request::query('username', 'Ahmard');

$request2 = Request::query('language', 'PHP')
    ->userAgent(UserAgent::CHROME)
    ->allowRedirects(false);

$realRequest = Request::useRequest($request1, $request2); //Has request 1 and request 2 data
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

- Use raw user agent<br/>
  Note that you can only pass Guzwrap\UserAgent class to the request object if you want use custom user agent, nothing
  more. <br/>
  This may open door to other possibilities in the future.

```php
use Guzwrap\UserAgent;
use Guzwrap\Request;

$request = Request::userAgent(UserAgent::raw('Browser 1.0 (Windows NT 10.0; Win64; x64)'));
```

**Enjoy 😊**