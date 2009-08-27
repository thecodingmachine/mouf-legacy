<?php
/**
 * Test for net::stubbles::websites::variantmanager::stubVariantsPreInterceptor.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
stubClassLoader::load('net::stubbles::websites::variantmanager::stubVariantsPreInterceptor',
                      'net::stubbles::websites::variantmanager::stubVariantFactory',
                      'net::stubbles::websites::variantmanager::stubVariantsMap',
                      'net::stubbles::websites::variantmanager::types::stubVariant'
);
/**
 * Helper class for the test.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 */
class TestVariantsPreInterceptor extends stubVariantsPreInterceptor
{
    /**
     * returns the cookie variant
     *
     * @param   stubRequest         $request
     * @param   stubSession         $session
     * @param   stubVariantFactory  $variantFactory
     * @param   string              $cookieName
     * @return  stubVariant
     */
    public function getCookieVariant(stubRequest $request, stubSession $session, stubVariantFactory $variantFactory, $cookieName)
    {
        return $this->getVariantFromCookie($request, $session, $variantFactory, $cookieName);
    }

    /**
     * returns the created cookie
     *
     * @param   string      $cookieName
     * @param   string      $variantName
     * @param   int         $expiring
     * @param   string      $cookieURL
     * @param   string      $cookiePath
     * @return  stubCookie
     */
    public function callCreateCookie($cookieName, $variantName, $expiring, $cookieURL, $cookiePath)
    {
        return $this->createCookie($cookieName, $variantName, $expiring, $cookieURL, $cookiePath);
    }
}
/**
 * Test for net::stubbles::websites::variantmanager::stubVariantsPreInterceptor.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubVariantsPreInterceptorCookieVariantTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * the instance to test
     *
     * @var  stubVariantsPreInterceptor
     */
    protected $variantPreInterceptor;
    /**
     * the mocked request
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * the mocked session
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * a mocked variant factory
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockVariantFactory;
    /**
     * a mocked variants map
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockVariantsMap;
    
    /**
     * set up the test environment
     */
    public function setUp()
    {
        $this->variantPreInterceptor = new TestVariantsPreInterceptor();
        $this->mockSession           = $this->getMock('stubSession');
        $this->mockRequest           = $this->getMock('stubRequest');
        $this->mockVariantFactory    = $this->getMock('stubVariantFactory');
        $this->mockVariantsMap       = $this->getMock('stubVariantsMap');
        $this->mockVariantFactory->expects($this->any())
                                 ->method('getVariantsMap')
                                 ->will($this->returnValue($this->mockVariantsMap));
    }

    /**
     * assure that no cookie returns no variant
     *
     * @test
     */
    public function noCookie()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->assertNull($this->variantPreInterceptor->getCookieVariant($this->mockRequest, $this->mockSession, $this->mockVariantFactory, 'variant'));
    }
    
    /**
     * assure that an invalid cookie will not be used anymore
     *
     * @test
     */
    public function invalidCookie()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue(null));
        $this->mockVariantFactory->expects($this->once())->method('getVariantNames')->will($this->returnValue(array()));
        $this->assertNull($this->variantPreInterceptor->getCookieVariant($this->mockRequest, $this->mockSession, $this->mockVariantFactory, 'variant'));
    }
    
    /**
     * assure that a valid cookie will be used when no enforcing variant is set
     *
     * @test
     */
    public function validCookieWithoutEnforcingVariant()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('fooVariant'));
        $this->mockVariantFactory->expects($this->once())->method('getVariantNames')->will($this->returnValue(array()));
        $fooVariant = $this->getMock('stubVariant');
        $fooVariant->expects($this->any())->method('getName')->will($this->returnValue('fooVariant'));
        $this->mockVariantFactory->expects($this->once())->method('getVariantByName')->will($this->returnValue($fooVariant));
        $this->mockVariantsMap->expects($this->once())->method('getEnforcingVariant')->will($this->returnValue(null));
        $resultVariant = $this->variantPreInterceptor->getCookieVariant($this->mockRequest, $this->mockSession, $this->mockVariantFactory, 'variant');
        $this->assertSame($fooVariant, $resultVariant);
    }
    
    /**
     * assure that a valid cookie will be used when enforcing variant is not a parent variant
     *
     * @test
     */
    public function validCookieWithInvalidEnforcingVariant()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('fooVariant'));
        $this->mockVariantFactory->expects($this->once())->method('getVariantNames')->will($this->returnValue(array()));
        $fooVariant = $this->getMock('stubVariant');
        $fooVariant->expects($this->any())->method('getName')->will($this->returnValue('fooVariant'));
        $this->mockVariantFactory->expects($this->once())->method('getVariantByName')->will($this->returnValue($fooVariant));
        $barVariant = $this->getMock('stubVariant');
        $barVariant->expects($this->any())->method('getName')->will($this->returnValue('barVariant'));
        $this->mockVariantsMap->expects($this->once())->method('getEnforcingVariant')->will($this->returnValue($barVariant));
        $resultVariant = $this->variantPreInterceptor->getCookieVariant($this->mockRequest, $this->mockSession, $this->mockVariantFactory, 'variant');
        $this->assertSame($fooVariant, $resultVariant);
    }
    
    /**
     * assure that a enforcing variant will be used if set and cookie is valid
     *
     * @test
     */
    public function validCookieWithValidEnforcingVariant()
    {
        $this->mockRequest->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockRequest->expects($this->once())->method('getValidatedValue')->will($this->returnValue('fooVariant'));
        $this->mockVariantFactory->expects($this->once())->method('getVariantNames')->will($this->returnValue(array()));
        $fooVariant = $this->getMock('stubVariant');
        $fooVariant->expects($this->any())->method('getName')->will($this->returnValue('fooVariant'));
        $this->mockVariantFactory->expects($this->once())->method('getVariantByName')->will($this->returnValue($fooVariant));
        $barVariant = $this->getMock('stubVariant');
        $barVariant->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $this->mockVariantsMap->expects($this->once())->method('getEnforcingVariant')->will($this->returnValue($barVariant));
        $resultVariant = $this->variantPreInterceptor->getCookieVariant($this->mockRequest, $this->mockSession, $this->mockVariantFactory, 'variant');
        $this->assertSame($barVariant, $resultVariant);
    }

    /**
     * make sure cookie is created correct
     *
     * @test
     */
    public function createCookie()
    {
        $cookie = $this->variantPreInterceptor->callCreateCookie('name', 'value', 3600, 'url', 'path');
        $this->assertEquals('name', $cookie->getName());
        $this->assertEquals('value', $cookie->getValue());
        $this->assertGreaterThan(time() + 3550, $cookie->getExpiration());
        $this->assertEquals('url', $cookie->getDomain());
        $this->assertEquals('path', $cookie->getPath());
    }
}
?>