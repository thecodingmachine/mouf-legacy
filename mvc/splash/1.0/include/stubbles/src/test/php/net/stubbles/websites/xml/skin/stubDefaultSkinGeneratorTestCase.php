<?php
/**
 * Tests for net::stubbles::websites::xml::skin::stubDefaultSkinGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_skin_test
 * @version     $Id: stubDefaultSkinGeneratorTestCase.php 1904 2008-10-25 14:04:33Z mikey $
 */
stubClassLoader::load('net::stubbles::websites::xml::skin::stubDefaultSkinGenerator');
/**
 * Tests for net::stubbles::websites::xml::skin::stubDefaultSkinGenerator.
 *
 * @package     stubbles
 * @subpackage  websites_xml_skin_test
 * @group       websites
 * @group       websites_xml
 */
class stubDefaultSkinGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubDefaultSkinGenerator
     */
    protected $skinGenerator;
    /**
     * the page instance
     *
     * @var  stubPage
     */
    protected $page;
    /**
     * mocked session instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * mocked xsl processor
     *
     * @var  stubXSLProcessor
     */
    protected $mockXSLProcessor;
    /**
     * generated skin dom document
     *
     * @var  DOMDocument
     */
    protected $resultDomDocument;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->skinGenerator     = $this->getMock('stubDefaultSkinGenerator',
                                                  array('createXSLProcessor',
                                                        'createXMLSkinDocument'
                                                  )
                                   );
        $this->mockSession       = $this->getMock('stubSession');
        $this->page              = new stubPage();
        $this->mockXSLProcessor  = $this->getMock('stubXSLProcessor',
                                                  array('toDoc')
                                   );
        $this->resultDomDocument = new DOMDocument();
        $this->resultDomDocument->createElement('bar', 'foo');
        $this->mockXSLProcessor->expects($this->any())->method('toDoc')->will($this->returnValue($this->resultDomDocument));
        $this->skinGenerator->expects($this->any())->method('createXSLProcessor')->will($this->returnValue($this->mockXSLProcessor));
        if (stubRegistry::hasConfig('net.stubbles.language') == true) {
            stubRegistry::setConfig('net.stubbles.language', null);
        }
    }

    /**
     * clear test environment
     */
    public function tearDown()
    {
        stubRegistry::removeConfig('net.stubbles.language');
        stubRegistry::removeConfig('net.stubbles.websites.xml.skin.common');
    }

    /**
     * check whether skin exists should work as expected
     *
     * @test
     */
    public function hasSkin()
    {
        $this->assertTrue($this->skinGenerator->hasSkin('default'));
        $this->assertFalse($this->skinGenerator->hasSkin('foo'));
    }

    /**
     * assure that another skin will be used
     *
     * @test
     */
    public function skin()
    {
        $this->page->setProperty('name', 'foo');
        $this->skinGenerator->expects($this->once())
                            ->method('createXMLSkinDocument')
                            ->with($this->equalTo('another'))
                            ->will($this->returnValue($this->getMock('DOMDocument')));
        $this->assertSame($this->resultDomDocument, $this->skinGenerator->generate($this->mockSession, $this->page, 'another'));
        $this->assertEquals(md5('anotherfooen_EN'), $this->skinGenerator->getSkinKey($this->mockSession, $this->page, 'another'));
    }

    /**
     * assure that the language value from the registry is taken
     *
     * @test
     */
    public function registryLanguage()
    {
        stubRegistry::setConfig('net.stubbles.language', 'foo_foo');
        $this->page->setProperty('name', 'bar');
        $this->skinGenerator->expects($this->any())
                            ->method('createXMLSkinDocument')
                            ->will($this->returnValue($this->getMock('DOMDocument')));
        $this->assertSame($this->resultDomDocument, $this->skinGenerator->generate($this->mockSession, $this->page, 'default'));
        $this->assertEquals(md5('defaultbarfoo_foo'), $this->skinGenerator->getSkinKey($this->mockSession, $this->page, 'default'));
        $this->assertEquals(array('page'      => 'bar',
                                  'lang'      => 'foo_foo',
                                  'lang_base' => 'foo_*'
                            ),
                            $this->mockXSLProcessor->getParameters('')
        );
    }

    /**
     * assure that the language value from the page is taken
     *
     * @test
     */
    public function pageLanguage()
    {
        $this->page->setProperty('language', 'bar_bar');
        $this->page->setProperty('name', 'baz');
        stubRegistry::setConfig('net.stubbles.language', 'foo_foo');
        $this->skinGenerator->expects($this->any())
                            ->method('createXMLSkinDocument')
                            ->will($this->returnValue($this->getMock('DOMDocument')));
        $this->assertSame($this->resultDomDocument, $this->skinGenerator->generate($this->mockSession, $this->page, 'default'));
        $this->assertEquals(md5('defaultbazbar_bar'), $this->skinGenerator->getSkinKey($this->mockSession, $this->page, 'default'));
        $this->assertEquals(array('page'      => 'baz',
                                  'lang'      => 'bar_bar',
                                  'lang_base' => 'bar_*'
                            ),
                            $this->mockXSLProcessor->getParameters('')
        );
    }

    /**
     * assure that the language value from the session is taken
     *
     * @test
     */
    public function sessionLanguage()
    {
        $this->skinGenerator->expects($this->any())
                            ->method('createXMLSkinDocument')
                            ->will($this->returnValue($this->getMock('DOMDocument')));
        $this->mockSession->expects($this->exactly(2))->method('hasValue')->will($this->returnValue(true));
        $this->mockSession->expects($this->exactly(2))->method('getValue')->will($this->returnValue('baz_baz'));
        $this->page->setProperty('language', 'bar_bar');
        $this->page->setProperty('name', 'baz');
        stubRegistry::setConfig('net.stubbles.language', 'foo_foo');
        $this->assertSame($this->resultDomDocument, $this->skinGenerator->generate($this->mockSession, $this->page, 'default'));
        $this->assertEquals(md5('defaultbazbaz_baz'), $this->skinGenerator->getSkinKey($this->mockSession, $this->page, 'default'));
        $this->assertEquals(array('page'      => 'baz',
                                  'lang'      => 'baz_baz',
                                  'lang_base' => 'baz_*'
                            ),
                            $this->mockXSLProcessor->getParameters('')
        );
    }

    /**
     * common path should be added when stored in registry
     *
     * @test
     */
    public function commonPathIsAddedWhenEnabled()
    {
        $includePathes = stubXMLXIncludeStreamWrapper::getIncludePathes();
        $this->assertFalse(isset($includePathes['common']));
        stubRegistry::setConfig('net.stubbles.websites.xml.skin.common', 'commonPath');
        $this->page->setProperty('name', 'foo');
        $this->skinGenerator->expects($this->any())
                            ->method('createXMLSkinDocument')
                            ->with($this->equalTo('another'))
                            ->will($this->returnValue($this->getMock('DOMDocument')));
        $this->skinGenerator->generate($this->mockSession, $this->page, 'another');
        $includePathes = stubXMLXIncludeStreamWrapper::getIncludePathes();
        $this->assertTrue(isset($includePathes['common']));
        $this->assertEquals('commonPath', $includePathes['common']); 
    }
}
?>