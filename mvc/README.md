# Laravel12 - Model View Controller

- https://laravel.com/docs/12.x/sail
- https://laravel.com/docs/12.x/passport
- https://laravel.com/docs/12.x/pint
- https://github.com/barryvdh/laravel-ide-helper

## Setup
```sh
composer install
```

### Laravel Sail

To make sure this is always available, you may add this to your shell configuration file in your home directory, such as ~/.zshrc or ~/.bashrc, and then restart your shell.

```sh
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

### Build to dev

```sh
sail up

# Setting Database
sail artisan migrate --seed

# Setting Laravel Passport
sail artisan passport:keys
sh ./setpassport.sh
```

### ide-helper
```sh
sail artisan ide-helper:generate
sail artisan ide-helper:meta
sail artisan ide-helper:models -N -R
```

## Formatter

### Laravel Pint
```sh
sail pint -v
```

## Database

### MySQL
```sh
sail mysql
```

## Mail

### Mailpit
- http://localhost:8025/

## Test

### Setting features test
```sh
cp .env.example .env.testing
sail artisan key:generate --env=testing
```

Change of .env.testing
```diff
DB_HOST=mysql
DB_PORT=3306
- DB_DATABASE=laravel
+ DB_DATABASE=testing
DB_USERNAME=sail
DB_PASSWORD=password
```

## Features

### Sign-in

```sh
# Admin user
curl -v POST http://localhost/api/sign-in \
  -H 'accept: application/json' \
  -H 'content-type: application/json' \
  -d '{"login_id": "admin@xxx.xxx","password": "password"}'
```

### First Sign-in

```sh
# After create the user
curl -v POST http://localhost/api/first-sign-in \
  -H'accept: application/json' \
  -H'content-type: application/json' \
  -d '{"login_id": "johndoe@example.com","password": "INITIAL PASSWORD","new_password": "NEW PASSWORD"}'
```

### Sign-out

```sh
curl -v DELETE http://localhost/api/sign-out \
  -H'accept: application/json' \
  -H'authorization: Bearer {PERSONAL ACCESS TOKEN}' \
  -H'content-type: application/json'
```

### User list

```sh
curl -v GET http://localhost/api/user \
  -H 'accept: application/json' \
  -H 'authorization: Bearer {PERSONAL ACCESS TOKEN}' \
  -H 'content-type: application/json'
```

### Create user

```sh
curl -v POST http://localhost/api/user \
  -H'accept: application/json' \
  -H'authorization: Bearer {PERSONAL ACCESS TOKEN}' \
  -H'content-type: application/json' \
  -d '{"name": "John Doe","email": "johndoe@example.com"}'
```

### Get user

```sh
curl -v GET http://localhost/api/user/{USER ULID} \
  -H'accept: application/json' \
  -H'authorization: Bearer {PERSONAL ACCESS TOKEN}' \
  -H'content-type: application/json'
```

### Update user

```sh
curl -v PUT  http://localhost/api/user/{USER ULID} \
  -H'accept: application/json' \
  -H'authorization: Bearer {PERSONAL ACCESS TOKEN}' \
  -H'content-type: application/json' \
  -d '{"name": "John Doe2","email": "johndoe2@example.com"}'
```

### Delete user 

```sh
curl -v DELETE http://localhost/api/user/{USER ULID} \
  -H'accept: application/json' \
  -H'authorization: Bearer {PERSONAL ACCESS TOKEN}' \
  -H'content-type: application/json'
```
