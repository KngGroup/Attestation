Attestation
===========

Описание
--------
Пошаговый пример создания приложения на symfony2 с применением angularjs.

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

Учебник
=======

Шаг 1.
------

На первом этапе мы сконфигурируем ``FosRestBundle``, ``SibersAngularBundle``,
``AsseticBundle`` и cоздадим приложение для ``angularjs``

FosRestBundle
^^^^^^^^^^^^^

Этот bundle предоставляет различные инструменты для быстрой разработки RESTful API 
и приложений на Symfony2.

Изначально нам необходимо настроить View Layer: он позволяет создавать
форматонезависимые (html, json, xml, etc) контроллеры, используя прослойку между
контроллером и генерацией окончательного ответа через шаблонизатор или serializer.

``` yaml
#app/config/config.yml
fos_rest:
    view:
        view_response_listener: true #включаем view listener для обработки View аннотации
        formats: #перечисляем доступные форматы
            json: true
        templating_formats: #перечисляем форматы, которые будут обрабатываться шаблонизатором
            html: true
    format_listener:
        default_priorities: [json, html, '*/*'] #приоритеты форматов
        fallback_format: json # формат по умолчанию
```

Поскольку мы используем ``view_response_listener`` нам необходимо отключить
``Template`` аннотацию ``SensioFrameworkExtraBundle``

``` yaml
sensio_framework_extra:
    view: { annotations: false }
```

SibersAngularBundle
^^^^^^^^^^^^^^^^^^^
``SibersAngularBundle`` предоставляет инструменты для интеграции symfony2 приложения
с фреймворком angularjs

Прежде всего нам необходимо добиться такого поведения, при котором на первый GET
запрос отдавался наш базовый шаблон.

Сконфигурируем наш bundle:

``` yaml
#app/config/config.yml
sibers_angular:
    ng_app:
      name: attestation #имя angular приложения. В шаблоне будет доступна глобальная переменная ngApp
    first_request:
        enabled: true
        base_view: "::base.html.twig" #базовый шаблон
        exclude_urls: ["^/_profiler"] #urls, при переходе на которые не будет отдаваться базовый шаблон
```

Теперь на первый GET запрос будет отдаваться шаблон ``::base.html.twig``.

Angular приложение
^^^^^^^^^^^^^^^^^^

Создадим основной файл приложения и сконфигурируем его:

``` javascript
// web/js/app.js

angular.module('attestation', [])
       .config(['$locationProvider', '$httpProvider','$interpolateProvider', function($locationProvider, $httpProvider, $interpolateProvider) {
           $locationProvider.html5Mode(true).hashPrefix('!');
           $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
           $interpolateProvider.startSymbol('~~').endSymbol('~~');
       });
```

Основные моменты, на которые стоит обратить внимание:
# для перечисления зависимостей всегда пользуйтесь расширенным синтаксисом, это позволит избежать проблем при сжатии js файлов
# в качестве управляющей последовательности для angularjs будет использоваться ``~~``, чтобы избежать пересечения с ``twig`` шаблонами.

AsseticBundle
^^^^^^^^^^^^^
``Assetic`` - фреймворк для управления ``asset``'ами.

``Asset`` - ресурс, содержащий фильтруемый контент, который может быть загружен и
сброшен(dumped) в другой файл.

Поскольку у нас будет достаточно много css и javascript файлов, мы сгруппируем их в 
несколько и сожмем утилитой google closure.

``` yaml
# Assetic Configuration
assetic:
    variables:
        min: [".min", ""]
    filters:
        cssrewrite: ~
        closure:
            jar: %kernel.root_dir%/Resources/java/compiler.jar
        yui_css:
            jar: %kernel.root_dir%/Resources/java/yuicompressor-2.4.7.jar
    assets:
      styles:
        inputs: ["css/bootstrap.css", "css/new_css.css", "css/select2.css"]
      vendors:
        vars: ["min"]
        inputs:
            - "js/bower_components/jquery/jquery{min}.js"
            - "js/bower_components/angular/angular{min}.js"
      app:
        inputs:
            - "js/angular/app.js"
```

Для того, чтобы в dev окружении у нас подключались полные версии файлов, а в prod - 
сжатые мы добавили переменную min. В конфиге мы должны перечислить все значения,
которые  может принимать эта переменная (чтобы assetic мог заранее подготовить
файлы для обоих окружений). Значение этой перемнной будет определяться во время
выполнения (передается в assetic через ``SibersAngularBundle``).

Также мы резделили js файлы на 2 категории: app и vendors - это сделано потому, 
что разработчики библиотек предоставляют сжатые версии для своих библиотек и нет
смысла их повторно сжимать. На выходе мы получим 2 js файла.

