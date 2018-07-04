## Laravel api source with voyager admin package
This package install source code for laravel api project and install voyager admin package
## Installation
At the first you need have empty laravel project. Next step is
Either run

```sh
php composer.phar require lenboard/voyager-api-admin
```

or add

```
"lenboard/voyager-api-admin": "dev-master"
```

to the require section of your `composer.json` file.

Next you need update composer packages by command:

```composer update```

This command install needing package to empty laravel project.

## Basic usage

1. Add ```Lenboard\VoyagerApiAdmin\VoyagerApiAdminServiceProvider::class``` to __app/config.php__ file at the section providers.
2. Run the command at root directory project

```sh
php artisan vendor:publish --force
```

and choose ```Provider: Lenboard\VoyagerApiAdmin\VoyagerApiAdminServiceProvider```

This command copy need files to you project. Be carefully, this command overwrite some files at project.

In the next step you must configure project config file (.env). You must editing database configuration and application url (APP_URL). If you did this earlier, you can skip this step.
Then you must run commands:

```sh
php composer.phar dump-autoload && php artisan migrate:fresh --seed
```

This commands create needed database structure and paste some information to database.
Next step is include file routes/voyager-admin-api-routes.php to file routes/web.php
```php
//...
include __DIR__ . '/voyager-admin-api-routes.php';
//...
```

Create symlink to storage directory. Command for this is:
```sh
php artisan storage:link
```

That is finish. For access to admin panel go to the link __http(s)://example.com__/admin

Access for admin:
__email:__ admin@admin.com
__password:__ password