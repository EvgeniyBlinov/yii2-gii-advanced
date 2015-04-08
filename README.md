# yii2-gii-advanced
yii2-gii-advanced


## Install

Put to composer.json

```json
   "require":{
        "cent/yii2-gii-advanced": "v0.1.0"
    },

    "repositories": [
        {
            "type"   :"package",
            "package": {
              "name"      : "cent/yii2-gii-advanced",
              "version"   :"v0.1.0",
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

Run `composer install`.

## Usage

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
