# Defer

This is a backport attept for laravel 11 defer helper.

## Features

- Postpones callback executions to after the response has been sent to a client
- Maintains the database connection used when executing deferred callback.

## Installation and Setup

Install the package within your project using,

```bash
composer require tomshoo/defer
```

Or you can add the following to your `composer.json`,

```jsonc
{
  "require": {
    "tomshoo/defer": "^1.0"
  }
}
```

Once installed you can then register the service provider, and the middleware, using the following samples.

```php
// config/app.php

'providers' => [
    // ...,
    \Tomshoo\Defer\DeferServiceProvider::class,
    // ...,
],
```

```php
// app/Http/Kernel.php

public class Kernel {
    protected $middleware = [
        \Tomshoo\Defer\Middleware\DeferCallbackExecutor::class,
        //...,
    ];
}
```
