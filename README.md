# Установка проекта
---

Склонировать каталог
```sh
$ git clone https://github.com/BOPOH/kontragent-site
```

Перейти в склонированный каталог
```sh
$ cd kontragent-site
```

Установить [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) и зависимости проекта
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
логин/пароль админа: admin@localhost/admin6
