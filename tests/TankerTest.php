<?php

require_once dirname(__FILE__) .'/../src/Tanker.php';
$tanker = new \Tanker\Tanker();

class A {

    public static $count = 0;

    public $test = 'Test property';

    public function __construct() {
        self::$count++;
    }

    public function test() {
        return 'Test method';
    }
}

class B {

    public static $count = 0;

    public $test = 'Test property';

    public $a;

    public function __construct(\A $a) {
        self::$count++;
        $this->a = $a;
    }


    public function test() {
        return 'Test method';
    }
}

class TankerTest {

    public function testScalarElement() {
        $tanker = new \Tanker\Tanker();
        $tanker->set('five', 5);
        $tanker->set('string', 'Test string');

        return $tanker->get('five') === 5 && $tanker->get('string') === 'Test string';
    }

    public function testObjectElement() {
        $tanker = new \Tanker\Tanker();
        $a1 = new A;
        $a2 = new A;
        $tanker->set('a1', $a1);
        $tanker->set('a2', $a2);

        return $tanker->get('a1') === $a1 && $tanker->get('a2') === $a2;
    }

    public function testPropertyOverloadAccess() {
        $tanker = new \Tanker\Tanker();
        $a = new A;
        $tanker->a = $a;

        return $tanker->a === $a;
    }

    public function testArrayAccess() {
        $tanker = new \Tanker\Tanker();
        $a = new A;
        $tanker['a'] = $a;

        return $tanker['a'] === $a;
    }

    public function testServiceMethodAccess() {
        $tanker = new \Tanker\Tanker();
        $a = new A;
        $tanker->set('a', $a);

        return $tanker->get('a') === $a;
    }

    public function testClosureElement() {
        $tanker = new \Tanker\Tanker();
        $a = new A;
        $tanker->set('a', function(){
            return new A;
        });

        return $tanker->get('a') == $a;
    }

    public function testSingletonDeclaration() {
        $tanker = new \Tanker\Tanker();
        \A::$count = 0;
        $tanker->set('a1', function(){
            return new A;
        });
        $tanker->set('a2', function(){
            return new A;
        });

        return \A::$count === 0;
    }

    public function testSingletonMatchingObjects() {
        $tanker = new \Tanker\Tanker();
        $tanker->set('a', function(){
            return new A;
        });

        return $tanker->get('a')->test === 'Test property' && $tanker->get('a')->test() === 'Test method';
    }

    public function testDependencyInjectionDeclaration() {
        $tanker = new \Tanker\Tanker();
        \A::$count = 0;
        $tanker->set('a1', function(){
            return new A;
        });
        $tanker->set('b1', function() use($tanker) {
            return new B($tanker->get('a1'));
        });
        $tanker->set('a2', function(){
            return new A;
        });
        $tanker->set('b2', function() use($tanker) {
            return new B($tanker->get('a2'));
        });

        return \A::$count === 0 && \B::$count === 0;
    }

    public function testDependencyInjectionAccess() {
        $tanker = new \Tanker\Tanker();
        \A::$count = 0;
        $tanker->set('a', function(){
            return new A;
        });
        $tanker->set('b', function() use($tanker) {
            return new B($tanker->get('a'));
        });
        $tanker->get('b');
        $tanker->get('a');
        $tanker->get('b')->test;
        $tanker->get('a')->test;
        $tanker->get('b')->test();
        $tanker->get('a')->test();

        return \A::$count === 1 && \B::$count === 1;
    }

    public function testIssetElement() {
        $tanker = new \Tanker\Tanker();
        $tanker->set('a', function(){
            return new A;
        });

        return $tanker->exists('a') === TRUE && $tanker->exists('b') === FALSE;
    }

    public function testUnsetElement() {
        $tanker = new \Tanker\Tanker();
        $tanker->set('a', function(){
            return new A;
        });

        $added = $tanker->exists('a');
        $tanker->remove('a');
        $removed = $tanker->exists('a') ? FALSE : TRUE;

        return $added === TRUE && $removed === TRUE;
    }

    public function testDeclaredkeys() {
        $tanker = new \Tanker\Tanker();
        $tanker->set('a', function(){
            return new A;
        });
        $tanker->set('b', function(){
            return new B(new A);
        });

        return $tanker->declaredKeys() === array('tanker', 'object_container', 'a', 'b');
    }

    public function testDeclaredkey() {
        $tanker = new \Tanker\Tanker();
        $a = new A;
        $tanker->set('a', function(){
            return new A;
        });
        $tanker->container = 'use reserved word';

        return $tanker->get('a') == $a;
    }
}


$test = new TankerTest();
$methods = get_class_methods($test);
print '<table>';
foreach ($methods as $method) {
    print '<tr>';
    print '<td>'. $method .'</td>';
    print '<td>'. ($test->$method() ? '<span style="color: green">PASSED</span>' : '<span style="color: red">FAILED</span>') .'</td>';
    print '</tr>';
}
print '</table>';
