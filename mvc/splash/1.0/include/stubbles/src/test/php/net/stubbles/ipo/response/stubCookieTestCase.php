<?php
/**
 * Tests for net::stubbles::ipo.response::stubCookie.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_response_test
 */
stubClassLoader::load('net::stubbles::ipo.response::stubCookie');
/**
 * Tests for net::stubbles::ipo.response::stubCookie.
 *
 * @package     stubbles
 * @subpackage  ipo_response_test
 * @group       ipo
 * @group       ipo_response
 */
class stubCookieTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test with default values
     *
     * @test
     */
    public function defaultValues()
    {
        $cookie = stubCookie::create('foo', 'bar');
        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());
        $this->assertEquals(0, $cookie->getExpiration());
        $this->assertNull($cookie->getPath());
        $this->assertNull($cookie->getDomain());
        $this->assertFalse($cookie->isSecure());
        $this->assertFalse($cookie->isHttpOnly());
    }
    
    /**
     * test that values are returned as expected
     *
     * @test
     */
    public function valuesSet()
    {
        $cookie = stubCookie::create('foo', 'bar')->expiringAt(100)
                                                  ->forPath('bar')
                                                  ->forDomain('.example.org')
                                                  ->withSecurity(true)
                                                  ->usingHttpOnly(true);
        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());
        $this->assertEquals(100, $cookie->getExpiration());
        $this->assertEquals('bar', $cookie->getPath());
        $this->assertEquals('.example.org', $cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
    }
}
?>