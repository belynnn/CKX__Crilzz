# Bug report & solutions
Ce document vise à garder en mémoire chaque bug ou dépréciation rencontré et qu'est-ce que j'ai mis en place pour les résoudre

## Bug 1
### Log
```
( ! ) Deprecated: ini_set(): assert.warning INI setting is deprecated in C:\xampp-8-2\htdocs\crilzz\vendor\symfony\runtime\Internal\BasicErrorHandler.php on line 35
Call Stack
#	Time	Memory	Function	Location
1	0.0001	573520	{main}( )	...\index.php:0
2	0.0002	579024	require_once( 'C:\xampp-8-2\htdocs\crilzz\vendor\autoload_runtime.php )	...\index.php:5
3	0.0127	2920648	Symfony\Component\Runtime\SymfonyRuntime->__construct( $options = ['project_dir' => 'C:\\xampp-8-2\\htdocs\\crilzz'] )	...\autoload_runtime.php:16
4	0.0131	2930432	Symfony\Component\Runtime\GenericRuntime->__construct( $options = ['project_dir' => 'C:\\xampp-8-2\\htdocs\\crilzz', 'env_var_name' => 'APP_ENV', 'debug_var_name' => 'APP_DEBUG', 'debug' => TRUE, 'disable_dotenv' => TRUE, 'error_handler' => 'Symfony\\Component\\Runtime\\Internal\\SymfonyErrorHandler'] )	...\SymfonyRuntime.php:128
5	0.0132	2934688	Symfony\Component\Runtime\Internal\SymfonyErrorHandler::register( $debug = TRUE )	...\GenericRuntime.php:76
6	0.0132	2941296	Symfony\Component\Runtime\Internal\BasicErrorHandler::register( $debug = TRUE )	...\SymfonyErrorHandler.php:27
7	0.0132	2941704	ini_set( $option = 'assert.warning', $value = 0 )	...\BasicErrorHandler.php:35
```
### Solution
1. Mettre à jour symfony/runtime '"symfony/runtime": "6.1.*",' pour : '"symfony/runtime": "^6.3"'
2. composer 'update symfony/runtime' ou 'composer require symfony/runtime:^6.4'

## Bug 2 : Passer tout Symfony en 6.4 (ou plus) et rester compatible avec PHPDoc Parser v2
### Log
```
Executing script cache:clear [KO]
 [KO]
Script cache:clear returned with error code 255
!!
!!  // Clearing the cache for the dev environment with debug true
!!
!!  10:11:13 CRITICAL  [php] Uncaught Error: Too few arguments to function PHPStan\PhpDocParser\Parser\ConstExprParser::__construct(), 0 passed in C:\xampp-8-2\htdocs\crilzz\vendor\symfony\property-info\Extractor\PhpStanExtractor.php on line 67 and exactly 1 expected ["exception" => ArgumentCountError { …}]
!!
!!  In ConstExprParser.php line 18:
!!                                                                                                                                                                                       !!  Too few arguments to function PHPStan\PhpDocParser\Parser\ConstExprParser::__construct(), 0 passed in C:\xampp-8-2\htdocs\crilzz\vendor\symfony\property-info\Extractor\PhpStanExtractor.php on line 67 and                                                                                                                                                                                      
!!  exactly 1 expected
!!
!!
!!  cache:clear [--no-warmup] [--no-optional-warmers]
!!
!!
Script @auto-scripts was called via post-update-cmd
```
### Solution
1. Se rendre dans le fichier composer.json
2. Mettre à jour toute les dépendances '6.1.*' en '^6.4'
3. Mettre à jour 'phpstan/phpdoc-parser' en '^2.0'
4. Lancer la commande 'composer update' dans le terminal

