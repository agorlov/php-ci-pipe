# php-ci-pipe

CI/CD Pipeline for php projects

1. Project environment: setup [lxc container](https://gist.github.com/agorlov/d40c86e534116ce2e2f3ef31fad20f9f)
2. Write README.md
3. Plug PDD (todo to ticket maker)
4. rultor (build, checks and deployment
  - deploy.php
  - psr2 - phpcs
  - phpmd
  - phplint ?
  - jslint
  - unit tests 
5. codeception
6. хочу: возможность прогнать чеки сразу после пула, не только при мерже и деплое
7. бот, который автоматически удаляет смерженые бранчи: https://github.com/apps/delete-merged-branch 
 или настройка проекта ``Settings -> Options -> Merge Button``:
 ![image](https://user-images.githubusercontent.com/2485728/64680846-3ec0b180-d487-11e9-8330-2b9cd83d8a20.png)

8. запрет на merge в мастер без ревью
 ![image](https://user-images.githubusercontent.com/2485728/64680787-1cc72f00-d487-11e9-92a8-213cec0ed747.png)
 
 
 # PDD (puzzle driven development)
 
 ## Настройки
 
 1. подключить колаборатора к репозитарию @0pdd
 2. добавить webhook https://www.yegor256.com/2017/04/05/pdd-in-action.html
 3. добавить конфиги:
 4. наладить
 
.0pdd.yaml:

```yaml
errors:
  - a.gorlov@gmail.com
# alerts:
#   github:
#     - agorlov
#
#tags:
#  - pdd
#  - bug
```

.pdd
```
--source=.
#--verbose
--exclude lib/vendor/**/*
--exclude www/js/application/classes/docs/**/*
--exclude www/js/lightbox/**/*
--exclude www/tinymce/**/*
--exclude scripts/iMacros/**/*
--exclude scripts/import/**/*
--exclude scripts/tomita/**/*
--exclude scripts/2gis/**/*
```

как отладить:

1. установить ``$ gem install pdd``
2. зайти в директорию проекта и запустить: pdd -v -f pdd.out

Если есть ошибки, исправить.

## PDD секция для README.md проека

Можно новые тикеты создавтаь непосредственно из кода с помощью @0pdd бота:

В любом месте в коде оставляем комментарий todo, стараемся сделать понятным
для того кто будет его делать:

<pre>
// &#64;todo #123 сделать вот что-то, вот так-то
</pre>

где #123 - номер тикета над которым сейчас работаем

задачу можно сформулировать в несколько строк, добавив отступ в виде одного пробела:

<pre>
// &#64;todo #123 сделать вот что-то, вот так-то
//  строка еще одна, чтобы детальнее описать задачу
//  и еще одна если нужно
</pre>

Как форматировать:
https://github.com/yegor256/pdd#how-to-format

Если пазлы не появляются, как проверить, что pdd работает корректно :
1. установить ``$ gem install pdd``
2. зайти в директорию проекта и запустить: pdd -v -f pdd.out

Если есть ошибки, исправить.

# Composer

Фрагмент composer.json:

```json
    "require-dev": {
        "friendsofphp/php-cs-fixer": "^2.15",
        "squizlabs/php_codesniffer": "^3.4",
        "phpunit/phpunit": "8",
        "codeception/codeception": "^3.0",
        "phpstan/phpstan": "^0.11.8",
        "phpmd/phpmd": "^2.6"
    },
...
    "scripts": {
        "phpcs-fix": [
            "phpcs --standard=PSR2 --colors src/ tests/unit",
            "php-cs-fixer fix src/",
            "php-cs-fixer fix tests/unit"
        ],
        "test": [
            "codecept run unit"
        ],
        "phpcs": [
            "vendor/bin/phpcs --standard=PSR2 src/ tests/unit/"
        ],
        "phpstan": [
            "vendor/bin/phpstan analyse --error-format=table --no-progress -l3 -c phpstan.neon src/ tests/unit/"
        ],
        "phpmd": [
            "php phpmd.php"
        ]
    },
    ...
```
# PSR2: Actions + PHPCS

Теперь чек на PSR2 можно подключить **легко** благодаря Github Actions:

1. Подключаем composer пакет: ``$ composer require squizlabs/php_codesniffer``
2. Создаем файл ``.github/workflows/php.yml``:

```yaml
name: Unit tests and PSR2 check

on:
  pull_request:
    types: [opened, synchronize]

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v2

    - name: Install dependencies
      run: composer install --prefer-dist --no-progress --no-suggest

    - name: Run test suite
      run: composer test unit

    - name: Run PSR2 Check
      run: composer phpcs
```


## Github Actions: full build в lxc

```yaml
name: Сборка lxc-контейнера и запуск всех тестов

# Запускаем, когда меняется что-то в lxc-миграциях, либо миграциях, либо в функциональных тестах
on:
  pull_request:
    paths:
    - "lxc-migrations/**"
    - "migrations/**"
    - "tests/functional/**"
    #types: [opened, synchronize]

jobs:
  build:

    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v2

    - name: Install lxc system package
      run: |
        sudo apt-get update
        sudo apt-get install lxc lxc-templates -y

    - name: Создаем lxc-контейнер
      run: |
        sudo bash ./lxc-init.sh

    - name: Обновление lxc-контейнера
      run: sudo php ./lxc-update.php

    - name: Запуск всех тестов в контейнере
      run: |
        sudo lxc-start axe
        sudo lxc-attach axe -- su - runner -c "cd /www && /usr/bin/php vendor/bin/codecept run"
```

##  Github Actions: deploy

```yaml
name: Развертывание в прод

on:
  release:
    types: [published]

# Для отладки - тренировался на ветке deploy-setup
#  push:
#    branches:
#      - "deploy-setup"

# Варианты событий для старта деплоя:
#  release
#  push: "release/**"
#

# Еще вариант, при закрытии _смерженного_ PR:
# https://github.community/t5/GitHub-Actions/Depend-on-another-workflow/td-p/32317
#on:
#  pull_request:
#    types: [closed]
#    branches:
#      - master
#
#jobs:
#  deploy:
#    runs-on: ubuntu-latest
#    if: github.event.pull_request.merged
#    ...



jobs:
  build:

    runs-on: ubuntu-latest

    # Можно ли запустить билд, перед заливкой?

    steps:
      - uses: actions/checkout@v2

      - name: Подготовка ключей
        run: |
          echo "${{ secrets.AXE_SSH_KEY }}" > key.txt
          chmod 400 key.txt
          ls -l
          mkdir -p ~/.ssh
          ssh-keyscan -p2225 axe.inline-ltd.ru  >> ~/.ssh/known_hosts


      - name: Install php-packages (composer)
        run: |
          composer install --prefer-dist --no-progress --no-suggest --no-dev

      - name: Deploy to production
        run: |
          php scripts/deploy.php go

```
