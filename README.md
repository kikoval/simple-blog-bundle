SimpleBlogBundle
================

A Symphony 2 bundle that adds simple blog-like feature. Used to demostrate how to make a REST-like API.

## Add composer dependecies

1. Add it do `composer.json` file:

```
{
    ...,
    "require": {
        ...,
        "kikoval/simple-blog-bundle": "dev-master"
    }
}
```

2. Update the dependency:

``` bash
$ php composer.phar update kikoval/simple-blog-bundle
```

or update all dependencies

``` bash
$ php composer.phar update
```

## Enable the bundle

```
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new SimpleBlogBundle\SimpleBlogBundle(),
        );

        // ...
    }
}
```
