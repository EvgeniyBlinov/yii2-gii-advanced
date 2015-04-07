RefrigeratorBundle
==================

RefrigeratorBundle - система кеширования вывода SF2 при помощи Redis.


## Установка

Содержимое composer.json

```json
   "require":{
        "cent/refrigerator-bundle": "dev-master"
    },
    
    "minimum-stability": "dev",
    "repositories": [
        {
            "type"   :"package",
            "package": {
              "name"      : "cent/refrigerator-bundle",
              "version"   :"dev-master",
              "source": {
                  "url": "https://github.com/EvgeniyBlinov/RefrigeratorBundle",
                  "type": "git",
                  "reference":"master"
                },
                "autoload": {
                    "psr-0": { "Cent\\RefrigeratorBundle": "" }
                },
                "target-dir": "Cent/RefrigeratorBundle"
            }
        }
    ]
```

Для загрузки бандла нужно выполнить `composer install`.

### Примеры использования.

Содержание файла настроек (parameters.yml)

```yaml
use_refrigerator_cache: false
    redis_uri: 'tcp://127.0.0.1:6379?alias=first-node'
    redis_options:
        prefix: 'sitename:'
    refrigerator:
        cache_all: false
```
Статический метод для подстановки нужного префикса для Redis, для мультидоменного ресурса

```php
    /**
     * Get redis options
     * 
     * @param array $redisOptions
     * @return array
     */
    public static function getRedisOptions(array $redisOptions)
    {
        if (isset($redisOptions['prefix'])) {
            $redisOptions['prefix'] = sprintf('%s%s:', $redisOptions['prefix'], $_SERVER['HTTP_HOST']);
        }
        return $redisOptions;
    }
```

Содержание файла сервисов (services.yml)

```yaml
#### service refrigerator
    mysite_redis_options:
        class: ArrayObject
        factory: ["static_method_class_name_with_namespace", "getRedisOptions"]
        arguments: 
            - "%redis_options%"
    redis:
        #class: Predis\Client
        class: Cent\RefrigeratorBundle\Extension\AdvancedRedisClient
        arguments: [ "%redis_uri%", "@mysite_redis_options" ]

    #  Хранение настроек в БД {{{
            RefrigeratorLinksRepository:
                class: Doctrine\ORM\EntityRepository
                factory_service: doctrine.orm.entity_manager
                factory_method: getRepository
                arguments:
                    - SkaBundle:Refrigerator\RefrigeratorLinks

            refrigerator_options_factory:
                class: Cent\RefrigeratorBundle\Extension\RefrigeratorOptionsFactory
                arguments: [ "@RefrigeratorLinksRepository" ]
   # }}}

    redis_cache:
        class: Cent\RefrigeratorBundle\Extension\Adapter\RedisAdapter
        arguments: [ "@redis" ]

    cent.refrigerator_bundle.extension.cache_factory:
        class: Cent\RefrigeratorBundle\Extension\CacheFactory
        arguments: 
            0: "@redis"
            1: "@request_stack"
            2: { options: "%refrigerator%", tags: "%entity_tags%", cache_options: "@refrigerator_options_factory", cache_options_ignored: "%refrigerator_ignored_links%" }
        calls: 
            - [ getAdapter ]

    cent.refrigerator_bundle.cache_listener:
        class: Cent\RefrigeratorBundle\EventListener\CacheListener
        arguments: [ "@service_container",  "@cent.refrigerator_bundle.extension.cache_factory"]
        tags:
            - { name: "kernel.event_listener", event: "kernel.controller",  method: "onKernelController" }
            - { name: kernel.event_listener, event: kernel.terminate, method: onKernelResponse }
```

