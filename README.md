# laravel-repository-gateway-maker

Repositories and Gateways are cool, but we are sometimes too lazy to set them up.

This package solves the problem, it handles automatic initialization and creating new Repositories & Gateways for you!

#Instructions
Install with composer:
```
"nch7/laravel-repository-gateway-maker" : "dev-master"
```

Add package service provider to app.php file:
```
Nch7\LaravelRepositoryGatewayMaker\LaravelRepositoryGatewayMakerServiceProvider
```

Add psr-4 autoloading:
```
"psr-4" : {
  "acme\\": "app/acme/"
}
```

Add repository service provider to app.php file:
```
acme\Repositories\RepositoryServiceProvider
```

Initialize:
```
php artisan repogate:init
```

Create Gateway and Repository for specific model:
```
php artisan repogate:make User
```


#Examples
```php
<?php

use acme\Gateways\UserGateway;

class UsersController extends BaseController {

	public function __construct(UserGateway $users) {
		$this->users = $users;
	}

	public function index()
	{
		return $this->users->all();
	}

}

```
