Attestation
===========

Описание
--------
Пример приложения, написанного на symfony2 с использованием angularjs

Установка
---------

``` bash
$ bower install
$ chmod 777 app/cache app/logs
$ php composer.phar update
$ php app/console doctrine:schema:create
$ php app/console doctrine:schema:update
$ php app/console doctrine:fixtures:load
$ php app/console assetic:dump
```

