<?php
/**
 * Test for net::stubbles::xml::rss::stubRSSProcessor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_rss_test
 */
stubClassLoader::load('net::stubbles::xml::rss::stubRSSProcessor');
@include_once 'vfsStream/vfsStream.php';
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  xml_rss_test
 */
class TeststubRSSProcessor extends stubRSSProcessor
{
    /**
     * access to protected method
     */
    public function callDoConstruct()
    {
        parent::doConstruct();
    }

    /**
     * overwrite to prevent method calls
     */
    public function doConstruct()
    {
        // intentionally empty
    }

    /**
     * sets the list of feeds
     *
     * @param  array<string,string>  $feeds
     */
    public function setFeeds(array $feeds)
    {
        $this->feeds = $feeds;
    }

    /**
     * access to protected method
     *
     * @param   string                $configFile
     * @return  array<string,string>
     */
    public function callLoadFeeds($configFile)
    {
        return $this->loadFeeds($configFile);
    }
}
/**
 * Tests for net::stubbles::xml::rss::stubRSSProcessor.
 *
 * @package     stubbles
 * @subpackage  xml_rss_test
 * @group       xml
 * @group       xml_rss
 */
class stubRSSProcessorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * mocked request to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * mocked response instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * mocked session to use
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest  = $this->getMock('stubRequest');
        $this->mockSession  = $this->getMock('stubSession');
        $this->mockResponse = $this->getMock('stubResponse');
    }

    /**
     * construction with no configured config file should use rss-feeds.ini
     *
     * @test
     */
    public function constructionWithNoSpecialConfigFile()
    {
        $rssProcessor = $this->getMock('TeststubRSSProcessor',
                                       array('loadFeeds'),
                                       array($this->mockRequest,
                                             $this->mockSession,
                                             $this->mockResponse
                                       )
                        );
        $rssProcessor->expects($this->once())
                     ->method('loadFeeds')
                     ->with($this->equalTo(stubConfig::getConfigPath() . DIRECTORY_SEPARATOR . 'rss-feeds.ini'));
        $rssProcessor->callDoConstruct();
    }

    /**
     * failing to load the feeds throws an exception
     *
     * @test
     * @expectedException  stubFileNotFoundException
     */
    public function loadFeedsFails()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('Test for feed loading required vfsStream, see http://vfs.bovigo.org/.');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('config'));
        $rssProcessor = new TeststubRSSProcessor($this->mockRequest,
                                                 $this->mockSession,
                                                 $this->mockResponse
                                );
        $rssProcessor->callLoadFeeds(vfsStream::url('config/doesNotExist.ini'));
    }

    /**
     * loading the feeds should return a list of classes
     *
     * @test
     */
    public function loadFeeds()
    {
        if (class_exists('vfsStream', false) === false) {
            $this->markTestSkipped('Test for feed loading required vfsStream, see http://vfs.bovigo.org/.');
        }
        
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('config'));
        vfsStream::newFile('rss-feeds.ini')->at(vfsStreamWrapper::getRoot())->withContent("default = \"org::stubbles::examples::xml::rss::TestFeed\"");
        $rssProcessor = new TeststubRSSProcessor($this->mockRequest,
                                                 $this->mockSession,
                                                 $this->mockResponse
                                );
        $this->assertEquals(array('default' => 'org::stubbles::examples::xml::rss::TestFeed'),
                            $rssProcessor->callLoadFeeds(vfsStream::url('config/rss-feeds.ini'))
        );
    }

    /**
     * no binder available triggers exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function withoutBinder()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('feed'))
                          ->will($this->returnValue(null));
        $this->mockResponse->expects($this->never())
                           ->method('addHeader');
        $this->mockResponse->expects($this->never())
                           ->method('write');
        $rssProcessor = new TeststubRSSProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $rssProcessor->setFeeds(array('default' => 'org::stubbles::examples::xml::rss::TestFeed',
                                      'test'    => 'org::stubbles::examples::xml::rss::TestFeed2'
                                )
        );
        $rssProcessor->process();
    }

    /**
     * use default feed
     *
     * @test
     */
    public function withBinderUsingDefaultFeed()
    {
        $mockXMLStreamWriter = $this->getMock('stubXMLStreamWriter');
        $mockXMLStreamWriter->expects($this->once())
                            ->method('asXML')
                            ->will($this->returnValue('rssFeedContents'));
        $mockRssFeedGenerator = $this->getMock('stubRSSFeedGenerator', array(), array('title', 'link', 'description'));
        $mockRssFeedGenerator->expects($this->once())
                             ->method('serialize')
                             ->will($this->returnValue($mockXMLStreamWriter));
        $mockRssFeed  = $this->getMock('stubRSSFeed');
        $mockRssFeed->expects($this->once())
                    ->method('create')
                    ->will($this->returnValue($mockRssFeedGenerator));
        $mockInjector = $this->getMock('stubInjector');
        $mockInjector->expects($this->once())
                     ->method('getInstance')
                     ->with($this->equalTo('org::stubbles::examples::xml::rss::DefaultFeed'))
                     ->will($this->returnValue($mockRssFeed));
        stubRegistry::set(stubBinder::REGISTRY_KEY, new stubBinder($mockInjector));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('feed'))
                          ->will($this->returnValue(null));
        $this->mockResponse->expects($this->once())
                           ->method('addHeader')
                           ->with($this->equalTo('Content-Type'), $this->equalTo('text/xml; charset=utf-8'));
        $this->mockResponse->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo('rssFeedContents'));
        $rssProcessor = new TeststubRSSProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $rssProcessor->setFeeds(array('default' => 'org::stubbles::examples::xml::rss::DefaultFeed',
                                      'test'    => 'org::stubbles::examples::xml::rss::TestFeed'
                                )
        );
        $rssProcessor->process();
    }

    /**
     * use requested feed
     *
     * @test
     */
    public function withBinderUsingRequestedFeed()
    {
        $mockXMLStreamWriter = $this->getMock('stubXMLStreamWriter');
        $mockXMLStreamWriter->expects($this->once())
                            ->method('asXML')
                            ->will($this->returnValue('rssFeedContents'));
        $mockRssFeedGenerator = $this->getMock('stubRSSFeedGenerator', array(), array('title', 'link', 'description'));
        $mockRssFeedGenerator->expects($this->once())
                             ->method('serialize')
                             ->will($this->returnValue($mockXMLStreamWriter));
        $mockRssFeed  = $this->getMock('stubRSSFeed');
        $mockRssFeed->expects($this->once())
                    ->method('create')
                    ->will($this->returnValue($mockRssFeedGenerator));
        $mockInjector = $this->getMock('stubInjector');
        $mockInjector->expects($this->once())
                     ->method('getInstance')
                     ->with($this->equalTo('org::stubbles::examples::xml::rss::TestFeed'))
                     ->will($this->returnValue($mockRssFeed));
        stubRegistry::set(stubBinder::REGISTRY_KEY, new stubBinder($mockInjector));
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('feed'))
                          ->will($this->returnValue('test'));
        $this->mockResponse->expects($this->once())
                           ->method('addHeader')
                           ->with($this->equalTo('Content-Type'), $this->equalTo('text/xml; charset=utf-8'));
        $this->mockResponse->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo('rssFeedContents'));
        $rssProcessor = new TeststubRSSProcessor($this->mockRequest, $this->mockSession, $this->mockResponse);
        $rssProcessor->setFeeds(array('default' => 'org::stubbles::examples::xml::rss::DefaultFeed',
                                      'test'    => 'org::stubbles::examples::xml::rss::TestFeed'
                                )
        );
        $rssProcessor->process();
    }
}
?>