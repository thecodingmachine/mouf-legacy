<?php
/**
 * Test for net::stubbles::service::soap::stubSoapClientConfiguration.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap_test
 */
stubClassLoader::load('net::stubbles::service::soap::stubSoapClientConfiguration');
/**
 * Tests for net::stubbles::service::soap::stubSoapClientConfiguration.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 * @group       service_soap
 */
class stubSoapClientConfigurationTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * test that only strings and instances of net::stubbles::peer::http::stubHTTPURL
     * are accepted as value for $endPoint
     *
     * @test
     */
    public function endPointAsString()
    {
        $config   = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $endPoint = $config->getEndPoint();
        $this->assertType('stubHTTPURL', $endPoint);
        $this->assertEquals('http://example.net/', $endPoint->get());
        $this->assertEquals('urn:foo', $config->getURI());
    }

    /**
     * test that only strings and instances of net::stubbles::peer::http::stubHTTPURL
     * are accepted as value for $endPoint
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function illegalEndPointAsString()
    {
        $config = new stubSoapClientConfiguration('', 'urn:foo');
    }

    /**
     * test that only strings and instances of net::stubbles::peer::http::stubHTTPURL
     * are accepted as value for $endPoint
     *
     * @test
     */
    public function endPointAsHTTPURLInstance()
    {
        $endPoint = stubHTTPURL::fromString('http://example.net/');
        $config   = new stubSoapClientConfiguration($endPoint, 'urn:foo');
        $test     = $config->getEndPoint();
        $this->assertSame($endPoint, $test);
        $this->assertEquals('urn:foo', $config->getURI());
    }

    /**
     * test that only strings and instances of net::stubbles::peer::http.stubHTTPURL
     * are accepted as value for $endPoint
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function endPointInvalidInstance()
    {
        $config = new stubSoapClientConfiguration(new stdClass(), 'urn:foo');
    }

    /**
     * wsdl defaults to false, but may be reconfigured
     *
     * @test
     */
    public function wsdl()
    {
        $config = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $this->assertFalse($config->usesWSDL());
        $config->useWSDL(true);
        $this->assertTrue($config->usesWSDL());
    }

    /**
     * data encoding defaults to iso-8859-1, but may be reconfigured
     *
     * @test
     */
    public function dataEncoding()
    {
        $config = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $this->assertEquals('iso-8859-1', $config->getDataEncoding());
        $config->setDataEncoding('utf-8');
        $this->assertEquals('utf-8', $config->getDataEncoding());
    }

    /**
     * assert that class mapping methods work as expected
     *
     * @test
     */
    public function classMapping()
    {
        $config = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $this->assertFalse($config->hasClassMapping());
        $this->assertEquals(array(), $config->getClassMapping());
        $config->registerClassMapping('foo', new ReflectionClass('stdClass'));
        $this->assertTrue($config->hasClassMapping());
        $this->assertEquals(array('foo' => 'stdClass'), $config->getClassMapping());
    }
}
?>