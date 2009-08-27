<?php
/**
 * Test for net::stubbles::peer::http::stubHTTPURL.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  peer_http_test
 * @version     $Id: stubHTTPURLTestCase.php 1910 2008-11-03 20:12:33Z mikey $
 */
stubClassLoader::load('net::stubbles::peer::http::stubHTTPURL');
/**
 * Test for net::stubbles::peer::http::stubHTTPURL.
 *
 * @package     stubbles
 * @subpackage  peer_http_test
 * @group       peer
 * @group       peer_http
 */
class stubHTTPURLTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function value()
    {
        $http = stubHTTPURL::fromString('http://example.com/');
        $this->assertTrue($http->isValid());
        $this->assertTrue($http->hasDefaultPort());
        $this->assertEquals('http://example.com/', $http->get());
        $this->assertEquals('http://example.com:80/', $http->get(true));
        $this->assertEquals('http', $http->getScheme());
        $this->assertEquals('example.com', $http->getHost());
        $this->assertEquals(80, $http->getPort());
        $this->assertEquals('/', $http->getPath());
        $this->assertFalse($http->hasQuery());
        $this->assertTrue($http->checkDNS());
        
        $http = stubHTTPURL::fromString('https://example.com/');
        $this->assertTrue($http->isValid());
        $this->assertTrue($http->hasDefaultPort());
        $this->assertEquals('https://example.com/', $http->get());
        $this->assertEquals('https://example.com:443/', $http->get(true));
        $this->assertEquals('https', $http->getScheme());
        $this->assertEquals('example.com', $http->getHost());
        $this->assertEquals(443, $http->getPort());
        $this->assertEquals('/', $http->getPath());
        $this->assertFalse($http->hasQuery());
        $this->assertTrue($http->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueComplete()
    {    
        $http = stubHTTPURL::fromString('http://eXAMPle.com:80/index.php?content=features#top');
        $this->assertTrue($http->isValid());
        $this->assertTrue($http->hasDefaultPort());
        $this->assertEquals('http://example.com:80/index.php?content=features#top', $http->get(true));
        $this->assertEquals('example.com', $http->getHost());
        $this->assertEquals(80, $http->getPort());
        $this->assertEquals('/index.php?content=features', $http->getPath());
        $this->assertTrue($http->hasQuery());
        $this->assertTrue($http->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueLocalhost()
    {
        $http = stubHTTPURL::fromString('http://localhost:125/');
        $this->assertTrue($http->isValid());
        $this->assertFalse($http->hasDefaultPort());
        $this->assertEquals('http://localhost/', $http->get());
        $this->assertEquals('http://localhost:125/', $http->get(true));
        $this->assertEquals('localhost', $http->getHost());
        $this->assertEquals(125, $http->getPort());
        $this->assertEquals('/', $http->getPath());
        $this->assertFalse($http->hasQuery());
        $this->assertTrue($http->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueIP()
    {
        $http = stubHTTPURL::fromString('http://127.0.0.1/');
        $this->assertTrue($http->isValid());
        $this->assertTrue($http->hasDefaultPort());
        $this->assertEquals('http://127.0.0.1/', $http->get());
        $this->assertEquals('http://127.0.0.1:80/', $http->get(true));
        $this->assertEquals('127.0.0.1', $http->getHost());
        $this->assertEquals(80, $http->getPort());
        $this->assertEquals($http->getPath(), '/');
        $this->assertFalse($http->hasQuery());
        $this->assertTrue($http->checkDNS());
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function valueHTTPSIP()
    {
        $http = stubHTTPURL::fromString('https://127.0.0.1:125/');
        $this->assertTrue($http->isValid());
        $this->assertFalse($http->hasDefaultPort());
        $this->assertEquals('https://127.0.0.1/', $http->get());
        $this->assertEquals('https://127.0.0.1:125/', $http->get(true));
        $this->assertEquals('127.0.0.1', $http->getHost());
        $this->assertEquals(125, $http->getPort());
        $this->assertEquals($http->getPath(), '/');
        $this->assertFalse($http->hasQuery());
        $this->assertTrue($http->checkDNS());
    }

    /**
     * assure that wrong values trigger an exception
     *
     * @test
     */
    public function wrongValue()
    {
        $this->setExpectedException('stubMalformedURLException');
        $url = stubHTTPURL::fromString('blubber');
    }

    /**
     * assure that an empty string does not generate an instance of stubURL
     *
     * @test
     */
    public function emptyString()
    {
        $this->assertNull(stubHTTPURL::fromString(''));
    }

    /**
     * assure that wrong scheme triggers an exception
     *
     * @test
     */
    public function wrongScheme()
    {
        $this->setExpectedException('stubMalformedURLException');
        $url = stubHTTPURL::fromString('ftp://user:password@auxiliary.kl-s.com/');
    }

    /**
     * assure getting a connection works as expected
     *
     * @test
     */
    public function connection()
    {
        $http = stubHTTPURL::fromString('http://example.com/');
        $httpconnection = $http->connect();
        $this->assertType('stubHTTPConnection', $httpconnection);
    }

    /**
     * assure getting a connection works as expected
     *
     * @test
     */
    public function connectionWithHeaderList()
    {
        $http    = stubHTTPURL::fromString('http://example.com/');
        $headers = new stubHeaderList();
        $httpconnection = $http->connect($headers);
        $this->assertType('stubHTTPConnection', $httpconnection);
        $this->assertSame($headers, $httpconnection->getHeaderList());
    }
}
?>