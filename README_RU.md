# yii2-gii-advanced
yii2-gii-advanced


## Установка

Содержимое composer.json

```json
   "require":{
        "cent/yii2-gii-advanced": "v0.0.1"
    },

    "minimum-stability": "dev",
    "repositories": [
        {
            "type"   :"package",
            "package": {
              "name"      : "cent/yii2-gii-advanced",
              "version"   :"v0.0.1",
              "source": {
                  "url": "https://github.com/EvgeniyBlinov/yii2-gii-advanced",
                  "type": "git",
                  "reference":"master"
                },
                "autoload": {
                    "psr-4": { "cent\\gii\\": "" }
                }
            }
        }
    ]
```

Для загрузки бандла нужно выполнить `composer install`.

## Подключение

```php
if (!YII_ENV_TEST) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'allowedIPs' => ['127.0.0.1'],
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => [
                'class' => 'cent\gii\generators\model\Generator',
            ]
        ]
    ];
}
```
