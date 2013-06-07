##Steamy

Used to build out Eloquent ORM models and relationships from an existing database.

###Installing

####1:

Add ```"pitch/steamy": "dev-master"``` to your root composer.json file. You may already have other packages loading but it should look similar to:

```php
"require": {
	"laravel/framework": "4.0.*",
	"pitch/steamy": "dev-master"
},```

####2:

Add ```'Pitch\Steamy\SteamyServiceProvider'``` to you app/config/app.php file under 

```php
	'providers' => array(

		(omitted)
		'Pitch\Steamy\SteamyServiceProvider'
	),```

####3

Publish assets, run the below command from command line:

```shell
php artisan asset:publish```

###Using

Once you have installed Steamy visit yourdomain.com/steamy to proceed.

