<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisIncludeTemplatePageElement.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisIncludeTemplatePageElement');
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisIncludeTemplatePageElement.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class stubMemphisIncludeTemplatePageElementTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubMemphisIncludeTemplatePageElement
     */
    protected $includeTemplatePageElement;

    /**
     * set up test environment
     */
    public function setUp()
    {
        stubRegistry::setConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, TEST_SRC_PATH);
        stubMemphisIncludeTemplatePageElement::__static();
        $this->includeTemplatePageElement = new stubMemphisIncludeTemplatePageElement();
    }

    /**
     * assure that setting and getting the name of the element works as expected
     *
     * @test
     */
    public function name()
    {
        $this->assertEquals('', $this->includeTemplatePageElement->getName());
        $this->includeTemplatePageElement->setName('foo');
        $this->assertEquals('foo', $this->includeTemplatePageElement->getName());
    }

    /**
     * assure that setting and getting the source of the element works as expected
     *
     * @test
     */
    public function source()
    {
        $this->assertEquals('', $this->includeTemplatePageElement->getSource());
        $this->includeTemplatePageElement->setSource('resources/contentFile.txt');
        $this->assertEquals('resources/contentFile.txt', $this->includeTemplatePageElement->getSource());
    }

    /**
     * assure that setting and getting the source of the element works as expected
     *
     * @test
     * @expectedException  stubFileNotFoundException
     */
    public function setSourceToNonExistingFileThrowsFileNotFoundException()
    {
        $this->includeTemplatePageElement->setSource('resources/doesNotExist');
    }

    /**
     * caching methods return correct data
     *
     * @test
     */
    public function cachingMethods()
    {
        $this->assertEquals(array(), $this->includeTemplatePageElement->getCacheVars());
        $this->assertEquals(array(), $this->includeTemplatePageElement->getUsedFiles());
        $this->includeTemplatePageElement->setSource('resources/contentFile.txt');
        $this->assertEquals(array('source' =>  'resources/contentFile.txt'), $this->includeTemplatePageElement->getCacheVars());
        $this->assertEquals(array(TEST_SRC_PATH . DIRECTORY_SEPARATOR . 'resources/contentFile.txt'), $this->includeTemplatePageElement->getUsedFiles());
    }

    /**
     * processing the page element without template engine in the context throws a runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function processWithoutTemplateContextThrowsRuntimeException()
    {
        $this->includeTemplatePageElement->process();
    }

    /**
     * processing the page element with wrong template engine in the context throws a runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function processWithWrongTemplateContextThrowsRuntimeException()
    {
        $this->includeTemplatePageElement->init($this->getMock('stubRequest'), $this->getMock('stubSession'), $this->getMock('stubResponse'), array('template' => new stdClass()));
        $this->includeTemplatePageElement->process();
    }

    /**
     * assure that processing works as expected
     *
     * @test
     */
    public function process()
    {
        $mockMemphisTemplate = $this->getMock('stubMemphisTemplate');
        $this->includeTemplatePageElement->init($this->getMock('stubRequest'), $this->getMock('stubSession'), $this->getMock('stubResponse'), array('template' => $mockMemphisTemplate));
        $this->assertEquals('', $this->includeTemplatePageElement->process());
        $this->includeTemplatePageElement->setSource('resources/contentFile.txt');
        $mockMemphisTemplate->expects($this->once())
                            ->method('readTemplatesFromInput')
                            ->with($this->equalTo('resources/contentFile.txt'));
        $mockMemphisTemplate->expects($this->once())
                            ->method('getParsedTemplate')
                            ->will($this->returnValue('This is the content.'));
        $this->assertEquals('This is the content.', $this->includeTemplatePageElement->process());
    }
}
?>