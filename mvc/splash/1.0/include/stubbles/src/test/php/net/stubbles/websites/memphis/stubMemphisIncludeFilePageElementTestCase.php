<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisIncludeFilePageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisIncludeFilePageElement');
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisIncludeFilePageElement.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class stubMemphisIncludeFilePageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubMemphisIncludeFilePageElement
     */
    protected $includeFilePageElement;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->includeFilePageElement = new stubMemphisIncludeFilePageElement();
    }

    /**
     * assure that setting and getting the name of the element works as expected
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('', $this->includeFilePageElement->getName());
        $this->includeFilePageElement->setName('foo');
        $this->assertEquals('foo', $this->includeFilePageElement->getName());
    }

    /**
     * assure that setting and getting the source of the element works as expected
     *
     * @test
     */
    public function source()
    {
        $this->assertEquals('', $this->includeFilePageElement->getSource());
        $this->includeFilePageElement->setSource(TEST_SRC_PATH . '/resources/contentFile.txt');
        $this->assertEquals(TEST_SRC_PATH . '/resources/contentFile.txt', $this->includeFilePageElement->getSource());
    }

    /**
     * assure that setting and getting the source of the element works as expected
     *
     * @test
     * @expectedException  stubFileNotFoundException
     */
    public function setSourceToNonExistingFileThrowsFileNotFoundException()
    {
        $this->includeFilePageElement->setSource(TEST_SRC_PATH . '/resources/doesNotExist');
    }

    /**
     * caching methods return correct data
     *
     * @test
     */
    public function cachingMethods()
    {
        $this->assertEquals(array(), $this->includeFilePageElement->getCacheVars());
        $this->assertEquals(array(), $this->includeFilePageElement->getUsedFiles());
        $this->includeFilePageElement->setSource(TEST_SRC_PATH . '/resources/contentFile.txt');
        $this->assertEquals(array('source' => TEST_SRC_PATH . '/resources/contentFile.txt'), $this->includeFilePageElement->getCacheVars());
        $this->assertEquals(array(TEST_SRC_PATH . '/resources/contentFile.txt'), $this->includeFilePageElement->getUsedFiles());
    }

    /**
     * assure that processing works as expected
     *
     * @test
     */
    public function process()
    {
        $this->assertEquals('', $this->includeFilePageElement->process());
        $this->includeFilePageElement->setSource(TEST_SRC_PATH . '/resources/contentFile.txt');
        $this->assertEquals('This is the content.', $this->includeFilePageElement->process());
    }
}
?>