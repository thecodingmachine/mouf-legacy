<?php
/**
 * Test for net::stubbles::ioc::stubInjector with the ImplementedBy annotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder');

/**
 * Enter description here...
 *
 * @ImplementedBy(stubInjectorImplementedByTestCase_Schst.class)
 */
interface stubInjectorImplementedByTestCase_Person {
    public function sayHello();
}

class stubInjectorImplementedByTestCase_Schst implements stubInjectorImplementedByTestCase_Person {
    public function sayHello() {
        return "My name is schst.";
    }
}

class stubInjectorImplementedByTestCase_Mikey implements stubInjectorImplementedByTestCase_Person {
    public function sayHello() {
        return "My name is mikey.";
    }
}

/**
 * Test for net::stubbles::ioc::stubInjector with the ImplementedBy annotation.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubInjectorImplementedByTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Test the default binding
     *
     * @test
     */
    public function defaultImplementation()
    {
        $binder   = new stubBinder();
        $injector = $binder->getInjector();
        $person   = $injector->getInstance('stubInjectorImplementedByTestCase_Person');
        $this->assertType('stubInjectorImplementedByTestCase_Schst', $person);
    }

    /**
     * Test overriding the default binding
     *
     * @test
     */
    public function override()
    {
        $binder = new stubBinder();
        $binder->bind('stubInjectorImplementedByTestCase_Person')->to('stubInjectorImplementedByTestCase_Mikey');
        $injector = $binder->getInjector();
        $person   = $injector->getInstance('stubInjectorImplementedByTestCase_Person');
        $this->assertType('stubInjectorImplementedByTestCase_Mikey', $person);
    }
}
?>