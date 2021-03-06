##Steamy

[![Build Status](https://travis-ci.org/BrandonMcQueen/steam.png?branch=master)](https://travis-ci.org/BrandonMcQueen/steam)

Used to build out Eloquent ORM models and relationships from an existing database.

###Installing

Add ```"pitch/steamy": "dev-master"``` to your root composer.json file. You may already have other packages loading but it should look similar to:

```php
"require": {
	"laravel/framework": "4.0.*",
	"pitch/steamy": "dev-master"
},
```


Add ```'Pitch\Steamy\SteamyServiceProvider'``` to the bottom of the providers array in app/config/app.php

```php
	'providers' => array(

		(omitted)
		'Pitch\Steamy\SteamyServiceProvider'
	),
```


Publish assets, run the below command from command line:

```shell
php artisan asset:publish
```

Using
-----

> PLEASE BE AWARE, THIS SCRIPT WILL OVERWRITE YOUR MODELS IF YOU SELECT
> THEM TO BE WRITTEN FROM THE STEAMY DASHBOARD

Once you have installed Steamy visit ```yourdomain.com/steamy``` to proceed.