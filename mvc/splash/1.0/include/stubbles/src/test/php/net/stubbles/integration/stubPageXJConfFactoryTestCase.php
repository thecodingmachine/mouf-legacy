<?php
/**
 * Integration test for xjconf page factory.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::websites::stubPageXJConfFactory');
/**
 * Integration test for xjconf page factory.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class stubPageXJConfFactoryTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that creating the page instance works correct
     *
     * @test
     */
    public function xmlPageXJConfFactory()
    {
        $pageFactory = new stubPageXJConfFactory();
        $pageFactory->setPagePrefix('conf/');
        $this->assertTrue($pageFactory->hasPage('index'));
        $this->assertFalse($pageFactory->hasPage('doesNotExist'));
        $page = $pageFactory->getPage('index');
        $this->assertEquals('default', $page->getProperty('skin'));
        $this->assertEquals(utf8_encode('Übersicht'), $page->getProperty('title'));
        $elements = $page->getElements();
        $this->assertType('TestXMLPageElement', $elements['Test']);
        $this->assertType('stubXMLPageElementCachingDecorator', $elements['cached']);
        $this->assertEquals(array('org::stubbles::examples::pageelements::CurrentTimeXMLPageElement'), $elements['cached']->getRequiredClassNames());
        $this->assertType('CurrentTimeXMLPageElement', $elements['uncached']);
        
        // cached
        $pageFactory = new stubPageXJConfFactory();
        $pageFactory->setPagePrefix('conf/');
        $this->assertTrue($pageFactory->hasPage('index'));
        $this->assertFalse($pageFactory->hasPage('doesNotExist'));
        $page = $pageFactory->getPage('index');
        $this->assertEquals('default', $page->getProperty('skin'));
        $elements = $page->getElements();
        $this->assertType('TestXMLPageElement', $elements['Test']);
        $this->assertType('stubXMLPageElementCachingDecorator', $elements['cached']);
        $this->assertEquals(array('org::stubbles::examples::pageelements::CurrentTimeXMLPageElement'), $elements['cached']->getRequiredClassNames());
        $this->assertType('CurrentTimeXMLPageElement', $elements['uncached']);
    }

    /**
     * assure that creating the page instance works correct
     *
     * @test
     */
    public function memphisPageXJConfFactory()
    {
        stubRegistry::setConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, TEST_SRC_PATH . DIRECTORY_SEPARATOR . 'resources');
        if (class_exists('stubMemphisIncludeFilePageElement', false) === true) {
            stubMemphisIncludeFilePageElement::__static();
        }
        
        $pageFactory = new stubPageXJConfFactory();
        $this->assertTrue($pageFactory->hasPage('index'));
        $this->assertFalse($pageFactory->hasPage('doesNotExist'));
        try {
        $page = $pageFactory->getPage('index');
        } catch (Exception $e) {
            echo (string) $e;
        }
        $this->assertEquals('default', $page->getProperty('skin'));
        $this->assertEquals(utf8_encode('Übersicht'), $page->getProperty('title'));
        $elements = $page->getElements();
        $this->assertType('stubMemphisIncludeFilePageElement', $elements['index']);
        
        // cached
        $pageFactory = new stubPageXJConfFactory();
        $this->assertTrue($pageFactory->hasPage('index'));
        $this->assertFalse($pageFactory->hasPage('doesNotExist'));
        $page = $pageFactory->getPage('index');
        $this->assertEquals('default', $page->getProperty('skin'));
        $elements = $page->getElements();
        $this->assertType('stubMemphisIncludeFilePageElement', $elements['index']);
    }
}
?>