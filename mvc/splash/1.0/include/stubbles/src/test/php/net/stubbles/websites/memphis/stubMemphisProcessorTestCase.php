<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisProcessor');
require_once dirname(__FILE__) . '/stubMemphisProcessorTestHelper.php';
class TeststubDummyTemplate
{
    /**
     * list of collected template variables
     *
     * @var  array<string,array<string,scalar>>
     */
    protected $vars       = array();
    /**
     * list of collected global template variables
     *
     * @var  array<string,scalar>
     */
    protected $globalVars = array();

    /**
     * add a variable to a template
     *
     * A variable may also be an indexed array, but not an associative array!
     *
     * @param   string  $template  name of the template
     * @param   string  $varname   name of the variable
     * @param   mixed   $value     value of the variable
     * @return  bool
     */
    public function addVar($template, $varname, $value)
    {
        if (isset($this->vars[$template]) === false) {
            $this->vars[$template] = array();
        }
        
        $this->vars[$template][$varname] = $value;
    }

    /**
     * returns list of collected template variables
     *
     * @return  array<string,array<string,scalar>>
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * adds a global variable
     *
     * Global variables are valid in all templates of this object.
     * A global variable has to be scalar, it will be converted to a string.
     *
     * @param   string  $varname  name of the global variable
     * @param   string  $value    value of the variable
     * @return  bool
     */
    public function addGlobalVar($varname, $value)
    {
        $this->globalVars[$varname] = $value;
    }

