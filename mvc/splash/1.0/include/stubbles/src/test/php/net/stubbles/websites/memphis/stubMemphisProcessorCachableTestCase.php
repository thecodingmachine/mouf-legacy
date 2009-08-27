<?php
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis_test
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisProcessor');
require_once dirname(__FILE__) . '/stubDummyWebsiteCache.php';
require_once dirname(__FILE__) . '/stubMemphisProcessorTestHelper.php';
/**
 * Tests for net::stubbles::websites::memphis::stubMemphisProcessor.
 *
 * This test covers the methods from the stubCachableProcessor interface.
 *
 * @package     stubbles
 * @subpackage  websites_memphis_test
 * @group       websites
 * @group       websites_memphis
 */
class stubMemphisProcessorCachableTestCase extends PHPUnit_Framework_TestCase
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
     * test that collecting the cache variables works as expected
     *
     * @test
     */
    public function processCacheVars()
    {
        $this->mockMemphisConfig->expects($this->any())->method('getParts')->will($this->returnValue(array('content', 'teaser')));
        $mockDefaultPageElement1 = $this->getMock('stubPageElement');
        $mockDefaultPageElement1->expects($this->any())->method('getName')->will($this->returnValue('defaultMock1'));
        $mockDefaultPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockDefaultPageElement1->expects($this->never())->method('isCachable');
        $mockDefaultPageElement1->expects($this->never())->method('getCacheVars');
        $mockDefaultPageElement1->expects($this->never())->method('getUsedFiles');
        $mockDefaultPageElement2 = $this->getMock('stubPageElement');
        $mockDefaultPageElement2->expects($this->any())->method('getName')->will($this->returnValue('defaultMock2'));
        $mockDefaultPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->any())->method('isCachable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->exactly(2))->method('getCacheVars')->will($this->returnValue(array('one' => 'defaultMock2')));
        $mockDefaultPageElement2->expects($this->exactly(2))->method('getUsedFiles')->will($this->returnValue(array('defaultMock2.tmpl')));
        $this->mockMemphisConfig->expects($this->any())->method('getDefaultElements')->will($this->returnValue(array($mockDefaultPageElement1, $mockDefaultPageElement2)));
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())->method('getName')->will($this->returnValue('Mock1'));
        $mockPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockPageElement1->expects($this->never())->method('isCachable');
        $mockPageElement1->expects($this->never())->method('getCacheVars');
        $mockPageElement1->expects($this->never())->method('getUsedFiles');
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())->method('getName')->will($this->returnValue('Mock2'));
        $mockPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockPageElement2->expects($this->any())->method('isCachable')->will($this->returnValue(true));
        $mockPageElement2->expects($this->exactly(2))->method('getCacheVars')->will($this->returnValue(array('two' => 'Mock2')));
        $mockPageElement2->expects($this->exactly(2))->method('getUsedFiles')->will($this->returnValue(array('Mock2.tmpl')));
        $this->mockPage->expects($this->any())->method('getElements')->will($this->returnValue(array('mock1' => $mockPageElement1, 'mock2' => $mockPageElement2)));
        $mockCache = new stubDummyWebsiteCache();
        $this->assertTrue($this->memphisProcessor->addCacheVars($mockCache));
        $this->assertEquals(array('page'    => 'index',
                                  'frame'   => 'frame',
                                  'variant' => null,
                                  'one'     => 'defaultMock2',
                                  'two'     => 'Mock2'
                            ),
                            $mockCache->getCacheVars()
        );
        $this->assertEquals(array('defaultMock2.tmpl' => 'defaultMock2.tmpl',
                                  'Mock2.tmpl'        => 'Mock2.tmpl'
                            ),
                            $mockCache->getUsedFiles()
        );
    }

    /**
     * test that collecting the cache variables works as expected
     *
     * @test
     */
    public function processCacheVarsWithUncachableElement()
    {
        $this->mockMemphisConfig->expects($this->any())->method('getParts')->will($this->returnValue(array('content', 'teaser')));
        $mockDefaultPageElement1 = $this->getMock('stubPageElement');
        $mockDefaultPageElement1->expects($this->any())->method('getName')->will($this->returnValue('defaultMock1'));
        $mockDefaultPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockDefaultPageElement1->expects($this->never())->method('isCachable');
        $mockDefaultPageElement1->expects($this->never())->method('getCacheVars');
        $mockDefaultPageElement1->expects($this->never())->method('getUsedFiles');
        $mockDefaultPageElement2 = $this->getMock('stubPageElement');
        $mockDefaultPageElement2->expects($this->any())->method('getName')->will($this->returnValue('defaultMock2'));
        $mockDefaultPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->any())->method('isCachable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->once())->method('getCacheVars')->will($this->returnValue(array('one' => 'defaultMock2')));
        $mockDefaultPageElement2->expects($this->once())->method('getUsedFiles')->will($this->returnValue(array('defaultMock2.tmpl')));
        $this->mockMemphisConfig->expects($this->any())->method('getDefaultElements')->will($this->returnValue(array($mockDefaultPageElement1, $mockDefaultPageElement2)));
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())->method('getName')->will($this->returnValue('Mock1'));
        $mockPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockPageElement1->expects($this->never())->method('isCachable');
        $mockPageElement1->expects($this->never())->method('getCacheVars');
        $mockPageElement1->expects($this->never())->method('getUsedFiles');
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())->method('getName')->will($this->returnValue('Mock2'));
        $mockPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockPageElement2->expects($this->any())->method('isCachable')->will($this->returnValue(false));
        $mockPageElement2->expects($this->never())->method('getCacheVars');
        $mockPageElement2->expects($this->never())->method('getUsedFiles');
        $this->mockPage->expects($this->any())->method('getElements')->will($this->returnValue(array('mock1' => $mockPageElement1, 'mock2' => $mockPageElement2)));
        $mockCache = new stubDummyWebsiteCache();
        $this->assertFalse($this->memphisProcessor->addCacheVars($mockCache));
        $this->assertEquals(array('page'    => 'index',
                                  'frame'   => 'frame',
                                  'variant' => null,
                                  'one'     => 'defaultMock2'
                            ),
                            $mockCache->getCacheVars()
        );
        $this->assertEquals(array('defaultMock2.tmpl' => 'defaultMock2.tmpl'),
                            $mockCache->getUsedFiles()
        );
    }

    /**
     * test that collecting the cache variables works as expected
     *
     * @test
     */
    public function processCacheVarsWithUncachableDefaultElement()
    {
        $this->mockMemphisConfig->expects($this->any())->method('getParts')->will($this->returnValue(array('content', 'teaser')));
        $mockDefaultPageElement1 = $this->getMock('stubPageElement');
        $mockDefaultPageElement1->expects($this->any())->method('getName')->will($this->returnValue('defaultMock1'));
        $mockDefaultPageElement1->expects($this->any())->method('isAvailable')->will($this->returnValue(false));
        $mockDefaultPageElement1->expects($this->never())->method('isCachable');
        $mockDefaultPageElement1->expects($this->never())->method('getCacheVars');
        $mockDefaultPageElement1->expects($this->never())->method('getUsedFiles');
        $mockDefaultPageElement2 = $this->getMock('stubPageElement');
        $mockDefaultPageElement2->expects($this->any())->method('getName')->will($this->returnValue('defaultMock2'));
        $mockDefaultPageElement2->expects($this->any())->method('isAvailable')->will($this->returnValue(true));
        $mockDefaultPageElement2->expects($this->any())->method('isCachable')->will($this->returnValue(false));
        $mockDefaultPageElement2->expects($this->never())->method('getCacheVars');
        $mockDefaultPageElement2->expects($this->never())->method('getUsedFiles');
        $this->mockMemphisConfig->expects($this->any())->method('getDefaultElements')->will($this->returnValue(array($mockDefaultPageElement1, $mockDefaultPageElement2)));
        $mockPageElement1 = $this->getMock('stubPageElement');
        $mockPageElement1->expects($this->any())->method('getName')->will($this->returnValue('Mock1'));
        $mockPageElement1->expects($this->never())->method('init');
        $mockPageElement1->expects($this->never())->method('isAvailable');
        $mockPageElement1->expects($this->never())->method('isCachable');
        $mockPageElement1->expects($this->never())->method('getCacheVars');
        $mockPageElement1->expects($this->never())->method('getUsedFiles');
        $mockPageElement2 = $this->getMock('stubPageElement');
        $mockPageElement2->expects($this->any())->method('getName')->will($this->returnValue('Mock2'));
        $mockPageElement2->expects($this->never())->method('init');
        $mockPageElement2->expects($this->never())->method('isAvailable');
        $mockPageElement2->expects($this->never())->method('isCachable');
        $mockPageElement2->expects($this->never())->method('getCacheVars');
        $mockPageElement2->expects($this->never())->method('getUsedFiles');
        $this->mockPage->expects($this->any())->method('getElements')->will($this->returnValue(array('mock1' => $mockPageElement1, 'mock2' => $mockPageElement2)));
        $mockCache = new stubDummyWebsiteCache();
        $this->memphisProcessor->setSSL(true);
        $this->assertFalse($this->memphisProcessor->addCacheVars($mockCache));
        $this->assertEquals(array('page'    => 'index',
                                  'frame'   => 'frame',
                                  'variant' => null
                            ),
                            $mockCache->getCacheVars()
        );
        $this->assertEquals(array(), $mockCache->getUsedFiles());
    }

    /**
     * page name should be returned
     *
     * @test
     */
    public function pageName()
    {
        $this->assertEquals('index', $this->memphisProcessor->getPageName());
    }
}
?>