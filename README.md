Tanker
======

Tanker is an flexible object container with singleton pattern realization

## How to include?

Include `Tanker` to your script directly in PHP code:

    require_once '/path/to/Tanker.php';

or add to `composer.json` file:

    "require": {
        "tanker/tanker": "1.0.*@dev"
    }
    
## How to import?

Import class with the `use` statement:

    use Tanker\Tanker;

## How to use?

To *create* a `Tanker` instance with `new` operator:
    
    $tanker = new Tanker;
    
To *add* object to the container:

    $obj = new StdClass(); // create some object
    $tanker->set('object', $obj); // add object to container with name using setter
    
or do the same simply with property overload:

    $tanker->object = $obj;

or do the same simply with array access:

    $tanker['object'] = $obj;
    
To *get* object from container:

    $obj = $tanker->get('object'); // get object by name from container using getter
    
or do the same simply with property overload:

    $obj = $tanker->object;
    
or do the same simply with array access:

    $obj = $tanker['object'];
    
