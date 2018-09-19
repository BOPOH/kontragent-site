# Сайт контрагентов
---

### Установка

Склонировать каталог
```sh
$ git clone https://github.com/BOPOH/kontragent-site
```

Перейти в склонированный каталог
```sh
$ cd kontragent-site
```

Установить зависимости проекта (установив перед этим [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx))
```sh
$ composer install
```

Выполнить инициализацию проекта
```sh
$ php init
```

Создать чистую БД и указать параметры подключения к ней в конфиге common/config/main-local.php

Провести миграции
```sh
$ php yii migrate
```

Сконфигурировать apache/nginx на доступ к папкам kontragent-site/backend/web и kontragent-site/frontend/web

**логин/пароль админа: admin@localhost/admin6**

    формат данных в файле импорта: invoice-id,transaction-type,transaction-amount,transaction-stamp,kontragent-name,kontragent-email
    где
    - invoice-id - id счета в системе,
    - transaction-type - deposit (для входящей транзакции) или withdrawal (для исходящей),
    - transaction-amount - сумма транзакции, целое число,
    - transaction-stamp - дата транзакции в формате 'Y-m-d h:i:s',
    - kontragent-name - имя контрагента,
    - kontragent-email - email контрагента

### Что не сделано
- не исправлены тесты
    когда проверял, возникла ошибка "Insufficient privilege: 7 ERROR: permission denied", начал разбираться, нашел [это](https://toster.ru/q/307902) и решил пока ничего не править, т.к. не знаю какой способ правильный + не работал с codeception + больше работал с unit-тестами, а не интеграционными, а значит смогу показать только то, чему научился "сейчас", а не что умел "до этого", поэтому и большого смысла показывать работу с тестами не вижу
- не сделано "опционально"
    >> Опционально. Факт попытки контрагента вызвать запросы администратора должен регистрироваться, а нарушители отдельным списком выводиться в админке.

    здесь работы на пару часов, но времени пока мало, и так уже затянул, поэтому, раз "опционально", то и не стал это делать
- хотел наконец-то доразбираться с docker'ом, но времени пока мало, поэтому развертывание без него