## Bug 3
### Log
```
10:28:18.000 AM
deprecation 	Since symfony/framework-bundle 6.4: Not setting the "framework.handle_all_throwables" config option is deprecated. It will default to "true" in 7.0.
2 times

[▼
  "exception" => Symfony\Component\ErrorHandler\Exception\SilencedErrorContext {#637 ▼
    +count: 2
    -severity: E_USER_DEPRECATED
    trace: {▼
      C:\xampp-8-2\htdocs\crilzz\vendor\symfony\framework-bundle\DependencyInjection\Configuration.php:93 {▼
        Symfony\Bundle\FrameworkBundle\DependencyInjection\Configuration->Symfony\Bundle\FrameworkBundle\DependencyInjection\{closure} …
        › if (!isset($v['handle_all_throwables'])) {
        ›     trigger_deprecation('symfony/framework-bundle', '6.4', 'Not setting the "framework.handle_all_throwables" config option is deprecated. It will default to "true" in 7.0.');
        › }
      }
      C:\xampp-8-2\htdocs\crilzz\vendor\symfony\config\Definition\Builder\ExprBuilder.php:246 {▼
        ›     $then = $expr->thenPart;
        ›     $expressions[$k] = static fn ($v) => $if($v) ? $then($v) : $v;
        › }
      }
    }
  }
]
```
### Solution
1. Se rendre dans le fichier framework.yaml
  ```
  # see https://symfony.com/doc/current/reference/configuration/framework.html
  framework:
      secret: '%env(APP_SECRET)%'
      #csrf_protection: true
      http_method_override: false

      # Enables session support. Note that the session will ONLY be started if you read or write from it.
      # Remove or comment this section to explicitly disable session support.
      session:
          handler_id: null
          cookie_secure: auto
          cookie_samesite: lax
          storage_factory_id: session.storage.factory.native

      #esi: true
      #fragments: true
      php_errors:
          log: true

  when@test:
      framework:
          test: true
          session:
              storage_factory_id: session.storage.factory.mock_file
  ```
2. Ajouter la ligne 'handle_all_throwables: true' en dessous de 'php_errors: log true'
  ```
  # see https://symfony.com/doc/current/reference/configuration/framework.html
  framework:
      secret: '%env(APP_SECRET)%'
      #csrf_protection: true
      http_method_override: false

      # Enables session support. Note that the session will ONLY be started if you read or write from it.
      # Remove or comment this section to explicitly disable session support.
      session:
          handler_id: null
          cookie_secure: auto
          cookie_samesite: lax
          storage_factory_id: session.storage.factory.native

      #esi: true
      #fragments: true
      php_errors:
          log: true
      handle_all_throwables: true

  when@test:
      framework:
          test: true
          session:
              storage_factory_id: session.storage.factory.mock_file
  ```
3. Vider le cache pour être sûr que les modificaitons ont été prise en compte avec la commande 'php bin/console cache:clear'

## Bug 4
### Log
```
10:28:18.000 AM
deprecation 	Since doctrine/doctrine-bundle 2.12: The default value of "doctrine.orm.controller_resolver.auto_mapping" will be changed from `true` to `false`. Explicitly configure `true` to keep existing behaviour.

[▼
  "exception" => Symfony\Component\ErrorHandler\Exception\SilencedErrorContext {#719 ▼
    +count: 1
    -severity: E_USER_DEPRECATED
    trace: {▼
      C:\xampp-8-2\htdocs\crilzz\vendor\doctrine\doctrine-bundle\src\DependencyInjection\DoctrineExtension.php:504 {▼
        Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension->ormLoad(array $config, ContainerBuilder $container) …
        › if ($config['controller_resolver']['auto_mapping'] === null) {
        ›     trigger_deprecation('doctrine/doctrine-bundle', '2.12', 'The default value of "doctrine.orm.controller_resolver.auto_mapping" will be changed from `true` to `false`. Explicitly configure `true` to keep existing behaviour.');
        ›     $config['controller_resolver']['auto_mapping'] = true;
      }
      C:\xampp-8-2\htdocs\crilzz\vendor\doctrine\doctrine-bundle\src\DependencyInjection\DoctrineExtension.php:122 {▼
        › 
        ›     $this->ormLoad($config['orm'], $container);
        › }
      }
    }
  }
]
```
### Solution
1. Se rendre dans le fichier 'doctrine.yaml'
  ```
  doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '16'
        use_savepoints: true
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App

  when@test:
      doctrine:
          dbal:
              # "TEST_TOKEN" is typically set by ParaTest
              dbname_suffix: '_test%env(default::TEST_TOKEN)%'

  when@prod:
      doctrine:
          orm:
              auto_generate_proxy_classes: false
              proxy_dir: '%kernel.build_dir%/doctrine/orm/Proxies'
              query_cache_driver:
                  type: pool
                  pool: doctrine.system_cache_pool
              result_cache_driver:
                  type: pool
                  pool: doctrine.result_cache_pool

      framework:
          cache:
              pools:
                  doctrine.result_cache_pool:
                      adapter: cache.app
                  doctrine.system_cache_pool:
                      adapter: cache.system
  ```
