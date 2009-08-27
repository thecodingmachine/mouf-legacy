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

class TestInvalidVariantFactory {}
/**
 * Test for net::stubbles::websites::variantmanager::stubVariantsPreInterceptor.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_test
 * @group       websites
 * @group       websites_variantmanager
 */
class stubVariantsPreInterceptorProcessTestCase extends PHPUnit_Framework_TestCase
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
     * the mocked response
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
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
        $this->variantPreInterceptor = $this->getMock('stubVariantsPreInterceptor',
                                                      array('createVariantFactory',
                                                            'createCookie',
                                                            'getVariantFromCookie'
                                                      )
                                       );
        $this->mockSession           = $this->getMock('stubSession');
        $this->mockRequest           = $this->getMock('stubRequest');
        $this->mockResponse          = $this->getMock('stubResponse');
        $this->mockVariantFactory    = $this->getMock('stubVariantFactory');
        $this->mockVariantsMap       = $this->getMock('stubVariantsMap');
        $this->mockVariantFactory->expects($this->any())
                                 ->method('getVariantsMap')
                                 ->will($this->returnValue($this->mockVariantsMap));
        stubRegistry::setConfig('net.stubbles.websites.variantmanager.variantfactory.class', get_class($this->mockVariantFactory));
    }

    /**
     * assure that an existing variant will not be overwritten by another variant
     *
     * @test
     */
    public function variantAlreadyExists()
    {
        $this->mockSession->expects($this->once())->method('hasValue')->will($this->returnValue(true));
        $this->mockSession->expects($this->never())->method('putValue');
        $this->mockResponse->expects($this->never())->method('setCookie');
        $this->variantPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
    
    /**
     * assure that an invalid variant factory classname configurationt throws a stubVariantConfigurationException
     *
     * @test
     * @expectedException  stubVariantConfigurationException
     */
    public function invalidVariantFactory()
    {
        $this->mockSession->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->never())->method('putValue');
        $this->mockResponse->expects($this->never())->method('setCookie');
        stubRegistry::setConfig('net.stubbles.websites.variantmanager.variantfactory.class', 'TestInvalidVariantFactory');
        $this->variantPreInterceptor->expects($this->once())->method('createVariantFactory')->will($this->returnValue(new TestInvalidVariantFactory()));
        $this->variantPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
    
    /**
     * assure that no variant cookie triggers a new variant
     *
     * @test
     */
    public function noVariantCookieSet()
    {
        $mockNewVariant = $this->getMock('stubVariant');
        $mockNewVariant->expects($this->any())->method('getName')->will($this->returnValue('variantName'));
        $mockNewVariant->expects($this->any())->method('getAlias')->will($this->returnValue('variantAlias'));
        $mockNewVariant->expects($this->any())->method('getFullQualifiedName')->will($this->returnValue('foo:variantName'));
        $this->mockSession->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->at(1))
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.variantmanager.variant.name'),
                                 $this->equalTo('foo:variantName')
                            );
        $this->mockSession->expects($this->at(2))
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.variantmanager.variant.alias'),
                                 $this->equalTo('variantAlias')
                            );
        $this->mockResponse->expects($this->once())->method('setCookie');
        $this->variantPreInterceptor->expects($this->once())->method('createVariantFactory')->will($this->returnValue($this->mockVariantFactory));
        $this->variantPreInterceptor->expects($this->once())->method('getVariantFromCookie')->will($this->returnValue(null));
        $this->mockVariantsMap->expects($this->once())->method('shouldUsePersistence')->will($this->returnValue(true));
        $this->mockVariantsMap->expects($this->once())->method('getVariant')->will($this->returnValue($mockNewVariant));
        $this->variantPreInterceptor->expects($this->once())
                                    ->method('createCookie')
                                    ->with($this->equalTo('variant'),
                                           $this->equalTo('variantName'),
                                           $this->anything(),
                                           $this->equalTo(null),
                                           $this->equalTo('/')
                                      )
                                    ->will($this->returnValue(stubCookie::create('variant', 'variantName')));
        $this->variantPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
    
    /**
     * assure that disabled persistence triggers a new variant
     *
     * @test
     */
    public function noPersistence()
    {
        $mockNewVariant = $this->getMock('stubVariant');
        $mockNewVariant->expects($this->any())->method('getName')->will($this->returnValue('new'));
        $mockNewVariant->expects($this->any())->method('getAlias')->will($this->returnValue('fresh'));
        $mockNewVariant->expects($this->any())->method('getFullQualifiedName')->will($this->returnValue('foo:new'));
        $mockCookieVariant = $this->getMock('stubVariant');
        $mockCookieVariant->expects($this->any())->method('getName')->will($this->returnValue('cookie'));
        $mockCookieVariant->expects($this->any())->method('getAlias')->will($this->returnValue('notthatfresh'));
        $mockCookieVariant->expects($this->any())->method('getFullQualifiedName')->will($this->returnValue('foo:cookie'));
        $this->mockSession->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->at(1))
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.variantmanager.variant.name'),
                                 $this->equalTo('foo:new')
                            );
        $this->mockSession->expects($this->at(2))
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.variantmanager.variant.alias'),
                                 $this->equalTo('fresh')
                            );
        $this->mockResponse->expects($this->once())->method('setCookie');
        $this->variantPreInterceptor->expects($this->once())->method('createVariantFactory')->will($this->returnValue($this->mockVariantFactory));
        $this->variantPreInterceptor->expects($this->never())->method('getVariantFromCookie');
        $this->mockVariantsMap->expects($this->once())->method('shouldUsePersistence')->will($this->returnValue(false));
        $this->mockVariantsMap->expects($this->once())->method('getVariant')->will($this->returnValue($mockNewVariant));
        $this->variantPreInterceptor->expects($this->once())
                                    ->method('createCookie')
                                    ->with($this->equalTo('variant'),
                                           $this->equalTo('new'),
                                           $this->anything(),
                                           $this->equalTo('example.org'),
                                           $this->equalTo('/path/')
                                      )
                                    ->will($this->returnValue(stubCookie::create('variant', 'new')));
        stubRegistry::setConfig('net.stubbles.websites.variantmanager.cookie.url', 'example.org');
        stubRegistry::setConfig('net.stubbles.websites.variantmanager.cookie.path', '/path/');
        $this->variantPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
    
    /**
     * assure that a cookie variant is used
     *
     * @test
     */
    public function variantCookieSet()
    {
        $mockNewVariant = $this->getMock('stubVariant');
        $mockNewVariant->expects($this->any())->method('getName')->will($this->returnValue('new'));
        $mockNewVariant->expects($this->any())->method('getAlias')->will($this->returnValue('fresh'));
        $mockNewVariant->expects($this->any())->method('getFullQualifiedName')->will($this->returnValue('foo:new'));
        $mockCookieVariant = $this->getMock('stubVariant');
        $mockCookieVariant->expects($this->any())->method('getName')->will($this->returnValue('cookie'));
        $mockCookieVariant->expects($this->any())->method('getAlias')->will($this->returnValue('notthatfresh'));
        $mockCookieVariant->expects($this->any())->method('getFullQualifiedName')->will($this->returnValue('foo:cookie'));
        $this->mockSession->expects($this->once())->method('hasValue')->will($this->returnValue(false));
        $this->mockSession->expects($this->at(1))
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.variantmanager.variant.name'),
                                 $this->equalTo('foo:cookie')
                            );
        $this->mockSession->expects($this->at(2))
                          ->method('putValue')
                          ->with($this->equalTo('net.stubbles.websites.variantmanager.variant.alias'),
                                 $this->equalTo('notthatfresh')
                            );
        $this->mockResponse->expects($this->once())->method('setCookie');
        $this->variantPreInterceptor->expects($this->once())->method('createVariantFactory')->will($this->returnValue($this->mockVariantFactory));
        $this->variantPreInterceptor->expects($this->once())->method('getVariantFromCookie')->will($this->returnValue($mockCookieVariant));
        $this->mockVariantsMap->expects($this->once())->method('shouldUsePersistence')->will($this->returnValue(true));
        $this->mockVariantsMap->expects($this->never())->method('getVariant');
        $this->variantPreInterceptor->expects($this->once())
                                    ->method('createCookie')
                                    ->with($this->equalTo('variant'),
                                           $this->equalTo('cookie'),
                                           $this->anything(),
                                           $this->equalTo('example.org'),
                                           $this->equalTo('/path/')
                                      )
                                    ->will($this->returnValue(stubCookie::create('variant', 'new')));
        stubRegistry::setConfig('net.stubbles.websites.variantmanager.cookie.url', 'example.org');
        stubRegistry::setConfig('net.stubbles.websites.variantmanager.cookie.path', '/path/');
        $this->variantPreInterceptor->preProcess($this->mockRequest, $this->mockSession, $this->mockResponse);
    }
}
?>