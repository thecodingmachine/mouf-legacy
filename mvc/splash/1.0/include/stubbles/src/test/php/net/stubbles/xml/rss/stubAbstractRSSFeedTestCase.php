<?php
/**
 * Test for net::stubbles::xml::rss::stubAbstractRSSFeed.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_rss_test
 */
stubClassLoader::load('net::stubbles::xml::rss::stubAbstractRSSFeed',
                      'net::stubbles::ioc::annotations::stubInjectAnnotation',
                      'net::reflection::stubReflectionClass'
);

/**
 * Simple Test dummy implementation of abstract rss feed instance
 *
 * @package     stubbles
 * @subpackage  xml_rss_test
 */
class testAbstractRSSFeed extends stubAbstractRSSFeed {
    protected $title       = 'test feed';
    protected $description = 'test feed description';
    protected $copyright   = 'test copyright';
    protected function doCreate(stubRSSFeedGenerator $o){ return $o; }
}

/**
 * Test for net::stubbles::xml::rss::stubAbstractRSSFeed.
 *
 * @package     stubbles
 * @subpackage  xml_rss_test
 * @group       xml
 * @group       xml_rss
 */
class stubAbstractRSSFeedTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubAbstractRSSFeed
     */
    protected $abstractRssFeed;
    /**
     * mocked request instance
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mockRequest = $this->getMock('stubRequest');
        $this->abstractRssFeed = new testAbstractRSSFeed();
        $this->abstractRssFeed->setRequest($this->mockRequest);
    }

    /**
     * make sure annotation is present
     *
     * @test
     */
    public function annotationPresent()
    {
        $reflection = new stubReflectionClass('stubAbstractRSSFeed');
        $this->assertTrue($reflection->getMethod('setRequest')->hasAnnotation('Inject'));
    }

    /**
     * link should be created from request, but only once
     *
     * @test
     */
    public function linkShouldBeCreatedFromRequest()
    {
        $this->mockRequest->expects($this->once())
                          ->method('getValidatedValue')
                          ->with($this->anything(), $this->equalTo('SERVER_NAME'), $this->equalTo(stubRequest::SOURCE_HEADER))
                          ->will($this->returnValue('example.com'));
        
        $this->assertEquals('http://example.com/', $this->abstractRssFeed->getLink());
        $this->assertEquals('http://example.com/', $this->abstractRssFeed->getLink());
    }

    /**
     * create() should call all methods and return the rss feed generator
     *
     * @test
     */
    public function createShouldCreateTheRssFeedGenerator()
    {
        $rssFeedGenerator = $this->abstractRssFeed->create();

        $this->assertType('stubRSSFeedGenerator',  $rssFeedGenerator);
        $this->assertEquals('en_EN', $rssFeedGenerator->getLanguage());

        $this->assertEquals($this->abstractRssFeed->getTitle(), $rssFeedGenerator->getTitle());
        $this->assertEquals($this->abstractRssFeed->getDescription(), $rssFeedGenerator->getDescription());
        $this->assertEquals($this->abstractRssFeed->getCopyright(), $rssFeedGenerator->getCopyright());
    }

    /**
     * create() should call all only the internal lifecycle methods
     *
     * @test
     */
    public function createPassThroughGeneratorWithoutModifyRssInformation()
    {
        $rssGenerator = $this->abstractRssFeed->create(new stubRSSFeedGenerator('foo','http://example.org','bar'));

        $this->assertNotEquals('test feed', $rssGenerator->getTitle());
        $this->assertNotEquals('test feed description', $rssGenerator->getDescription());
        $this->assertNotEquals('test copyright', $rssGenerator->getCopyright());
    }

    /**
     * language was modify from outside of the generator
     *
     * @test
     */
    public function modifyLanguageByConfig()
    {
        stubRegistry::setConfig('net.stubbles.language', 'de_DE');
        $this->assertEquals('de_DE', $this->abstractRssFeed->create()->getLanguage());
    }
}
?>