2. Ajouter 'controller resolver' à la fin de la ligne 'orm', à la même indentaton de mappings
  ```
  doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '16'
        use_savepoints: true
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App
        controller_resolver:
            auto_mapping: true

  when@test:
      doctrine:
          dbal:
              # "TEST_TOKEN" is typically set by ParaTest
              dbname_suffix: '_test%env(default::TEST_TOKEN)%'

  when@prod:
      doctrine:
          orm:
              auto_generate_proxy_classes: false
              proxy_dir: '%kernel.build_dir%/doctrine/orm/Proxies'
              query_cache_driver:
                  type: pool
                  pool: doctrine.system_cache_pool
              result_cache_driver:
                  type: pool
                  pool: doctrine.result_cache_pool

      framework:
          cache:
              pools:
                  doctrine.result_cache_pool:
                      adapter: cache.app
                  doctrine.system_cache_pool:
                      adapter: cache.system
  ```
3. Vider le cache pour être sûr que les modificaitons ont été prise en compte avec la commande 'php bin/console cache:clear'

## Bug 5
### Log
```
10:28:18.000 AM
deprecation 	Since doctrine/doctrine-bundle 2.13: Enabling the controller resolver automapping feature has been deprecated. Symfony Mapped Route Parameters should be used as replacement.

[▼
  "exception" => Symfony\Component\ErrorHandler\Exception\SilencedErrorContext {#718 ▼
    +count: 1
    -severity: E_USER_DEPRECATED
    trace: {▼
      C:\xampp-8-2\htdocs\crilzz\vendor\doctrine\doctrine-bundle\src\DependencyInjection\DoctrineExtension.php:509 {▼
        Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension->ormLoad(array $config, ContainerBuilder $container) …
        › if ($config['controller_resolver']['auto_mapping'] === true) {
        ›     trigger_deprecation('doctrine/doctrine-bundle', '2.13', 'Enabling the controller resolver automapping feature has been deprecated. Symfony Mapped Route Parameters should be used as replacement.');
        › }
      }
      C:\xampp-8-2\htdocs\crilzz\vendor\doctrine\doctrine-bundle\src\DependencyInjection\DoctrineExtension.php:122 {▼
        › 
        ›     $this->ormLoad($config['orm'], $container);
        › }
      }
    }
  }
]
```
### Solution
1. Se rendre dans le fichier doctrine.yaml
  ```
  doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '16'
        use_savepoints: true
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App
        controller_resolver:
            auto_mapping: true

  when@test:
      doctrine:
          dbal:
              # "TEST_TOKEN" is typically set by ParaTest
              dbname_suffix: '_test%env(default::TEST_TOKEN)%'

  when@prod:
      doctrine:
          orm:
              auto_generate_proxy_classes: false
              proxy_dir: '%kernel.build_dir%/doctrine/orm/Proxies'
              query_cache_driver:
                  type: pool
                  pool: doctrine.system_cache_pool
              result_cache_driver:
                  type: pool
                  pool: doctrine.result_cache_pool

      framework:
          cache:
              pools:
                  doctrine.result_cache_pool:
                      adapter: cache.app
                  doctrine.system_cache_pool:
                      adapter: cache.system
  ```
2. Passer l'auto_mapping du controller_resolver à 'false'
  ```
  doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '16'
        use_savepoints: true
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App
        controller_resolver:
            auto_mapping: false

  when@test:
      doctrine:
          dbal:
              # "TEST_TOKEN" is typically set by ParaTest
              dbname_suffix: '_test%env(default::TEST_TOKEN)%'

  when@prod:
      doctrine:
          orm:
              auto_generate_proxy_classes: false
              proxy_dir: '%kernel.build_dir%/doctrine/orm/Proxies'
              query_cache_driver:
                  type: pool
                  pool: doctrine.system_cache_pool
              result_cache_driver:
                  type: pool
                  pool: doctrine.result_cache_pool

      framework:
          cache:
              pools:
                  doctrine.result_cache_pool:
                      adapter: cache.app
                  doctrine.system_cache_pool:
                      adapter: cache.system
  ```

## Bug 6
### Log
```
11:04:55.548 AM
error 	Uncaught PHP Exception Symfony\Component\HttpKernel\Exception\NotFoundHttpException: "No route found for "GET http://127.0.0.1:8000/"" at RouterListener.php line 127
```
### Solution
Symfony ne crée par de page d'accueil par défaut, il faut donc créer un controller avec une route '/'
1. Utiliser la commande 'php bin/console make:controller HomeController' dans le terminal
  Cela va :
  - créer le fichier HomeController.php 'src/Controller/HomeController.php'
  - créer le fichier template Twig 'templates/home/index.html.twig'
  - ajouter une méthode avec une route par défaut (que tu peux adapter)

