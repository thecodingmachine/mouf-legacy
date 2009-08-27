<?php
/**
 * Test for net::stubbles::websites::variantmanager::types::stubAbstractVariant.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_types_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::types::stubAbstractVariant',
                      'net::stubbles::websites::variantmanager::types::stubDummyVariant',
                      'net::stubbles::websites::variantmanager::types::stubRootVariant'
);
class stubTestVariant extends stubAbstractVariant
{
    protected $enforcing = false;
    protected $valid     = false;
    
    public function setEnforcing($enforcing)
    {
        $this->enforcing = $enforcing;
    }
    
    public function isEnforcing(stubSession $session, stubRequest $request)
    {
        return $this->enforcing;
    }
    
    public function setValid($valid)
    {
        $this->valid = $valid;
    }
    public function isValid(stubSession $session, stubRequest $request)
    {
        return $this->valid;
    }
}
/**
 * Test for net::stubbles::websites::variantmanager::types::stubAbstractVariant.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_types_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubAbstractVariantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * the instance to test
     *
     * @var  stubTestVariant
     */
    protected $abstractVariant;
    /**
     * the mocked session
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * the mocked request
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->abstractVariant = new stubTestVariant();
        $this->mockSession     = $this->getMock('stubSession');
        $this->mockRequest     = $this->getMock('stubRequest');
    }

    /**
     * test that setting the name works as expected
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('', $this->abstractVariant->getName());
        $this->assertEquals('', $this->abstractVariant->getFullQualifiedName());
        $this->abstractVariant->setName('foo');
        $this->assertEquals('foo', $this->abstractVariant->getName());
        $this->assertEquals('foo', $this->abstractVariant->getFullQualifiedName());
    }

    /**
     * test that setting the name works as expected
     *
     * @test
     * @expectedException  stubVariantConfigurationException
     */
    public function tooLongNameThrowsException()
    {
        $this->abstractVariant->setName('foobarbazfoobarbaz');
    }

    /**
     * test that setting the title works as expected
     *
     * @test
     */
    public function title()
    {
        $this->assertEquals('', $this->abstractVariant->gettitle());
        $this->abstractVariant->setTitle('foo');
        $this->assertEquals('foo', $this->abstractVariant->gettitle());
    }

    /**
     * test that setting the alias works as expected
     *
     * @test
     */
    public function alias()
    {
        $this->assertEquals('', $this->abstractVariant->getAlias());
        $this->abstractVariant->setAlias('foo');
        $this->assertEquals('foo', $this->abstractVariant->getAlias());
    }

    /**
     * test a non valid and non enforcing variant
     *
     * @test
     */
    public function nonValidNonEnforcing()
    {
        $this->abstractVariant->setEnforcing(false);
        $this->abstractVariant->setValid(false);
        $this->assertNull($this->abstractVariant->getEnforcingVariant($this->mockSession, $this->mockRequest));
        $this->assertNull($this->abstractVariant->getVariant($this->mockSession, $this->mockRequest));
    }

    /**
     * test a variant that has no childs
     *
     * @test
     */
    public function withoutChilds()
    {
        $this->abstractVariant->setEnforcing(true);
        $this->abstractVariant->setValid(true);
        $this->assertEquals(array(), $this->abstractVariant->getChildren());
        $variant = $this->abstractVariant->getEnforcingVariant($this->mockSession, $this->mockRequest);
        $this->assertSame($this->abstractVariant, $variant);
        $variant = $this->abstractVariant->getVariant($this->mockSession, $this->mockRequest);
        $this->assertSame($this->abstractVariant, $variant);
    }

    /**
     * test a variant that has non-valid childs
     *
     * @test
     */
    public function withNonValidChilds()
    {
        $this->abstractVariant->setEnforcing(true);
        $this->abstractVariant->setValid(true);
        $child1 = $this->getMock('stubVariant');
        $child1->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $child1->expects($this->once())->method('getEnforcingVariant')->will($this->returnValue(null));
        $child1->expects($this->once())->method('getVariant', null);
        $child1->expects($this->once())->method('setParent');
        $this->abstractVariant->addChild($child1);
        $child2 = $this->getMock('stubVariant');
        $child2->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $child2->expects($this->once())->method('getEnforcingVariant')->will($this->returnValue(null));
        $child2->expects($this->once())->method('getVariant', null);
        $child2->expects($this->once())->method('setParent');
        $this->abstractVariant->addChild($child2);
        $this->assertEquals(array('foo' => $child1, 'bar' => $child2), $this->abstractVariant->getChildren());
        $variant = $this->abstractVariant->getEnforcingVariant($this->mockSession, $this->mockRequest);
        $this->assertSame($this->abstractVariant, $variant);
        $variant = $this->abstractVariant->getVariant($this->mockSession, $this->mockRequest);
        $this->assertSame($this->abstractVariant, $variant);
    }

    /**
     * test with a valid child
     *
     * @test
     */
    public function withValidChilds()
    {
        $this->abstractVariant->setEnforcing(true);
        $this->abstractVariant->setValid(true);
        $child1 = $this->getMock('stubVariant');
        $child1->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $child1->expects($this->once())->method('getEnforcingVariant')->will($this->returnValue($child1));
        $child1->expects($this->once())->method('getVariant')->will($this->returnValue($child1));
        $child1->expects($this->once())->method('setParent');
        $this->abstractVariant->addChild($child1);
        $this->assertEquals(array('foo' => $child1), $this->abstractVariant->getChildren());
        $variant = $this->abstractVariant->getEnforcingVariant($this->mockSession, $this->mockRequest);
        $this->assertSame($child1, $variant);
        $variant = $this->abstractVariant->getVariant($this->mockSession, $this->mockRequest);
        $this->assertSame($child1, $variant);
    }

    /**
     * test that removing a child works as expected
     *
     * @test
     */
    public function removeChild()
    {
        $this->abstractVariant->setEnforcing(true);
        $this->abstractVariant->setValid(true);
        $child1 = $this->getMock('stubVariant');
        $child1->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $child1->expects($this->exactly(2))->method('setParent');
        $this->abstractVariant->addChild($child1);
        $child2 = $this->getMock('stubVariant');
        $child2->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $child2->expects($this->once())->method('setParent');
        $this->abstractVariant->addChild($child2);
        $this->assertEquals(array('foo' => $child1, 'bar' => $child2), $this->abstractVariant->getChildren());
        $this->abstractVariant->removeChild($child1);
        $this->assertEquals(array('bar' => $child2), $this->abstractVariant->getChildren());
    }

    /**
     * assure that a variant can not add itself as its own child
     *
     * @test
     * @expectedException  stubVariantConfigurationException
     */
    public function addItself()
    {
        $this->abstractVariant->addChild($this->abstractVariant);
    }

    /**
     * test parent
     *
     * @test
     */
    public function addParent()
    {
        $this->assertFalse($this->abstractVariant->hasParent());
        $this->assertNull($this->abstractVariant->getParent());
        $this->assertFalse($this->abstractVariant->assign($this->mockSession, $this->mockRequest));
        $parent = $this->getMock('stubVariant');
        $this->abstractVariant->setParent($parent);
        $this->assertTrue($this->abstractVariant->hasParent());
        $testParent = $this->abstractVariant->getParent();
        $this->assertSame($parent, $testParent);
        $parent->expects($this->once())->method('assign');
        $this->assertTrue($this->abstractVariant->assign($this->mockSession, $this->mockRequest));
        $this->abstractVariant->setName('bar');
        $parent->expects($this->once())->method('getFullQualifiedName')->will($this->returnValue('foo'));
        $this->assertEquals('foo:bar', $this->abstractVariant->getFullQualifiedName());
    }

    /**
     * test full qualified name when parent variant is root variant
     *
     * @test
     */
    public function parentRoot()
    {
        $this->abstractVariant->setName('bar');
        $rootVariant = new stubRootVariant();
        $this->abstractVariant->setParent($rootVariant);
        $this->assertEquals('bar', $this->abstractVariant->getFullQualifiedName());
    }

    /**
     * test __sleep() and __wakeup()
     *
     * @test
     */
    public function sleepWakeup()
    {
        $this->abstractVariant->setName('foo');
        $dummy1 = new stubDummyVariant();
        $dummy1->setName('bar');
        $this->abstractVariant->addChild($dummy1);
        $dummy2 = new stubDummyVariant();
        $dummy2->setName('baz');
        $dummy1->addChild($dummy2);
        
        $serialized = serialize($this->abstractVariant);
        $abstractVariant = unserialize($serialized);
        $this->assertFalse($abstractVariant->hasParent());
        $children1 = $abstractVariant->getChildren();
        $this->assertEquals('bar', $children1['bar']->getName());
        $this->assertEquals('foo', $children1['bar']->getParent()->getName());
        $children2 = $children1['bar']->getChildren();
        $this->assertEquals('baz', $children2['baz']->getName());
        $this->assertEquals('bar', $children2['baz']->getParent()->getName());
    }
}
?>