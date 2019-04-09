# Persistent settings package for Laravel

[![Version](https://poser.pugx.org/akaunting/setting/v/stable.svg)](https://github.com/akaunting/setting/releases)
[![Downloads](https://poser.pugx.org/akaunting/setting/d/total.svg)](https://github.com/akaunting/setting)
[![StyleCI](https://styleci.io/repos/101231817/shield?style=flat&branch=master)](https://styleci.io/repos/101231817)
[![Quality](https://scrutinizer-ci.com/g/akaunting/setting/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/akaunting/setting)
[![License](https://poser.pugx.org/akaunting/setting/license.svg)](LICENSE.md)

This package allows you to save settings in a more persistent way. You can use the database and/or json file to save your settings. You can also override the Laravel config.

* Driver support
* Helper function
* Blade directive
* Override config values
* Custom file, table and columns
* Auto save
* Extra columns

## Getting Started

### 1. Install

Run the following command:

```bash
composer require akaunting/setting
```

### 2. Register (for Laravel < 5.5)

Register the service provider in `config/app.php`

```php
Akaunting\Setting\Provider::class,
```

Add alias if you want to use the facade.

```php
'Setting' => Akaunting\Setting\Facade::class,
```

### 3. Publish

Publish config file.

```bash
php artisan vendor:publish --tag=setting
```


### 4. Configure

You can change the options of your app from `config/setting.php` file

## Usage

You can either use the helper method like `setting('foo')` or the facade `Setting::get('foo')`

### Facade

```php
Setting::get('foo', 'default');
Setting::get('nested.element');
Setting::set('foo', 'bar');
Setting::forget('foo');
$settings = Setting::all();
```

### Helper

```php
setting('foo', 'default');
setting('nested.element');
setting(['foo' => 'bar']);
setting()->forget('foo');
$settings = setting()->all();
```

You can call the  `save()` method to save the changes.

### Auto Save

If you enable the `auto_save` option in the config file, settings will be saved automatically every time the application shuts down if anything has been changed.

### Blade Directive

You can get the settings directly in your blade templates using the helper method or the blade directive like `@setting('foo')`


### Override Config Values 

You can easily override default config values by adding them to the `override` option in `config/setting.php`, thereby eliminating the need to modify the default config files and also allowing you to change said values during production. Ex :
```php
'override' => [
        "app.name" => "app_name",
        "app.env" => "app_env",
        "mail.driver" => "app_mail_driver",
        "mail.host" => "app_mail_host",
],
```
The values on the left corresponds to the respective config value (Ex: config('app.name')) and the value on the right is the name of the `key` in your settings table/json file.

### JSON Storage

You can modify the path used on run-time using `setting()->setPath($path)`.

### Database Storage

If you want to use the database as settings storage then you should run the `php artisan migrate`. You can modify the table fields from the `create_settings_table` file in the migrations directory.

#### Extra Columns

If you want to store settings for multiple users/clients in the same database you can do so by specifying extra columns:

```php
setting()->setExtraColumns(['user_id' => Auth::user()->id]);
```

where `user_id = x` will now be added to the database query when settings are retrieved, and when new settings are saved, the `user_id` will be populated.

If you need more fine-tuned control over which data gets queried, you can use the `setConstraint` method which takes a closure with two arguments:

- `$query` is the query builder instance
- `$insert` is a boolean telling you whether the query is an insert or not. If it is an insert, you usually don't need to do anything to `$query`.

```php
setting()->setConstraint(function($query, $insert) {
	if ($insert) return;
	$query->where(/* ... */);
});
```

### Custom Drivers

This package uses the Laravel `Manager` class under the hood, so it's easy to add your own storage driver. All you need to do is extend the abstract `Driver` class, implement the abstract methods and call `setting()->extend`.

```php
class MyDriver extends Akaunting\Setting\Contracts\Driver
{
	// ...
}

setting()->extend('mystore', function($app) {
	return $app->make('MyStore');
});
```

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@akaunting.com instead of using the issue tracker.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Andreas Lutro](https://github.com/anlutro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
