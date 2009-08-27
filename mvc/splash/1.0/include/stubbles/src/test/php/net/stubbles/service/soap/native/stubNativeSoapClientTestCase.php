<?php
/**
 * Test for net::stubbles::service::soap::native::stubNativeSoapClient.
 *
 * @author          Frank Kleine <mikey@stubbles.net>
 * @package         stubbles
 * @subpackage      service_soap_native_test
 */
stubClassLoader::load('net::stubbles::service::soap::native::stubNativeSoapClient');
/**
 * Tests for net::stubbles::service::soap::native::stubNativeSoapClient.
 *
 * @package     stubbles
 * @subpackage  service_soap_native_test
 * @group       service_soap
 */
class stubNativeSoapClientTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubSoapClientConfiguration
     */
    protected $soapClientConfig;

    /**
     * set up test environment
     */
    public function setUp()
    {
        if (extension_loaded('soap') === false) {
            $this->markTestSkipped('net::stubbles::service::soap::native::stubNativeSoapClient requires PHP-extension "soap".');
        }
        
        $this->soapClientConfig = new stubSoapClientConfiguration('http://user:password@example.net/soap.wsdl', 'http://example.org/');
    }

    /**
     * test that only valid versions are accepted
     *
     * @test
     */
    public function version()
    {
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertNull($client->getConfig()->getVersion());
        
        $this->soapClientConfig->setVersion(SOAP_1_1);
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_1_1, $client->getConfig()->getVersion());
        
        $this->soapClientConfig->setVersion(SOAP_1_2);
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_1_2, $client->getConfig()->getVersion());
    }

    /**
     * test that only valid versions are accepted
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function illegalVersion()
    {
        $this->soapClientConfig->setVersion('illegal');
        $client = new stubNativeSoapClient($this->soapClientConfig);
    }

    /**
     * test that only valid request styles are accepted
     *
     * @test
     */
    public function requestStyle()
    {
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_RPC, $client->getConfig()->getRequestStyle());
        
        $this->soapClientConfig->setRequestStyle(SOAP_RPC);
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_RPC, $client->getConfig()->getRequestStyle());
        
        $this->soapClientConfig->setRequestStyle(SOAP_DOCUMENT);
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_DOCUMENT, $client->getConfig()->getRequestStyle());
    }

    /**
     * test that only valid request styles are accepted
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function illegalRequestStyle()
    {
        $this->soapClientConfig->setRequestStyle('illegal');
        $client = new stubNativeSoapClient($this->soapClientConfig);
    }

    /**
     * test that only valid usage values are accepted
     *
     * @test
     */
    public function usage()
    {
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_ENCODED, $client->getConfig()->getUsage());
        
        $this->soapClientConfig->setUsage(SOAP_ENCODED);
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_ENCODED, $client->getConfig()->getUsage());
        
        $this->soapClientConfig->setUsage(SOAP_LITERAL);
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertSame($this->soapClientConfig, $client->getConfig());
        $this->assertEquals(SOAP_LITERAL, $client->getConfig()->getUsage());
    }

    /**
     * test that only valid usage values are accepted
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function illagelUsage()
    {
        $this->soapClientConfig->setUsage('illegal');
        $client = new stubNativeSoapClient($this->soapClientConfig);
    }

    /**
     * native client always supports WSDL
     *
     * @test
     */
    public function alwaysSupportsWSDL()
    {
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertTrue($client->supportsWSDL());
    }

    /**
     * before invocation of the soap method there should be no debug data
     *
     * @test
     */
    public function debugDataIsEmptyBeforeInvocation()
    {
        $client = new stubNativeSoapClient($this->soapClientConfig);
        $this->assertEquals(array(), $client->getDebugData());
    }
}
?>