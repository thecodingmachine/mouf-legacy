<?php
/**
 * Test for net::stubbles::service::soap::stubSoapClientGenerator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap_test
 */
stubClassLoader::load('net::stubbles::service::soap::stubSoapClientGenerator',
                      'net::stubbles::service::soap::stubAbstractSoapClient'
);
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 */
class WsdlSoapClient extends stubAbstractSoapClient
{
    /**
     * switch whether wsdl is supported or not
     *
     * @var  bool
     */
    protected $supportsWSDL;

    /**
     * checks whether the client supports WSDL or not
     *
     * @return  bool
     */
    public function supportsWSDL()
    {
        return $this->supportsWSDL;
    }

    /**
     * invoke method call
     *
     * @param   string  $method  name of method to invoke
     * @param   array   $args    list of arguments for method
     * @return  mixed
     * @throws  stubSoapException
     */
    public function invoke($method, array $args = array()) { }
}
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 */
class TrueWsdlSoapClient extends WsdlSoapClient
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->supportsWSDL = true;
    }
}
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 */
class FalseWsdlSoapClient extends WsdlSoapClient
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->supportsWSDL = false;
    }
}
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 */
class FailsOnConstruction extends WsdlSoapClient
{
    /**
     * constructor
     *
     * @throws  Exception
     */
    public function __construct()
    {
        throw new Exception();
    }
}
/**
 * Tests for net::stubbles::service::soap::stubSoapClientGenerator.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 * @group       service_soap
 */
class stubSoapClientGeneratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * temporary client store
     *
     * @var  rray<string,string|ReflectionClass>
     */
    protected $clients;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->clients = stubSoapClientGenerator::getInstance()->getAvailableClients();
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        stubSoapClientGenerator::getInstance()->setAvailableClients($this->clients);
    }

    /**
     * assert that always the same instance is delivered
     *
     * @test
     */
    public function instanceAlwaysTheSame()
    {
        $this->assertSame(stubSoapClientGenerator::getInstance(), stubSoapClientGenerator::getInstance());
    }

    /**
     * clone throws a runtime exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function cloneIsForbidden()
    {
        $generator = clone stubSoapClientGenerator::getInstance();
    }

    /**
     * exception should handle a fault correctly
     *
     * @test
     */
    public function nativeSoapClientAvailableByDefault()
    {
        if (extension_loaded('soap') === false) {
            $this->markTestSkipped('Native soap client not enabled as soap extension is not available.');
        }
        
        $config    = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $generator = stubSoapClientGenerator::getInstance();
        $this->assertEquals(array('net::stubbles::service::soap::native::stubNativeSoapClient' => 'net::stubbles::service::soap::native::stubNativeSoapClient'), $generator->getAvailableClients());
        $client = $generator->forConfig($config);
        $this->assertType('stubNativeSoapClient', $client);
        $this->assertSame($config, $client->getConfig());
    }

    /**
     * in case no client is available a runtime exception is thrown
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function noClientAvailableThrowsRuntimeException()
    {
        $generator = stubSoapClientGenerator::getInstance();
        $generator->removeClient('net::stubbles::service::soap::native::stubNativeSoapClient');
        $this->assertEquals(array(), $generator->getAvailableClients());
        $config = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $client = $generator->forConfig($config);
    }

    /**
     * adding an invalid client class throws an illegal argument exception
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function addInvalidClientThrowsIllegalArgumentException()
    {
        $generator = stubSoapClientGenerator::getInstance();
        $generator->addClient(new ReflectionClass('stdClass'));
    }

    /**
     * make sure the correct client will be used
     *
     * @test
     */
    public function wsdlBehaviour()
    {
        $generator = stubSoapClientGenerator::getInstance();
        $generator->removeClient('net::stubbles::service::soap::native::stubNativeSoapClient');
        $generator->addClient(new ReflectionClass('FailsOnConstruction'));
        $generator->addClient(new ReflectionClass('FalseWsdlSoapClient'));
        $generator->addClient(new ReflectionClass('TrueWsdlSoapClient'));
        $config = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $config->useWSDL(true);
        $client = $generator->forConfig($config, true);
        $this->assertType('TrueWsdlSoapClient', $client);
    }

    /**
     * if no suited client is available a runtime exception is thrown
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function wsdlBehaviourNoWsdlSupportAvailable()
    {
        $generator = stubSoapClientGenerator::getInstance();
        $generator->removeClient('net::stubbles::service::soap::native::stubNativeSoapClient');
        $generator->addClient(new ReflectionClass('FailsOnConstruction'));
        $generator->addClient(new ReflectionClass('FalseWsdlSoapClient'));
        $config = new stubSoapClientConfiguration('http://example.net/', 'urn:foo');
        $config->useWSDL(true);
        $client = $generator->forConfig($config, true);
    }
}
?>