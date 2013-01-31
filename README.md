Service Container
=================

[![Build Status](https://travis-ci.org/herrera-io/php-service-container.png)](http://travis-ci.org/herrera-io/php-service-container)

A simple service container.

Summary
-------

This library provides a simple service container. It is heavily influenced by Fabien Potencier's [Pimple](https://github.com/fabpot/Pimple) project (in particular, Igor Wielder's modifications). The differences from Pimple are

- naming convention
- handling of service provider registration
- library specific exceptions
- different implementations of `shared()` and `protect()`

Installation
------------

Add it to your list of Composer dependencies:

```sh
$ composer require herrera-io/service-container=1.*
```

Usage
-----

### Simple usage

```php
<?php

use Herrera\Service\Container;

$container = new Container(array('var' => 123));

$container['factory'] = $container->many(function () {
    return new ArrayObject(array('rand' => rand()));
});

$container['shared'] = $container->once(function() {
    return new ArrayObject(array('rand' => rand()));
});

echo $container['factory']['rand']; // echo "1197692050"
echo $container['factory']['rand']; // echo "995449132"
echo $container['shared']['rand']; // echo "89432412"
echo $container['shared']['rand']; // echo "89432412"
```

### Service provider usage

```php
<?php

use Herrera\Service\Container;
use Herrera\Service\ProviderInterface;

class MyProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container['hello'] = $container->once(function (Container $container) {
            echo 'Hello, ', $container['name'], "!\n";
        });
    }
}

$container =  new Container();
$container->register(new MyProvider(), array(
    'name' => 'Guest'
));

```
