# php-ci-pipe

CI/CD Pipeline for php projects

1. README
2. pdd (todo to ticket maker)
3. rultor (build, checks and deployment
  - deploy.php
  - psr2 - phpcs
  - phpmd
  - phplint ?
  - jslint
  - unit tests 
4. codeception
5. хочу: возможность прогнать чеки сразу после пула, не только при мерже и деплое
6. бот, который автоматически удаляет смерженые бранчи: https://github.com/apps/delete-merged-branch 
 или настройка проекта ``Settings -> Options -> Merge Button``:
 ![image](https://user-images.githubusercontent.com/2485728/64680846-3ec0b180-d487-11e9-8330-2b9cd83d8a20.png)

7. запрет на merge в мастер без ревью
 ![image](https://user-images.githubusercontent.com/2485728/64680787-1cc72f00-d487-11e9-92a8-213cec0ed747.png)
