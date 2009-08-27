<?php
/**
 * Tests for net::stubbles::websites::xml::skin::stubCachingSkinGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin_test
 */
stubClassLoader::load('net::stubbles::websites::xml::skin::stubCachingSkinGenerator');
/**
 * Tests for net::stubbles::websites::xml::skin::stubCachingSkinGenerator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin_test
 * @group       websites
 * @group       websites_xml
 */
class stubCachingSkinGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubCachingSkinGenerator
     */
    protected $cachingSkinGenerator;
    /**
     * mocked decorated skin generator instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSkinGenerator;
    /**
     * mocked cache container instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockCacheContainer;
    /**
     * mocked session instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * page instance
     *
     * @var  stubPage
     */
    protected $page;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockSkinGenerator    = $this->getMock('stubSkinGenerator');
        $this->mockCacheContainer   = $this->getMock('stubCacheContainer');
        $this->cachingSkinGenerator = new stubCachingSkinGenerator($this->mockSkinGenerator, $this->mockCacheContainer);
        $this->mockSession          = $this->getMock('stubSession');
        $this->page                 = new stubPage();
    }

    /**
     * skin checked should be passed thru
     *
     * @test
     */
    public function hasSkin()
    {
        $this->mockSkinGenerator->expects($this->once())
                                ->method('hasSkin')
                                ->with($this->equalTo('foo'))
                                ->will($this->returnValue(true));
        $this->assertTrue($this->cachingSkinGenerator->hasSkin('foo'));
    }

    /**
     * skin key should be passed thru as is
     *
     * @test
     */
    public function skinKey()
    {
        $this->mockSkinGenerator->expects($this->once())
                                ->method('getSkinKey')
                                ->with($this->equalTo($this->mockSession), $this->equalTo($this->page), $this->equalTo('bar'))
                                ->will($this->returnValue('foo'));
        $this->assertEquals('foo', $this->cachingSkinGenerator->getSkinKey($this->mockSession, $this->page, 'bar'));
    }

    /**
     * cached skin will not be regenerated
     *
     * @test
     */
    public function cached()
    {
        $this->mockSkinGenerator->expects($this->once())
                                ->method('getSkinKey')
                                ->with($this->equalTo($this->mockSession), $this->equalTo($this->page), $this->equalTo('bar'))
                                ->will($this->returnValue('foo'));
        $this->mockSkinGenerator->expects($this->never())
                                ->method('generate');
        $this->mockCacheContainer->expects($this->once())
                                 ->method('has')
                                 ->with($this->equalTo('foo'))
                                 ->will($this->returnValue(true));
        $this->mockCacheContainer->expects($this->once())
                                 ->method('get')
                                 ->with($this->equalTo('foo'))
                                 ->will($this->returnValue('<?xml version="1.0" encoding="iso-8859-1"?><foo>bar</foo>'));
        $this->mockCacheContainer->expects($this->never())
                                 ->method('put');
        $result = $this->cachingSkinGenerator->generate($this->mockSession, $this->page, 'bar');
        $this->assertType('DOMDocument', $result);
        $this->assertEquals('<?xml version="1.0" encoding="iso-8859-1"?>' . "\n<foo>bar</foo>\n", $result->saveXML());
    }

    /**
     * non-cached skin has to be created
     *
     * @test
     */
    public function nonCached()
    {
        $result = new DOMDocument();
        $result->loadXML('<?xml version="1.0" encoding="iso-8859-1"?><foo>bar</foo>');
        $this->mockSkinGenerator->expects($this->once())
                                ->method('getSkinKey')
                                ->with($this->equalTo($this->mockSession), $this->equalTo($this->page), $this->equalTo('bar'))
                                ->will($this->returnValue('foo'));
        $this->mockSkinGenerator->expects($this->once())
                                ->method('generate')
                                ->with($this->equalTo($this->mockSession), $this->equalTo($this->page), $this->equalTo('bar'))
                                ->will($this->returnValue($result));
        $this->mockCacheContainer->expects($this->once())
                                 ->method('has')
                                 ->with($this->equalTo('foo'))
                                 ->will($this->returnValue(false));
        $this->mockCacheContainer->expects($this->never())
                                 ->method('get');
        $this->mockCacheContainer->expects($this->once())
                                 ->method('put')
                                 ->with($this->equalTo('foo'), $this->equalTo('<?xml version="1.0" encoding="iso-8859-1"?>' . "\n<foo>bar</foo>\n"));
        $this->assertSame($result, $this->cachingSkinGenerator->generate($this->mockSession, $this->page, 'bar'));
    }
}
?>