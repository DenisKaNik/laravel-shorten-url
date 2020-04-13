<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Info

* Laravel 7
* PHP >= 7.2
* MySQL
* Vue.js

**Проверить наличие PHP модулей:**
* curl
* intl
* json
* mbstring

## Установка

```
$ git clone https://github.com/DenisKaNik/laravel-shorten-url.git
$ composer install
```

Если не появился в корне проекта файл`.env`, то выполнить
```
$ cp .env.example .env && ./artisan key:generate
```

Затем продолжаем установку
```
$ npm install && npm run prod
$ find ./storage -type d -exec chmod 777 {} \;
```

## Запуск

Для быстрого запуска и без использования БД достаточно выполнить:
```
$ ./artisan serve
```
И открыть в браузере указанный адрес. Например, http://127.0.0.1:8000

---

При использовании БД нужно создать базу данных и прописать доступы в `.env`:
```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
Выполнить:
```
$ ./artisan migrate
```

Изменить параметр `SHORTEN_URL_STORAGE_DRIVER` на значение **db** и перезапустить сервер:
 ```
 $ ./artisan migrate
 ```

---

Либо использовать Nginx конфигурацию из файла `app_nginx.conf`

