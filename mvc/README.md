# Laravel12 MVC

## Laravel Pint
- https://laravel.com/docs/12.x/pint
```sh
./vendor/bin/pint -v
```

## ide-helper
- https://github.com/barryvdh/laravel-ide-helper
```sh
php artisan ide-helper:generate
php artisan ide-helper:meta
php artisan ide-helper:models -N -R
```

## Setup

```sh
php artisan migrate --seed
php artisan passport:client --personal

  Client ID ............................... xxxxxxxxxx  
  Client secret ........................... xxxxxxxxxx

  ✅️ Overwrite to .env
  - PASSPORT_PERSONAL_ACCESS_CLIENT_ID
  - PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET
```
