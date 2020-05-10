<?php
namespace Guzwrap;

use Guzwrap\SubClasses\Redirect;
use Guzwrap\SubClasses\Cookie;
use Guzwrap\SubClasses\RequestMethods;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Client;

class TheWrapper
{
    //use Cookie triat
    use Cookie;
    //use RequestMethods trait
    use RequestMethods;
    
    
    /**
     * Guzzle request options
     * @var array
     */
    public $options = array();
    
    
    
    public function addOption($name, $value)
    {
        $this->options = array_merge(
            $this->options, 
            [$name => $value]
        );
        return $this;
    }
    
    
    public function request($type, $url, $options=array())
    {
        $this->requestType = $type;
        $this->url = $url;
        $this->options = array_merge(
            $this->options, 
            $options
        );
        
        return $this;
    }
    
    
    /**
     * Execute the request
     * @param void
     * @return GuzzleHttp\Client
     */
    public function exec()
    {
        $client = new Client();
        return $client->request($this->requestType, $this->url, $this->options);
    }
    
    
    /**
     * Describes the redirect behavior of a request.
     * @param mixed $options
     * @return Guzwrap\Wrapper
     */
    public function allowRedirects($options=true)
    {
        return $this->addOption('allow_redirects', $options);
    }
    
    
    public function redirects($callback)
    {
        $redirObject = new Redirect();
        $callback($redirObject);
        $options = $redirObject->getOptions();
        return $this->addOption('allow_redirects', $options);
    }
    
    
    public function auth($optionOrUsername, $typeOrPassword=null, $type=null)
    {
        $option = $optionOrUsername;
        if(! is_array($optionOrUsername)){
            $option = array();
            $option[] = $optionOrUsername;
            $option[] = $typeOrPassword;
            
            if($type != null){
                $option[] = $typeOrPassword;
            }
        }
        
        return $this->addOption('auth', $option);
    }
    
    
    public function body($body)
    {
        return $this->addOption('body', $body);
    }
    
    
    public function cert($optionOrFile, $password=null)
    {
        $option = $optionOrFile;
        if(! is_array($optionOrFile)){
            $option = array();
            $option[] = $optionOrFile;
            $option[] = $password;
        }
        
        return $this->addOption('cert', $option);
    }
    
    
    public function connectTimeout(float $seconds)
    {
        return $this->addOption('connect_timeout', $seconds);
    }
    
    
    public function debug($bool=true)
    {
        return $this->addOption('debug', $bool);
    }
    
    
    public function decodeContent($bool=true)
    {
        return $this->addOption('decode_content', $bool);
    }
    
    
    public function delay(float $delay)
    {
        return $this->addOption('delay', $delay);
    }
    
    
    public function expect($expect)
    {
        return $this->addOption('expect', $expect);
    }
    
    
    public function forceIPResolve(string $version)
    {
        return $this->addOption('force_ip_resolve', $version);
    }
    
    
    public function formParams(array $params)
    {
        return $this->addOption('form_params', $params);
    }
    
    
    public function headers(array $headerLists)
    {
        return $this->addOption('headers', $headerLists);
    }
    
    
    public function httpErrors($bool=true)
    {
        return $this->addOption('http_errors', $bool);
    }
    
    
    public function idnConversion($bool=true)
    {
        return $this->addOption('idn_conversion', $bool);
    }
    
    
    public function json(string $json)
    {
        return $this->addOption('json', $json);
    }
    
    
    public function multipart(array $data)
    {
        return $this->addOption('multipart', $data);
    }
    
    
    public function onHeaders(callable $callback)
    {
        return $this->addOption('on_headers', $callback);
    }
    
    
    public function onStats(callable $callback)
    {
        return $this->addOption('on_stats', $callback);
    }
    
    
    public function progress(callable $callback)
    {
        return $this->addOption('progress', $progress);
    }
    
    
    public function proxy(string $url)
    {
        return $this->addOption('proxy', $url);
    }
    
    
    public function query($queries)
    {
        return $this->addOption('query', $queries);
    }
    
    
    public function readTimeout(float $seconds)
    {
        return $this->addOption('read_timeout', $seconds);
    }
    
    
    public function sink($file)
    {
        return $this->addOption('sink', $file);
    }
    
    
    public function saveTo(StreamInterface $stream)
    {
        return $this->addOption('save_to', $stream);
    }
    
    
    public function sslKey(string $fileOrPassword, $password=null)
    {
        $option = array();
        if(! is_array($fileOrPassword)){
            $option[] = $fileOrPassword;
            if($password != null){
                $option[] = $password;
            }
        }
        return $this->addOption('ssl_key', $option);
    }
    
    
    public function stream($bool=true)
    {
        return $this->addOption('stream', $bool);
    }
    
    
    public function synchronous($bool=true)
    {
        return $this->addOption('synchronous', $bool);
    }
    
    
    public function verify($verify)
    {
        return $this->addOption('verify', $verify);
    }
    
    
    public function timeout(float $seconds)
    {
        return $this->addOption('timeout', $seconds);
    }
    
    
    public function version($version)
    {
        return $this->addOption('version', $version);
    }
    
 }