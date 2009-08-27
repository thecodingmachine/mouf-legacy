<?php
/**
 * Tests for net::stubbles::websites::xml::skin::stubSkinGeneratorFactory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin_test
 */
stubClassLoader::load('net::stubbles::websites::xml::skin::stubSkinGeneratorFactory');
/**
 * Tests for net::stubbles::websites::xml::skin::stubSkinGeneratorFactory.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin_test
 * @group       websites
 * @group       websites_xml
 */
class stubSkinGeneratorFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubSkinGeneratorFactory
     */
    protected $skinGeneratorFactory;
    /**
     * mocked cache container instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockCacheContainer;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockCacheContainer   = $this->getMock('stubCacheContainer');
        $this->mockCacheContainer->expects($this->any())->method('getId')->will($this->returnValue('skin'));
        stubCache::addContainer($this->mockCacheContainer);
        $this->skinGeneratorFactory = new stubSkinGeneratorFactory();
    }

    /**
     * prod mode results in caching skin generator
     *
     * @test
     */
    public function prodMode()
    {
        stubMode::setCurrent(stubMode::$PROD);
        $skinGenerator = $this->skinGeneratorFactory->create();
        $this->assertType('stubCachingSkinGenerator', $skinGenerator);
    }

    /**
     * test mode results in caching skin generator
     *
     * @test
     */
    public function testMode()
    {
        stubMode::setCurrent(stubMode::$TEST);
        $skinGenerator = $this->skinGeneratorFactory->create();
        $this->assertType('stubCachingSkinGenerator', $skinGenerator);
    }

    /**
     * stage mode results in default skin generator
     *
     * @test
     */
    public function stageMode()
    {
        stubMode::setCurrent(stubMode::$STAGE);
        $skinGenerator = $this->skinGeneratorFactory->create();
        $this->assertType('stubDefaultSkinGenerator', $skinGenerator);
    }

    /**
     * dev mode results in default skin generator
     *
     * @test
     */
    public function devMode()
    {
        stubMode::setCurrent(stubMode::$DEV);
        $skinGenerator = $this->skinGeneratorFactory->create();
        $this->assertType('stubDefaultSkinGenerator', $skinGenerator);
    }
}
?>