## Bug 7
### Log
```
> watch
> encore dev --watch

Running webpack ...

 ERROR  Failed to compile with 1 errors                                                                                                                                                                 12:43:32

Error loading ./assets/styles/app.scss

FIX To load Sass files:
        1. Add Encore.enableSassLoader() to your webpack.config.js file.
        2. Install sass-loader & sass (or sass-embedded or node-sass) to use enableSassLoader()
              npm install sass-loader@^16.0.1 sass --save-dev

Entrypoint app 16 KiB = runtime.js 13.7 KiB app.js 2.25 KiB
webpack compiled with 1 error
```
### Solution
1. Se rendre dans le fichier 'webpack.config.js'
2. Ajouter ou décommenter la ligne '.enableSassLoader()'
3. Installer les paquets nécessaires avec la commande 'npm install sass-loader@^16.0.1 sass --save-dev' dans le terminal
4. Relancer la compilation avec la commande 'npm run watch'

## Bug 8
### Log
```
C:\xampp-8-2\htdocs\crilzz>php bin/console doctrine:database:create
Could not create database "app" for connection named default
An exception occurred in the driver: could not find driver
```
### Solution
1. Vérifie que Doctrine ORM est installé
```
composer require symfony/orm-pack
composer require symfony/maker-bundle
composer require doctrine/doctrine-migrations-bundle
```

## Bug 9
### Log
```
In ConnectionFactory.php line 269:
                              
  Malformed parameter "url".  
                              

In MalformedDsnException.php line 11:
                                     
  Malformed database connection URL  
                                     
```
### Solution
Je souhaitais mettre mes informations perso pour me connecter à la base de données dans mon projet symfony directement dans le fichier .env.local mais je n'avais pas définis les même paramètres pour que Symfony puisse extrapoler les informations

Ce que j'ai ajouté au fichier .env déjà rempli
```
DATABASE_USER=default_user
DATABASE_PASSWORD=default_pass
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306
DATABASE_NAME=default_db
DATABASE_SERVER_VERSION=10.4.32
DATABASE_CHARSET=utf8mb4

DATABASE_URL="mysql://${DATABASE_USER}:${DATABASE_PASSWORD}@${DATABASE_HOST}:${DATABASE_PORT}/${DATABASE_NAME}?serverVersion=${DATABASE_SERVER_VERSION}&charset=${DATABASE_CHARSET}"
```

Ce que j'ai ajouté au fichier .env.local vide
```
DATABASE_USER=belynn
DATABASE_PASSWORD=arPLcl357
DATABASE_NAME=crilzz
```

## Bug 10
### Log
```
C:\xampp-8-2\htdocs\crilzz>php bin/console make:migration

In ExceptionConverter.php line 101:
                                                                                                                                       
  An exception occurred in the driver: SQLSTATE[HY000] [1045] Access denied for user 'default_user'@'localhost' (using password: YES)  
                                                                                                                                       

In Exception.php line 24:
                                                                                                  
  SQLSTATE[HY000] [1045] Access denied for user 'default_user'@'localhost' (using password: YES)  
                                                                                                  

In Driver.php line 33:
                                                                                                  
  SQLSTATE[HY000] [1045] Access denied for user 'default_user'@'localhost' (using password: YES)  
                                                                                                  

make:migration [--formatted] [--configuration [CONFIGURATION]]
```

### Solution
```
DATABASE_USER=root
DATABASE_PASSWORD=
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306
DATABASE_NAME=default_db
DATABASE_SERVER_VERSION=10.4.32
DATABASE_CHARSET=utf8mb4
```

## Bug 12
### Log
```
C:\xampp-8-2\htdocs\crilzz>php bin/console make:migration

In AbstractPlatform.php line 452:
                                                                                                     
  Unknown database type enum requested, Doctrine\DBAL\Platforms\MySQL84Platform may not support it.  
                                                                                                     

make:migration [--formatted] [--configuration [CONFIGURATION]]
```
### Solution
J'ai créé un fichier migration.bat qui me permet de drop la base de données Doctrine et de la recréer, de supprimer les anciennes version de migration et de lancer une migration. Le problème venait d'une erreur dans la base de données.
```
@REM Delete toute ce qui se trouve dans le dossier migrations qui commence par V 
del migrations\V*
@REM Supprimer la base de données (et empêcher l'interaction)
symfony console doctrine:database:drop --force --no-interaction
@REM Créer une base de données avec le même nom (elle est vide)
symfony console doctrine:database:create
@REM On fait une migration de notre code actuel pour recréer les tables à jour
symfony console make:migration --no-interaction
@REM On synchronise avec la DB
symfony console doctrine:migration:migrate --no-interaction
@REM Lancer les fixtures
symfony console doctrine:fixtures:load --no-interaction
```

## Bug 13
### Log
```

```
### Solution
```

```