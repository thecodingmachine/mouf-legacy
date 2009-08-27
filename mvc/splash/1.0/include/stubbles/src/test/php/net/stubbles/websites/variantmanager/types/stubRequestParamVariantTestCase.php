<?php
/**
 * Test for net::stubbles::websites::variantmanager::types::stubRequestParamVariant.
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_types_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::types::stubRequestParamVariant');
/**
 * Test for net::stubbles::websites::variantmanager::types::stubRequestParamVariant.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_types_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubRequestParamVariantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * the instance to test
     *
     * @var  stubRequestParamVariant
     */
    protected $requestParamVariant;
    /**
     * the mocked session
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * the mocked request
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->requestParamVariant = new stubRequestParamVariant();
        $this->mockSession         = $this->getMock('stubSession');
        $this->mockRequest         = $this->getMock('stubRequest');
    }
    
    /**
     * test that a non-set param name throws an exception
     *
     * @test
     * @expectedException  stubVariantConfigurationException
     */
    public function withoutParamName()
    {
        $this->requestParamVariant->isValid($this->mockSession, $this->mockRequest);
    }
    
    /**
     * test a non-set request param
     *
     * @test
     */
    public function withoutParamSet()
    {
        $this->requestParamVariant->setParamName('param');
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(false));
        $this->assertFalse($this->requestParamVariant->isValid($this->mockSession, $this->mockRequest));
        $this->assertFalse($this->requestParamVariant->isEnforcing($this->mockSession, $this->mockRequest));
    }

    /**
     * test without a param value but request param set
     *
     * @test
     */
    public function withoutParamValue()
    {
        $this->requestParamVariant->setParamName('param');
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(true));
        $this->assertTrue($this->requestParamVariant->isValid($this->mockSession, $this->mockRequest));
        $this->assertTrue($this->requestParamVariant->isEnforcing($this->mockSession, $this->mockRequest));
    }
    
    /**
     * test with a wrong param value
     *
     * @test
     */
    public function withWrongParamValue()
    {
        $this->requestParamVariant->setParamName('param');
        $this->requestParamVariant->setParamValue('foo');
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->any())->method('validateValue')->will($this->returnValue(false));
        $this->assertFalse($this->requestParamVariant->isValid($this->mockSession, $this->mockRequest));
        $this->assertFalse($this->requestParamVariant->isEnforcing($this->mockSession, $this->mockRequest));
    }

    /**
     * test with correct param value
     *
     * @test
     */
    public function withCorrectParamValue()
    {
        $this->requestParamVariant->setParamName('param');
        $this->requestParamVariant->setParamValue('foo');
        $this->mockRequest->expects($this->any())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->any())->method('validateValue')->will($this->returnValue(true));
        $this->assertTrue($this->requestParamVariant->isValid($this->mockSession, $this->mockRequest));
        $this->assertTrue($this->requestParamVariant->isEnforcing($this->mockSession, $this->mockRequest));
    }
}
?>