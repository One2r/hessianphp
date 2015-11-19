HessianPHP 2 is a library that implements the Hessian binary web services protocol for PHP 5.

**The Hessian Protocol**

> "The Hessian binary web service protocol makes web services usable without requiring a
> large framework, and without learning yet another alphabet soup of protocols. Because it
> is a binary protocol, it is well-suited to sending binary data without any need to
> extend the protocol with attachments." (from Caucho web site)

Hessian was created by Caucho Technology in the Java programming language. This protocol was designed to be fast and simple to learn and use. It uses HTTP as transport by sending and receiving POST requests to remote services.

HessianPHP 2 is a complete rewrite of the original HessianPHP library published a few years ago to make it compatible with the newest versions of the protocol and PHP.

## Features ##

  * PHP 5 only
  * Hessian protocol version 1 and 2 with autodetection
  * Supports PHP 5.3 in strict mode
  * Can create both clients and servers
  * Support CURL and standard http stream wrappers

# Quick Start #

## Requirements and Installation ##

  * PHP 5.1+
  * PHP enabled web server (Apache, IIS, etc.)
  * CURL extension enabled (optional)

Download the code and place it in a directory in your web server, that's it.

## Consuming a Hessian web service ##

To start consuming remote Hessian web services all you need to do is:

  1. Include or require the file HessianClient.php
  1. Create a HessianClient object passing the url of the service and additional options if required
  1. Call methods

This is an example code that creates a proxy to a remote service, calls several methods and prints the results:

```
include_once 'HessianClient.php';
$testurl = 'http://localhost/mathService.php';
$proxy = new HessianClient($testurl);
try{
    echo $proxy->div(2,5); 
} catch (Exception $ex){
   // ...handle error
}
```

## Creating a Hessian web service ##

You have to create a script in the web server following this steps:

  1. Include or require the file HessianService.php.
  1. Create a HessianService wrapper object
  1. Register a previously created or new object in the service constructur or by calling registerObject().
  1. Execute the handle() method.

Thus, for example, if we want to publish a calculator service that is compatible with the previous example, we have to create a class something like this:

```
class Math{    
  function add($n1,$n2) {        
    return $n1+$n2;    
  }    
  function sub($n1,$n2) {        
    return $n1-$n2;    
  }    
  function mul($n1,$n2) {        
    return $n1*$n2;    
  }    
  function div($n1,$n2) {        
    return $n1/$n2;    
  }
}
```

Then create the service wrapper and register a Math object to make it accesible through Hessian:

```
include_once 'HessianService.php';
$service = new HessianService(new Math());
$service->handle();
```

That's it. Now we have successfully published a Hessian web service from a common PHP object. The url of the service is the same url of the script.

If you try to access the url using a web browser, you will get a 500 error because Hessian requires POST to operate.

### Status ###

Version 2.0.3 is in Beta phase and is the default build. It's compatible with PHP 5.3. Deployment requires a little over 100KB. Unit tests implemented using SimpleTest which need to be downloaded separately.

Thanks to all people that has contributed with the whole set of Hessian implementations, we hope to help you get the best of the project.

HessianPHP is licensed under the MIT license, so you can comfortably use it in commercial applications.