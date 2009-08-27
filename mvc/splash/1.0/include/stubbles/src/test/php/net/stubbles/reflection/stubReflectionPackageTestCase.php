<?php
/**
 * Test for net::stubbles::reflection::stubReflectionPackage.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection_test
 */
stubClassLoader::load('net::stubbles::reflection::stubReflectionPackage');
/**
 * Test for net::stubbles::reflection::stubReflectionPackage.
 *
 * @package     stubbles
 * @subpackage  reflection_test
 * @group       reflection
 */
class stubReflectionPackageTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubReflectionPackage
     */
    protected $stubRefPackage;
    
    /**
     * set up the test environment
     */
    public function setUp()
    {
        $this->stubRefPackage = new stubReflectionPackage('org::stubbles::test');
    }
    
    /**
     * assure that instances of stubReflectionPackage for the same package are equal
     *
     * @test
     */
    public function equals()
    {
        $stubRefPackage1 = new stubReflectionPackage('org::stubbles::test');
        $this->assertTrue($this->stubRefPackage->equals($stubRefPackage1));
        $this->assertTrue($stubRefPackage1->equals($this->stubRefPackage));
        $stubRefPackage2 = new stubReflectionPackage('foo');
        $this->assertFalse($this->stubRefPackage->equals($stubRefPackage2));
        $this->assertFalse($stubRefPackage2->equals($this->stubRefPackage));
        $this->assertFalse($stubRefPackage1->equals('not equal'));
        $this->assertFalse($stubRefPackage2->equals('not equal'));
    }
    
    /**
     * test behaviour if casted to string
     *
     * @test
     */
    public function toString()
    {
        $this->assertEquals("net::stubbles::reflection::stubReflectionPackage[org::stubbles::test] {\n}\n", (string) $this->stubRefPackage);
    }

    /**
     * test that the correct name is returned
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('org::stubbles::test', $this->stubRefPackage->getName());
    }

    /**
     * assert that class detection works as expected
     *
     * @test
     */
    public function hasClass()
    {
        $this->assertTrue($this->stubRefPackage->hasClass('FooProcessor'));
        $this->assertFalse($this->stubRefPackage->hasClass('NonExisting'));
    }

    /**
     * assert that the correct class reflection is used
     *
     * @test
     */
    public function getClass()
    {
        $refClass = $this->stubRefPackage->getClass('FooProcessor');
        $this->assertType('stubReflectionClass', $refClass);
        $this->assertEquals('org::stubbles::test::FooProcessor', $refClass->getFullQualifiedClassName());
    }

    /**
     * assert that all classes are returned
     *
     * @test
     */
    public function getClasses()
    {
        $stubRefPackage = new stubReflectionPackage('org::stubbles::test::package1');
        $this->assertEquals(2, count($stubRefPackage->getClasses(false)));
        $this->assertEquals(3, count($stubRefPackage->getClasses(true)));
        
        $stubRefPackage = new stubReflectionPackage('org::stubbles::test::package2');
        $this->assertEquals(0, count($stubRefPackage->getClasses(false)));
        $this->assertEquals(0, count($stubRefPackage->getClasses(true)));
    }

    /**
     * assert that all classes are returned
     *
     * @test
     */
    public function getClassNames()
    {
        $stubRefPackage = new stubReflectionPackage('org::stubbles::test::package1');
        $this->assertEquals(2, count($stubRefPackage->getClassNames(false)));
        $this->assertEquals(3, count($stubRefPackage->getClassNames(true)));
        
        $stubRefPackage = new stubReflectionPackage('org::stubbles::test::package2');
        $this->assertEquals(0, count($stubRefPackage->getClassNames(false)));
        $this->assertEquals(0, count($stubRefPackage->getClassNames(true)));
    }

    /**
     * test hasPackage()
     *
     * @test
     */
    public function hasPackage()
    {
        $this->assertTrue($this->stubRefPackage->hasPackage('package1'));
        $this->assertFalse($this->stubRefPackage->hasPackage('nonexisting'));
    }

    /**
     * assert that getPackage() delivers correct package reflection instance
     *
     * @test
     */
    public function getPackage()
    {
        $refPackage = $this->stubRefPackage->getPackage('package2');
        $this->assertType('stubReflectionPackage', $refPackage);
        $this->assertEquals('org::stubbles::test::package2', $refPackage->getName());
    }

    /**
     * assert that the correct package names are returned
     *
     * @test
     */
    public function getPackageNames()
    {
        $packageNames = $this->stubRefPackage->getPackageNames(false);
        $this->assertEquals(array('package1'), $packageNames);
        $packageNames = $this->stubRefPackage->getPackageNames(true);
        $this->assertEquals(array('package1', 'package1::subpackage'), $packageNames);
    }

    /**
     * assert that the correct package names are returned
     *
     * @test
     */
    public function getPackages()
    {
        $this->assertEquals(1, count($this->stubRefPackage->getPackages(false)));
        $this->assertEquals(2, count($this->stubRefPackage->getPackages(true)));
    }
}
?>