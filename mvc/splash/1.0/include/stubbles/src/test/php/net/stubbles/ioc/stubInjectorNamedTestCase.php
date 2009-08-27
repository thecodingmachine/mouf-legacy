<?php
/**
 * Test for net::stubbles::ioc::stubInjector with @Named annotation.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  ioc_test
 */
stubClassLoader::load('net::stubbles::ioc::stubBinder');


interface stubInjectorNamedTestCase_Person {
    public function sayHello();
}

class stubInjectorNamedTestCase_Boss implements stubInjectorNamedTestCase_Person {
    public function sayHello() {
        return "boss";
    }
}

class stubInjectorNamedTestCase_Employee implements stubInjectorNamedTestCase_Person {
    public function sayHello() {
        return "employee";
    }
}

class stubInjectorNamedTestCase_Developers {
    public $mikey;
    public $schst;

    /**
     * Setter method with Named() annotation
     *
     * @param Person $schst
     * @Inject
     * @Named('schst')
     */
    public function setSchst(stubInjectorNamedTestCase_Person $schst) {
        $this->schst = $schst;
    }

    /**
     * Setter method without Named() annotation
     *
     * @param Person $schst
     * @Inject
     */
    public function setMikey(stubInjectorNamedTestCase_Person $mikey) {
        $this->mikey = $mikey;
    }
}

/**
 * Test for net::stubbles::ioc::stubInjector with @Named annotation.
 *
 * @package     stubbles
 * @subpackage  ioc_test
 * @group       ioc
 */
class stubInjectorNamedTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test constructor injections
     *
     * @test
     */
    public function namedSetterInjection()
    {
        $binder = new stubBinder();
        $binder->bind('stubInjectorNamedTestCase_Person')->named('schst')->to('stubInjectorNamedTestCase_Boss');
        $binder->bind('stubInjectorNamedTestCase_Person')->to('stubInjectorNamedTestCase_Employee');

        $injector = $binder->getInjector();

        $this->assertTrue($injector->hasBinding('stubInjectorNamedTestCase_Person', 'schst'));
        $this->assertTrue($injector->hasBinding('stubInjectorNamedTestCase_Person'));

        $group = $injector->getInstance('stubInjectorNamedTestCase_Developers');

        $this->assertType('stubInjectorNamedTestCase_Developers', $group);
        $this->assertType('stubInjectorNamedTestCase_Person', $group->mikey);
        $this->assertType('stubInjectorNamedTestCase_Employee', $group->mikey);
        $this->assertType('stubInjectorNamedTestCase_Person', $group->schst);
        $this->assertType('stubInjectorNamedTestCase_Boss', $group->schst);
    }
}
?>