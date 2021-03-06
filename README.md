# Yii2-обертка для работы с RESTfull API v2 AmoCRM
Обертка реализует работу с основными сущностями AmoCRM через ее API.
Реализация для сущности AmoCRM будет добавляться по мере возможного.
Более детальное описание содержится [здесь](https://github.com/OlegChulakovStudio/amocrm).
## Установка
Установка производится через Composer, путем ручного добавления пакета в `composer.json`
```
"require": {
    ...
    "oleg-chulakov-studio/yii2-amocrm": "~1.0"
    ...
}
```
или с помощью консольной команды
```bash
composer require oleg-chulakov-studio/yii2-amocrm
```
## Конфигурация компонента Yii2
```php
return [
    ...
    'components' => [
        ...    
        'amo' => [
            'class'=> chulakov\yii\amocrm\Api::class,
            'subdomain' => '<your_subdomain_in_amo>',
            'login' => '<administrative_account_login>',
            'hash' => '<secret_hash>',
        ],
        ...
    ],
    ...
];
```
### Примеры использования
```php
// Получить информацию о текущем аккаунте
$info = Yii::$app->amo->account->info();
```