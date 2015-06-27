# yii2-gii-advanced
yii2-gii-advanced


## Install

```sh
composer config repositories.yii2-gii-advanced git https://github.com/EvgeniyBlinov/yii2-gii-advanced
composer require "cent/yii2-gii-advanced:v0.1.3"
```
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
