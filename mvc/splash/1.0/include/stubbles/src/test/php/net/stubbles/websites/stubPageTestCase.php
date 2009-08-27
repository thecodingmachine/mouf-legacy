<?php
/**
 * Tests for net::stubbles::websites::stubPage.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_test
 */
stubClassLoader::load('net::stubbles::websites::stubPage');
/**
 * Tests for net::stubbles::websites::stubPage
 *
 * @package     stubbles
 * @subpackage  websites_test
 * @group       websites
 */
class stubPageTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubPage
     */
    protected $stubPage;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->stubPage = new stubPage();
    }

    /**
     * assure that properties are set and retrieved correct
     *
     * @test
     */
    public function properties()
    {
        $this->assertFalse($this->stubPage->hasProperty('foo'));
        $this->assertNull($this->stubPage->getProperty('foo'));
        $this->stubPage->setProperty('foo', 'bar');
        $this->assertTrue($this->stubPage->hasProperty('foo'));
        $this->assertEquals('bar', $this->stubPage->getProperty('foo'));
        
        $this->stubPage->setProperties(array('baz' => 'baz', 'bar' => 'foo'));
        $this->assertFalse($this->stubPage->hasProperty('foo'));
        $this->assertNull($this->stubPage->getProperty('foo'));
        $this->assertTrue($this->stubPage->hasProperty('baz'));
        $this->assertEquals('baz', $this->stubPage->getProperty('baz'));
        $this->assertTrue($this->stubPage->hasProperty('bar'));
        $this->assertEquals('foo', $this->stubPage->getProperty('bar'));
    }

    /**
     * assure that page elements are set and retrieved correct
     *
     * @test
     */
    public function pageElementsHandling()
    {
        $this->assertEquals(array(), $this->stubPage->getElements());
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())
                         ->method('getName')
                         ->will($this->returnValue('foo'));
        $this->stubPage->addElement($mockPageElement1);
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())
                         ->method('getName')
                         ->will($this->returnValue('bar'));
        $this->stubPage->addElement($mockPageElement2);
        $this->assertEquals(array('foo' => $mockPageElement1,
                                  'bar' => $mockPageElement2
                            ),
                            $this->stubPage->getElements()
        );
        
        $mockPageElement3 = $this->getMock('stubPageElement');
        $mockPageElement3->expects($this->any())
                         ->method('getName')
                         ->will($this->returnValue('foo'));
        $this->stubPage->addElement($mockPageElement3);
        $this->assertEquals(array('foo' => $mockPageElement3,
                                  'bar' => $mockPageElement2
                            ),
                            $this->stubPage->getElements()
        );
    }
}
?>