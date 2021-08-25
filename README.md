### Paddle Coding challenge - Simple Product CRUD

## Test specification
[Link to the test specification](https://github.com/felipedecampos/paddle-coding-challenge/tree/master/docs)

Technologies, libraries and patterns used for this project:
- php:8.0
- [Laravel 8x](https://laravel.com/docs/8.x)
- mysql:8.0
- TDD (Unit and Feature test)

## Environment

First you will need to set up the environment

Go to the **project folder** and run:

```shell
$ cp .env.example .env
```

Then, you need to install the application dependencies and create the containers with sail package

Go to the **project folder** and run:

**Warning: Make sure you have PHP 8 installed on your machine**

```shell
$ composer install

$ ./vendor/bin/sail up -d

$ ./vendor/bin/sail php artisan key:generate
```

Now let's set up the database:

Go to the **project folder** and run:

```shell
$ ./vendor/bin/sail php artisan migrate

$ ./vendor/bin/sail php artisan db:seed
```

## Testing

To test the application go to the **project folder** and run the tests:

```shell
$ ./vendor/bin/sail test
```

When the test command ends, the coverage folder will be created into [/tests/Reports/coverage](https://github.com/felipedecampos/paddle-coding-challenge/tree/master/tests/Reports/coverage)

## Postman collection

[Link to Postman collection](https://github.com/felipedecampos/paddle-coding-challenge/tree/master/docs/postman-collection/Paddle.postman_collection.json)
