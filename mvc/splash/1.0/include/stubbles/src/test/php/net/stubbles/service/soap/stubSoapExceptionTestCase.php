<?php
/**
 * Test for net::stubbles::service::soap::stubSoapException.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_soap_test
 */
stubClassLoader::load('net::stubbles::service::soap::stubSoapException');
/**
 * Tests for net::stubbles::service::soap::stubSoapException.
 *
 * @package     stubbles
 * @subpackage  service_soap_test
 * @group       service_soap
 */
class stubSoapExceptionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * exception should handle a fault correctly
     *
     * @test
     */
    public function faultHandling()
    {
        $soapFault     = new stubSoapFault('code', 'string');
        $soapException = new stubSoapException($soapFault);
        $this->assertEquals('string', $soapException->getMessage());
        $this->assertSame($soapFault, $soapException->getSoapFault());
    }
}
?>