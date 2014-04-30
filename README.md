Tanker
======

Tanker is an flexible object container with singleton pattern realization

## How to include?

Include `Tanker` to your script directly in PHP code:

    require_once '/path/to/Tanker/Container.php';

or use `composer`. Add to `composer.json` file:

    "require": {
        "tanker/tanker": "1.1.*@dev"
    }
    
## How to import?

Import class with the `use` statement:

    use Tanker\Container as Tanker;

## How to use?

To **create** a `Tanker` instance with `new` operator:
    
    $tanker = new Tanker;
    
To **add** object to the container:

    $obj = new StdClass(); // create some object
    $tanker->set('object', $obj); // add object to container with name using setter
    
or do the same simply with *property overload*:

    $tanker->object = $obj;

or do the same simply with *array access*:

    $tanker['object'] = $obj;
    
To **get** object from container:

    $obj = $tanker->get('object'); // get object by name from container using getter
    
or do the same simply with *property overload*:

    $obj = $tanker->object;
    
or do the same simply with *array access*:

    $obj = $tanker['object'];
    
## How to use services?

Service is a more complex object, that works with other objects.
For better performance and resource savings you need to create it instances directly when you need to use it.
It's perfectly handled by `Tanker`, you need only to define service with anonymous function:

    $tanker->set('mailer', function(){
            return new Mailer();
        });

The instance of mailer will be create at the first call of the service:

    $tanker->get('mailer');

You simply can inject dependencies with `use` statement, passed to the anonymous function `Tanker` object:

    $tanker->set('mailer', function() use ($tanker) {
            return new Mailer($tanker->get('config'));
        });

## What's inside?

 - Project on [GitHub][1]
 - Code of Tanker [Container][2]

*It's simple, isn't it? :)*

  [1]: https://github.com/bocharsky-bw/tanker
  [2]: https://github.com/bocharsky-bw/tanker/blob/master/src/Tanker/Container.php
