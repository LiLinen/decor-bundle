# Decor bundle

[![Build Status](https://travis-ci.org/LiLinen/decor-bundle.svg?branch=master)](https://travis-ci.org/LiLinen/decor-bundle)

Symfony bundle for LiLinen/Decor library

## Installation

`composer require lilinen/decor-bundle`

Register the bundle:
```php
<?php
// config/bundles.php

return [
    // ...
    LiLinen\DecorBundle\LiLinenDecorBundle::class => ['all' => true],
];
```

## Usage

### Decorating a service

```php
<?php
// src/Service/MyService.php

namespace App\Service;

use App\My\Annotions\MyCustomAnnotation;

class MyService
{
    /**
     * @MyCustomAnnotation 
     */
    public function foo()
    {
        // ...
    }
}
```

To automatically decorate the service with the `decorated` tag:
```yaml
# config/services.yml
services:
    App\Service\MyService:
        tags:
            - { name: decorated }
```

Alternatively, the factory can be registered manually:
```yaml
# config/services.yml
services:
    App\Service\MyService:
        factory: 'app.decor.factory.my_service:create'
        
      app.decor.factory.my_service:
          parent: 'lilinen_decor.factory'
          autowire: true
          autoconfigure: false
          public: false
          arguments:
              $class: 'App\Service\MyService'
```


### Registering a decorator service

Services with the `lilinen_decor.decorator` tag are automatically registered. See [DecoratorPass](https://github.com/LiLinen/decor-bundle/blob/master/src/DependencyInjection/Compiler/DecoratorPass.php) for implementation details.

For example, if you have a custom Decorator:
```php
<?php
// src/Decorator/MyCustomDecorator.php

namespace App\Decorator;

use LiLinen\Decor\Decorator\DecoratorInterface

class MyCustomDecorator implements DecoratorInterface
{
    //...
}
```

```yaml
# config/services.yml

services:
    App\My\Decorator\MyCustomDecorator:
        tags:
            - { name: lilinen_decor.decorator }
```

## Related Projects

* [decor](https://github.com/LiLinen/decor)
* [decor-example](https://github.com/LiLinen/decor-example)
