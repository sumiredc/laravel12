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
