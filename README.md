## Before you start

Do several following steps:

1. install latest php version, at leasts php7.2.0
2. install all related yii2 php extensions
3. install php-curl extension
4. install php-xdebug extension
5. install composer latest version
6. run `composer global require codeception/codeception`
7. run `composer install` inside project folder
8. run `./yii serve` inside project folder
9. check that [link](http://localhost:8080/base/view?users[]=frog&platforms[]=github) working correctly

## Project struct

The goal of this project is collect and aggregate statistic from different version control system hosting.  
Project collect some stats about users and their repos on version control system hosting such as:

-   github
-   gitlab
-   bitbucket

Whole project is implemented with yii2 framework.  
But you can find abstract framework-agnostic part of project located in interfaces folder.  
There in interfaces folder you can see how basically application work inside.

Folder `components` contains implementation of some interfaces eg:

-   components/platforms/Bitbucket.php implements interfaces/IPlatform.php

Folder `models` contains remaining implementation of some interfaces eg:

-   models/BitbucketRepo.php implements interfaces/IRepo.php

Folder `controllers` contain one base controller that serve api json endpoint and view endpoint.

Folders `assets`, `config`, `runtime`, `vendor`, `views`, `web` are internal folders.  
All other files in project folder are internal too.

Folder `tests` is folder that contains all necessary unit and functional test casess for implementation.  
In this project we using [Yii2 codeception](https://codeception.com/docs/modules/Yii2) for testing.

## Test Assignment

You should implement all test casess in following classes:

-   tests/functional/BaseCest.php
-   tests/unit/FactoryTest.php
-   tests/unit/SearcherTest.php
-   tests/unit/BitbucketRepoTest.php
-   tests/unit/GithubRepoTest.php
-   tests/unit/GitlabRepoTest.php
-   tests/unit/UserTest.php

You shouldn't change any other files in project.  
To run unit tests execute `codecept run unit`.  
To run functional tests execute `codecept run functional`.  
To run code coverage report execute `codecept run unit,functional --coverage-html`.
