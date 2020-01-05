
## About Starwar API

Starwar API is backend service developed using laravel framework to fetch required data from data storage(mongodb). Data can be used to display on frontend applications.


Installation
------------

Make sure you have the MongoDB PHP driver installed. You can find installation instructions at http://php.net/manual/en/mongodb.installation.php

**WARNING**: The old mongo PHP driver is not supported anymore in versions >= 3.0.

- git clone https://github.com/mohsininayatkhan/starwar-api.git 
- cd starwar-api
- cp .env.testing .env
- composer install 

Configuration
-------------
Make changes for MongoDB connection string in config/database.php:

```php
'mongodb' => [
    'driver'   => 'mongodb',
    'dsn' => env('DB_DSN'),
    'database' => env('DB_DATABASE'),
],
```

Routes
-------------
- film/crawl/longest
- film/character/top/{number?}
- film/species
- film/planet/pilots
