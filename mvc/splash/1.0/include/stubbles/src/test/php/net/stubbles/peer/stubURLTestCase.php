<?php
/**
 * Test for net::stubbles::peer::stubURL.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_test
 * @version     $Id: stubURLTestCase.php 1935 2008-11-28 14:24:21Z mikey $
 */
stubClassLoader::load('net::stubbles::peer::stubURL');
/**
 * Test for net::stubbles::peer::stubURL.
 *
 * @package     stubbles
 * @subpackage  peer_test
 * @group       peer
 */
class stubURLTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function value()
    {
        $url = stubURL::fromString('http://example.com/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('http://example.com/', $url->get());
        $this->assertEquals('http', $url->getScheme());
        $this->assertNull($url->getUser());
        $this->assertEquals('foo', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('example.com', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $url->setPort(303);
        $this->assertEquals(303, $url->getPort());
        $this->assertEquals(303, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueComplete()
    {    
        $url = stubURL::fromString('http://exAmpLe.com:80/index.php?content=features#top');
        $this->assertTrue($url->isValid());
        $this->assertEquals('http://example.com:80/index.php?content=features#top', $url->get(true));
        $this->assertEquals('http', $url->getScheme());
        $this->assertNull($url->getUser());
        $this->assertEquals('foo', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('example.com', $url->getHost());
        $this->assertEquals($url->getPort(), 80);
        $this->assertEquals($url->getPath(), '/index.php?content=features');
        $this->assertTrue($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueFTPComplete()
    {
        $url = stubURL::fromString('ftp://user:password@example.com/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('ftp://user:password@example.com/', $url->get(true));
        $this->assertEquals('ftp', $url->getScheme());
        $this->assertEquals('user', $url->getUser());
        $this->assertEquals('user', $url->getUser('foo'));
        $this->assertEquals('password', $url->getPassword());
        $this->assertEquals('password', $url->getPassword('foo'));
        $this->assertEquals('example.com', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueFTPWithoutPass()
    {
        $url = stubURL::fromString('ftp://user@example.com/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('ftp://user@example.com/', $url->get());
        $this->assertEquals('ftp', $url->getScheme());
        $this->assertEquals('user', $url->getUser());
        $this->assertEquals('user', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('example.com', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueFTPEmptyPass()
    {
        $url = stubURL::fromString('ftp://user:@example.com/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('ftp://user:@example.com/', $url->get());
        $this->assertEquals('ftp', $url->getScheme());
        $this->assertEquals('user', $url->getUser());
        $this->assertEquals('user', $url->getUser('foo'));
        $this->assertEquals('', $url->getPassword());
        $this->assertEquals('', $url->getPassword('foo'));
        $this->assertEquals('example.com', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueFTPEmptyUser()
    {
        $url = stubURL::fromString('ftp://@example.com/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('ftp://@example.com/', $url->get());
        $this->assertEquals('ftp', $url->getScheme());
        $this->assertEquals('', $url->getUser());
        $this->assertEquals('', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('example.com', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     * @expectedException  stubMalformedURLException
     */
    public function valueWrongUserAndPass()
    {
        $url = stubURL::fromString('ftp://h:/:@example.com/');
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueLocalhost()
    {
        $url = stubURL::fromString('http://localhost/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('http://localhost/', $url->get());
        $this->assertEquals('http', $url->getScheme());
        $this->assertNull($url->getUser());
        $this->assertEquals('foo', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('localhost', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueIP()
    {
        $url = stubURL::fromString('http://127.0.0.1/');
        $this->assertTrue($url->isValid());
        $this->assertEquals('http://127.0.0.1/', $url->get());
        $this->assertEquals('http', $url->getScheme());
        $this->assertNull($url->getUser());
        $this->assertEquals('foo', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('127.0.0.1', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueIPNoPath()
    {
        $url = stubURL::fromString('http://127.0.0.1');
        $this->assertTrue($url->isValid());
        $this->assertEquals('http://127.0.0.1', $url->get());
        $this->assertEquals('http', $url->getScheme());
        $this->assertNull($url->getUser());
        $this->assertEquals('foo', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertEquals('127.0.0.1', $url->getHost());
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertNull($url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertTrue($url->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function fileURL()
    {
        $url = stubURL::fromString('file:///home');
        $this->assertTrue($url->isValid());
        $this->assertEquals('file:///home', $url->get());
        $this->assertEquals('file', $url->getScheme());
        $this->assertNull($url->getUser());
        $this->assertEquals('foo', $url->getUser('foo'));
        $this->assertNull($url->getPassword());
        $this->assertEquals('foo', $url->getPassword('foo'));
        $this->assertNull($url->getHost());
        $this->assertEquals('127.0.0.1', $url->getHost('127.0.0.1'));
        $this->assertNull($url->getPort());
        $this->assertEquals(313, $url->getPort(313));
        $this->assertEquals('/home', $url->getPath());
        $this->assertFalse($url->hasQuery());
        $this->assertFalse($url->checkDNS());
    }

    /**
     * assure that wrong values trigger an exception
     *
     * @test
     * @expectedException  stubMalformedURLException
     */
    public function wrongValue()
    {
        $url = stubURL::fromString('blubber');
    }

    /**
     * assure that an empty string does not generate an instance of stubURL
     *
     * @test
     */
    public function emptyString()
    {
        $this->assertNull(stubURL::fromString(''));
    }

    /**
     * assure that added parameters are correct in complete url and path
     *
     * @test
     */
    public function params()
    {
        $url = stubURL::fromString('http://example.org/');
        $this->assertFalse($url->hasQuery());
        
        $this->assertSame($url, $url->addParam('test', 'hello'));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello', $url->get());
        $this->assertEquals('/?test=hello', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test2', 538));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538', $url->get());
        $this->assertEquals('/?test=hello&test2=538', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test3', array(1, 2, 3)));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538&test3[]=1&test3[]=2&test3[]=3', $url->get());
        $this->assertEquals('/?test=hello&test2=538&test3[]=1&test3[]=2&test3[]=3', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test3', array('one' => 1, 'two' => 2, 'three' => 3)));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538&test3[one]=1&test3[two]=2&test3[three]=3', $url->get());
        $this->assertEquals('/?test=hello&test2=538&test3[one]=1&test3[two]=2&test3[three]=3', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test3', array('one' => 1, 'two' => 2, 3)));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538&test3[one]=1&test3[two]=2&test3[]=3', $url->get());
        $this->assertEquals('/?test=hello&test2=538&test3[one]=1&test3[two]=2&test3[]=3', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test3', null));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538', $url->get());
        $this->assertEquals('/?test=hello&test2=538', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test3', true));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538&test3=1', $url->get());
        $this->assertEquals('/?test=hello&test2=538&test3=1', $url->getPath());
        
        $this->assertSame($url, $url->addParam('test3', false));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?test=hello&test2=538&test3=0', $url->get());
        $this->assertEquals('/?test=hello&test2=538&test3=0', $url->getPath());
    }

    /**
     * assure that paramter without value is valid
     * e.g.: http://example.org?wsdl 
     *       http://example.org?key1&foo=bar&key2
     *
     * @test
     */
    public function keyWithoutParam()
    {
        $url = stubURL::fromString('http://example.org/');
        $this->assertFalse($url->hasQuery());
        $this->assertSame($url, $url->addParam('key1', ''));
        $this->assertTrue($url->hasQuery());
        $this->assertEquals('http://example.org/?key1', $url->get());
        $this->assertSame($url, $url->addParam('foo', 'bar'));
        $this->assertSame($url, $url->addParam('key2', ''));
        $this->assertEquals('http://example.org/?key1&foo=bar&key2', $url->get());
    }
    
    /**
     * assure that wrong parameters throw an exception
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function wrongParams()
    {
        $url = stubURL::fromString('http://example.org/');
        $url->addParam('test', new stdClass());
    }

    /**
     * assure that wrong keys throw an exception
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function wrongKeyForParams()
    {
        $url = stubURL::fromString('http://example.org/');
        $url->addParam(435, 'test');
    }
}
?>