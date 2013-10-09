Attestation
===========

Simple sf2, angular app

Installation
------------

``` bash
$ bower install
$ chmod 777 app/cache app/logs
$ php composer.phar update
$ php app/console doctrine:schema:create
$ php app/console doctrine:schema:update
$ php app/console doctrine:fixtures:load
$ php app/console assetic:dump
```