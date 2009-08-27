<?php
/**
 * Integration test for memphis view engine configuration.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  test_integration
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisConfig',
                      'net::stubbles::websites::memphis::stubMemphisTemplate'
);
/**
 * Integration test for memphis view engine configuration.
 *
 * @package     stubbles
 * @subpackage  test_integration
 * @group       integration
 */
class MemphisConfigTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * set up test environment
     */
    public function setUp()
    {
        stubRegistry::setConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, TEST_SRC_PATH . DIRECTORY_SEPARATOR . 'resources');
        if (class_exists('stubMemphisIncludeFilePageElement', false) === true) {
            stubMemphisIncludeFilePageElement::__static();
        }
        
        if (class_exists('stubMemphisIncludeTemplatePageElement', false) === true) {
            stubMemphisIncludeTemplatePageElement::__static();
        }
    }

    /**
     * assure that loading the memphis config gives correct results
     *
     * @test
     */
    public function config()
    {
        $memphisConfig = new stubMemphisConfig();
        $this->assertEquals(array('content', 'navteasers', 'teasers', 'annotations', 'header'), $memphisConfig->getParts());
        foreach ($memphisConfig->getDefaultElements('navteasers') as $defaultElement) {
            $this->assertType('stubMemphisPageElement', $defaultElement);
        }
        
        $this->assertEquals('frame/default.tmpl', $memphisConfig->getFrame('default'));
        $this->assertEquals('frame/platin.tmpl', $memphisConfig->getFrame('default_platin'));
        $this->assertEquals('frame/index.tmpl', $memphisConfig->getFrame('default_index'));
        $this->assertEquals('frame/weitere.tmpl', $memphisConfig->getFrame('weitere'));
        $this->assertEquals('frame/popup.tmpl', $memphisConfig->getFrame('popup'));
        $this->assertEquals('frame/popup_top.tmpl', $memphisConfig->getFrame('popup_top'));
        $this->assertEquals('frame/blank.tmpl', $memphisConfig->getFrame('blank'));
        $this->assertEquals('frame/empty.tmpl', $memphisConfig->getFrame('empty'));
        
        $this->assertEquals(array('description' => 'This is a description.',
                                  'keywords'    => 'keyword1, keyword2'
                            ),
                            $memphisConfig->getMetaTags()
        );
        
        // cached
        $memphisConfig = new stubMemphisConfig();
        $this->assertEquals(array('content', 'navteasers', 'teasers', 'annotations', 'header'), $memphisConfig->getParts());
        foreach ($memphisConfig->getDefaultElements('navteasers') as $defaultElement) {
            $this->assertType('stubMemphisPageElement', $defaultElement);
        }
        
        $this->assertEquals('frame/default.tmpl', $memphisConfig->getFrame('default'));
        $this->assertEquals('frame/platin.tmpl', $memphisConfig->getFrame('default_platin'));
        $this->assertEquals('frame/index.tmpl', $memphisConfig->getFrame('default_index'));
        $this->assertEquals('frame/weitere.tmpl', $memphisConfig->getFrame('weitere'));
        $this->assertEquals('frame/popup.tmpl', $memphisConfig->getFrame('popup'));
        $this->assertEquals('frame/popup_top.tmpl', $memphisConfig->getFrame('popup_top'));
        $this->assertEquals('frame/blank.tmpl', $memphisConfig->getFrame('blank'));
        $this->assertEquals('frame/empty.tmpl', $memphisConfig->getFrame('empty'));
        
        $this->assertEquals(array('description' => 'This is a description.',
                                  'keywords'    => 'keyword1, keyword2'
                            ),
                            $memphisConfig->getMetaTags()
        );
    }
}
?>