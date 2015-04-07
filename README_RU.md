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
                    "psr-0": { "": "" }
                }
            }
        }
    ]
```

Для загрузки бандла нужно выполнить `composer install`.