    /**
     * returns list of collected global template variables
     *
     * @return  array<string,scalar>
     */
    public function getGlobalVars()
    {
        return $this->globalVars;
    }
}
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisProcessor.
 *
 * We need to test the single methods because else the unit to test becomes to
 * great.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class stubMemphisProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  stubMemphisProcessor
     */
    protected $memphisProcessor;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked session instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * mocked page factory instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPageFactory;
    /**
     * mocked page configuration instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPage;
    /**
     * mocked memphis config instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockMemphisConfig;
    /**
     * mocked memphis template instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockMemphisTemplate;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest       = $this->getMock('stubRequest');
        $this->mockSession       = $this->getMock('stubSession');
        $this->mockResponse      = $this->getMock('stubResponse');
        $this->mockPageFactory   = $this->getMock('stubPageFactory');
        $this->memphisProcessor  = new TeststubMemphisProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $this->mockMemphisConfig = $this->getMock('TeststubMemphisConfig');
        $this->memphisProcessor->setConfig($this->mockMemphisConfig);
        $this->mockMemphisTemplate = $this->getMock('stubMemphisTemplate');
        $this->memphisProcessor->setTemplate($this->mockMemphisTemplate);
        $this->mockPage = $this->getMock('stubPage');
        $this->mockPageFactory->expects($this->any())->method('getPageName')->will($this->returnValue('index'));
        $this->mockPageFactory->expects($this->any())->method('getPage')->will($this->returnValue($this->mockPage));
        $this->memphisProcessor->selectPage($this->mockPageFactory);
    }

    /**
     * test that processing of page elements works as expected
     *
     * @test
     */
    public function processPageElements()
    {
        $this->mockRequest->expects($this->any())->method('isCancelled')->will($this->returnValue(false));
        $this->mockMemphisConfig->expects($this->any())->method('getParts')->will($this->returnValue(array('content', 'teaser')));
        $mockDefaultPageElement1 = $this->getMock('stubPageElement');
        $mockDefaultPageElement1->expects($this->any())->method('getName')->will($this->returnValue('defaultMock1'));
        $mockDefaultPageElement1->expects($this->exactly(2))->method('init');
        $mockDefaultPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockDefaultPageElement1->expects($this->never())->method('process');
        $mockDefaultPageElement2 = $this->getMock('stubPageElement');
        $mockDefaultPageElement2->expects($this->any())->method('getName')->will($this->returnValue('defaultMock2'));
        $mockDefaultPageElement2->expects($this->exactly(2))->method('init');
        $mockDefaultPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->exactly(2))->method('process')->will($this->returnValue('defaultMock2'));
        $this->mockMemphisConfig->expects($this->any())->method('getDefaultElements')->will($this->returnValue(array($mockDefaultPageElement1, $mockDefaultPageElement2)));
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())->method('getName')->will($this->returnValue('Mock1'));
        $mockPageElement1->expects($this->exactly(2))->method('init');
        $mockPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockPageElement1->expects($this->never())->method('process');
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())->method('getName')->will($this->returnValue('Mock2'));
        $mockPageElement2->expects($this->exactly(2))->method('init');
        $mockPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockPageElement2->expects($this->exactly(2))->method('process')->will($this->returnValue('Mock2'));
        $this->mockMemphisTemplate->expects($this->at(1))
                                  ->method('addGlobalVar')
                                  ->with($this->equalTo('content'), $this->equalTo('defaultMock2Mock2'));
        $this->mockMemphisTemplate->expects($this->at(2))
                                  ->method('addGlobalVar')
                                  ->with($this->equalTo('teaser'), $this->equalTo('defaultMock2Mock2'));
        $this->mockMemphisTemplate->expects($this->once())->method('getParsedTemplate')->will($this->returnValue('defaultMock2Mock2defaultMock2Mock2'));
        $this->mockResponse->expects($this->once())->method('write')->with($this->equalTo('defaultMock2Mock2defaultMock2Mock2'));
        $this->mockPage->expects($this->any())->method('getElements')->will($this->returnValue(array('mock1' => $mockPageElement1, 'mock2' => $mockPageElement2)));
        $this->memphisProcessor->process();
    }

    /**
     * test that processing of page elements works as expected
     *
     * @test
     */
    public function processPageElementsWithRequestCancelledByPageElement()
    {
        // just two, as the first default element is not available
        $this->mockRequest->expects($this->any())->method('isCancelled')->will($this->onConsecutiveCalls(false, true));
        $this->mockMemphisConfig->expects($this->any())->method('getParts')->will($this->returnValue(array('content', 'teaser')));
        $mockDefaultPageElement1 = $this->getMock('stubPageElement');
        $mockDefaultPageElement1->expects($this->any())->method('getName')->will($this->returnValue('defaultMock1'));
        $mockDefaultPageElement1->expects($this->once())->method('init');
        $mockDefaultPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockDefaultPageElement1->expects($this->never())->method('process');
        $mockDefaultPageElement2 = $this->getMock('stubPageElement');
        $mockDefaultPageElement2->expects($this->any())->method('getName')->will($this->returnValue('defaultMock2'));
        $mockDefaultPageElement2->expects($this->once())->method('init');
        $mockDefaultPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->once())->method('process')->will($this->returnValue('defaultMock2'));
        $this->mockMemphisConfig->expects($this->any())->method('getDefaultElements')->will($this->returnValue(array($mockDefaultPageElement1, $mockDefaultPageElement2)));
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())->method('getName')->will($this->returnValue('Mock1'));
        $mockPageElement1->expects($this->once())->method('init');
        $mockPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockPageElement1->expects($this->once())->method('process');
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())->method('getName')->will($this->returnValue('Mock2'));
        $mockPageElement2->expects($this->never())->method('init');
        $mockPageElement2->expects($this->never())->method('isAvailable');
        $mockPageElement2->expects($this->never())->method('process');
        $this->mockMemphisTemplate->expects($this->never())->method('addGlobalVar');
        $this->mockResponse->expects($this->never())->method('write');
        $this->mockPage->expects($this->any())->method('getElements')->will($this->returnValue(array('mock1' => $mockPageElement1, 'mock2' => $mockPageElement2)));
        $this->memphisProcessor->process();
    }

    /**
     * test that processing of page elements works as expected
     *
     * @test
     */
    public function processPageElementsWithRequestCancelledByDefaultPageElement()
    {
        $this->mockRequest->expects($this->any())->method('isCancelled')->will($this->returnValue(true));
        $this->mockMemphisConfig->expects($this->any())->method('getParts')->will($this->returnValue(array('content', 'teaser')));
        $mockDefaultPageElement1 = $this->getMock('stubPageElement');
        $mockDefaultPageElement1->expects($this->any())->method('getName')->will($this->returnValue('defaultMock1'));
        $mockDefaultPageElement1->expects($this->once())->method('init');
        $mockDefaultPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockDefaultPageElement1->expects($this->once())->method('process');
        $mockDefaultPageElement2 = $this->getMock('stubPageElement');
        $mockDefaultPageElement2->expects($this->any())->method('getName')->will($this->returnValue('defaultMock2'));
        $mockDefaultPageElement2->expects($this->never())->method('init');
        $mockDefaultPageElement2->expects($this->never())->method('isAvailable');
        $mockDefaultPageElement2->expects($this->never())->method('process');
        $this->mockMemphisConfig->expects($this->any())->method('getDefaultElements')->will($this->returnValue(array($mockDefaultPageElement1, $mockDefaultPageElement2)));
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())->method('getName')->will($this->returnValue('Mock1'));
        $mockPageElement1->expects($this->never())->method('init');
        $mockPageElement1->expects($this->never())->method('isAvailable');
        $mockPageElement1->expects($this->never())->method('process');
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())->method('getName')->will($this->returnValue('Mock2'));
        $mockPageElement2->expects($this->never())->method('init');
        $mockPageElement2->expects($this->never())->method('isAvailable');
        $mockPageElement2->expects($this->never())->method('process');
        $this->mockMemphisTemplate->expects($this->never())->method('addGlobalVar');
        $this->mockResponse->expects($this->never())->method('write');
        $this->mockPage->expects($this->any())->method('getElements')->will($this->returnValue(array('mock1' => $mockPageElement1, 'mock2' => $mockPageElement2)));
        $this->memphisProcessor->process();
    }

    /**
     * test that request is responsible for choosing the frame
     *
     * @test
     */
    public function getFrameIdFromRequest()
    {
        $this->mockMemphisConfig->expects($this->any())->method('getFrames')->will($this->returnValue(array('default')));
        $page = new stubPage();
        $mockPageFactory = $this->getMock('stubPageFactory');
        $mockPageFactory->expects($this->once())->method('getPageName')->will($this->returnValue('index'));
        $mockPageFactory->expects($this->once())->method('getPage')->will($this->returnValue($page));
        $this->memphisProcessor->selectPage($mockPageFactory);
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->exactly(3))
                          ->method('getValidatedValue')
                          ->will($this->onConsecutiveCalls('another', '', null));
        $this->mockSession->expects($this->never())->method('hasValue');
        $this->assertEquals('another', $this->memphisProcessor->callGetFrameId());
        $this->assertEquals('default', $this->memphisProcessor->callGetFrameId());
        $this->assertEquals('default', $this->memphisProcessor->callGetFrameId());
    }

    /**
     * test that session is responsible for choosing the frame
     *
     * @test
     */
    public function getFrameIdFromSession()
    {
        $page = new stubPage();
        $mockPageFactory = $this->getMock('stubPageFactory');
        $mockPageFactory->expects($this->once())->method('getPageName')->will($this->returnValue('index'));
        $mockPageFactory->expects($this->once())->method('getPage')->will($this->returnValue($page));
        $this->memphisProcessor->selectPage($mockPageFactory);
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(false));
        $this->mockRequest->expects($this->never())->method('getValidatedValue');
        $this->mockSession->expects($this->any())->method('hasValue')->will($this->returnValue(true));
        $this->mockSession->expects($this->exactly(3))
                          ->method('getValue')
                          ->will($this->onConsecutiveCalls('another', '', null));
        $this->assertEquals('another', $this->memphisProcessor->callGetFrameId());
        $this->assertEquals('default', $this->memphisProcessor->callGetFrameId());
        $this->assertEquals('default', $this->memphisProcessor->callGetFrameId());
    }

    /**
     * test that page is responsible for choosing the frame
     *
     * @test
     */
    public function getFrameIdFromPage()
    {
        $page = new stubPage();
        $mockPageFactory = $this->getMock('stubPageFactory');
        $mockPageFactory->expects($this->once())->method('getPageName')->will($this->returnValue('index'));
        $mockPageFactory->expects($this->once())->method('getPage')->will($this->returnValue($page));
        $this->memphisProcessor->selectPage($mockPageFactory);
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(false));
        $this->mockRequest->expects($this->never())->method('getValidatedValue');
        $this->mockSession->expects($this->any())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->never())->method('getValue');
        $this->assertEquals('default', $this->memphisProcessor->callGetFrameId());
        $page->setProperty('frame', '');
        $this->assertEquals('default', $this->memphisProcessor->callGetFrameId());
        $page->setProperty('frame', 'another');
        $this->assertEquals('another', $this->memphisProcessor->callGetFrameId());
    }

    /**
     * test that page is responsible for choosing the frame
     *
     * @test
     */
    public function getFrameIdFromPageWithFixedFrame()
    {
        $page = new stubPage();
        $page->setProperty('frame', '404');
        $page->setProperty('frame_fixed', true);
        $mockPageFactory = $this->getMock('stubPageFactory');
        $mockPageFactory->expects($this->once())->method('getPageName')->will($this->returnValue('index'));
        $mockPageFactory->expects($this->once())->method('getPage')->will($this->returnValue($page));
        $this->memphisProcessor->selectPage($mockPageFactory);
        $this->mockRequest->expects($this->any())->method('hasValue');
        $this->mockSession->expects($this->any())->method('hasValue');
        $this->assertEquals('404', $this->memphisProcessor->callGetFrameId());
    }

    /**
     * test that template vars are set correct
     *
     * @test
     */
    public function setTemplateVars1()
    {
        $this->mockPage->expects($this->once())->method('getProperty')->will($this->returnValue(utf8_encode('b�r')));
        $this->mockMemphisConfig->expects($this->any())
                                ->method('getMetaTags')
                                ->will($this->returnValue(array('description' => utf8_encode('This is a description containing an �mlaut.'),
                                                                'keywords'    => 'keyword1, keyword2'
                                                          )));
        $dummyTemplate = new TeststubDummyTemplate();
        $this->memphisProcessor->setTemplate($dummyTemplate);
        $this->mockSession->expects($this->any())->method('getName')->will($this->returnValue('sessionid'));
        $this->mockSession->expects($this->any())->method('getId')->will($this->returnValue(313));
        $this->memphisProcessor->callSetTemplateVars();
        $this->assertEquals(array('UCUO_FRAME'    => 'frame',
                                  'PAGE_TITLE'    => utf8_encode('b�r'),
                                  'PAGE_NAME'     => 'index',
                                  'VARIANT'       => null,
                                  'VARIANT_ALIAS' => null,
                                  'SERVICE_URL'   => '?processor=jsonrpc',
                                  'SID'           => '$SID',
                                  'SESSION_NAME'  => '$SESSION_NAME',
                                  'SESSION_ID'    => '$SESSION_ID',
                                  'SSL_MODE'      => 'no'
                            ),
                            $dummyTemplate->getGlobalVars()
        );
        $this->assertEquals(array('frame' => array('META_description' => utf8_encode('This is a description containing an �mlaut.'),
                                                   'META_keywords'    => 'keyword1, keyword2'
                                             )
                            ),
                            $dummyTemplate->getVars()
        );
    }

    /**
     * test that template vars are set correct
     *
     * @test
     */
    public function setTemplateVars()
    {
        $this->mockPage->expects($this->once())->method('getProperty')->will($this->returnValue('<b�r>'));
        $this->mockMemphisConfig->expects($this->any())
                                ->method('getMetaTags')
                                ->will($this->returnValue(array()));
        $dummyTemplate = new TeststubDummyTemplate();
        $this->memphisProcessor->setTemplate($dummyTemplate);
        $this->memphisProcessor->setSSL(true);
        $this->mockSession->expects($this->any())->method('getValue')->will($this->returnValue('variant'));
        $this->mockSession->expects($this->any())->method('getName')->will($this->returnValue('sessionid'));
        $this->mockSession->expects($this->any())->method('getId')->will($this->returnValue(313));
        $this->memphisProcessor->callSetTemplateVars();
        $this->assertEquals(array('UCUO_FRAME'    => 'frame',
                                  'PAGE_TITLE'    => '&lt;b�r&gt;',
                                  'PAGE_NAME'     => 'index',
                                  'VARIANT'       => 'variant',
                                  'VARIANT_ALIAS' => 'variant',
                                  'SERVICE_URL'   => '?processor=jsonrpc',
                                  'SID'           => '$SID',
                                  'SESSION_NAME'  => '$SESSION_NAME',
                                  'SESSION_ID'    => '$SESSION_ID',
                                  'SSL_MODE'      => 'yes'
                            ),
                            $dummyTemplate->getGlobalVars()
        );
        $this->assertEquals(array(), $dummyTemplate->getVars());
    }
}
?>