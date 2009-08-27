<?php
/**
 * Tests for net::stubbles::websites::stubFrontController.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_test
 */
stubClassLoader::load('net::stubbles::websites::stubFrontController');
/**
 * Class that extends the front controller to make it testable.
 *
 * @package     stubbles
 * @subpackage  websites_test
 * @group       websites
 */
class TeststubFrontController extends stubFrontController
{
    /**
     * returns the created request instance
     *
     * @return  stubRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * returns the created response instance
     *
     * @return  stubResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * returns the created session instance
     *
     * @return  stubSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * sets the response instance
     *
     * @return  stubResponse
     */
    public function setResponse(stubResponse $response)
    {
        $this->response = $response;
    }
}
/**
 * Tests for net::stubbles::websites::stubFrontController.
 *
 * @package     stubbles
 * @subpackage  websites_test
 */
class stubFrontControllerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to be used for tests
     *
     * @var  TeststubFrontController
     */
    protected $frontController;
    /**
     * mocked interceptor initializer
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockInterceptorInitializer;
    /**
     * access to request
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequest;
    /**
     * access to session
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSession;
    /**
     * access to response
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse;
    /**
     * access to response
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockResponse2;
    /**
     * the mocked resolver
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockProcessorResolver;
    /**
     * the mocked processor
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockProcessor;
    /**
     * mocked website cache factory
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockWebsiteCacheFactory;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $mockWebsiteInitializer       = $this->getMock('stubWebsiteInitializer');
        $mockProcessorResolverFactory = $this->getMock('stubProcessorResolverFactory');
        $mockWebsiteInitializer->expects($this->any())->method('getProcessorResolverFactory')->will($this->returnValue($mockProcessorResolverFactory));
        $this->mockProcessorResolver  = $this->getMock('stubProcessorResolver');
        $this->mockProcessor          = $this->getMock('stubProcessor');
        $mockProcessorResolverFactory->expects($this->any())
                                     ->method('getResolver')
                                     ->will($this->returnValue($this->mockProcessorResolver));
        $this->mockProcessorResolver->expects($this->any())
                                    ->method('resolve')
                                    ->will($this->returnValue($this->mockProcessor));
        $this->mockInterceptorInitializer = $this->getMock('stubInterceptorInitializer');
        $mockWebsiteInitializer->expects($this->any())->method('getInterceptorInitializer')->will($this->returnValue($this->mockInterceptorInitializer));
        stubRegistry::setConfig(stubRequest::CLASS_REGISTRY_KEY, get_class($this->getMock('stubRequest')));
        stubRegistry::setConfig(stubResponse::CLASS_REGISTRY_KEY, get_class($this->getMock('stubResponse')));
        stubRegistry::setConfig(stubSession::CLASS_REGISTRY_KEY, get_class($this->getMock('stubSession')));
        $mockWebsiteInitializer->expects($this->any())->method('getRegistryInitializer')->will($this->returnValue($this->getMock('stubRegistryInitializer')));
        $mockWebsiteInitializer->expects($this->any())->method('hasGeneralInitializer')->will($this->returnValue(true));
        $generalInitializer = $this->getMock('stubInitializer');
        $generalInitializer->expects($this->any())->method('init');
        $mockWebsiteInitializer->expects($this->any())->method('getGeneralInitializer')->will($this->returnValue($generalInitializer));
        $this->frontController = new TeststubFrontController($mockWebsiteInitializer);
        
        $this->mockRequest  = $this->frontController->getRequest();
        $this->mockResponse = $this->frontController->getResponse();
        $this->mockSession  = $this->frontController->getSession();

        $this->mockWebsiteCacheFactory = $this->getMock('stubWebsiteCacheFactory');
        $this->mockWebsiteCacheFactory->expects($this->any())->method('configure')->will($this->returnValue($this->mockProcessor));
    }

    /**
     * assure that processing ends when request is cancelled
     *
     * @test
     */
    public function processWithAlreadyCancelledRequest()
    {
        $this->mockRequest->expects($this->once())->method('isCancelled')->will($this->returnValue(true));
        $this->mockResponse->expects($this->once())->method('send');
        $this->mockProcessor->expects($this->never())->method('forceSSL');
        $this->mockInterceptorInitializer->expects($this->never())->method('getPreInterceptors');
        $this->mockInterceptorInitializer->expects($this->never())->method('getPostInterceptors');
        $this->mockProcessor->expects($this->never())->method('getInterceptorDescriptor');
        $this->mockProcessor->expects($this->never())->method('process');
        $this->mockWebsiteCacheFactory->expects($this->never())->method('configure');
        $this->frontController->setWebsiteCacheFactory($this->mockWebsiteCacheFactory);
        $this->frontController->process();
    }

    /**
     * assure that processing ends when request is cancelled
     *
     * @test
     */
    public function processWithPreInterceptorCancellingRequest()
    {
        $this->mockProcessor->expects($this->any())->method('forceSSL')->will($this->returnValue(false));
        $preInterceptor1 = $this->getMock('stubPreInterceptor');
        $preInterceptor1->expects($this->once())->method('preProcess');
        $preInterceptor2 = $this->getMock('stubPreInterceptor');
        $preInterceptor2->expects($this->never())->method('preProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPreInterceptors')
                                         ->will($this->returnValue(array($preInterceptor1, $preInterceptor2)));
        $this->mockInterceptorInitializer->expects($this->never())->method('getPostInterceptors');
        $this->mockRequest->expects($this->exactly(2))->method('isCancelled')->will($this->onConsecutiveCalls(false, true));
        $this->mockResponse->expects($this->once())->method('send');
        $this->mockProcessor->expects($this->once())
                            ->method('getInterceptorDescriptor')
                            ->will($this->returnValue('interceptors'));
        $this->mockProcessor->expects($this->never())->method('process');
        $this->mockWebsiteCacheFactory->expects($this->never())->method('configure');
        $this->frontController->setWebsiteCacheFactory($this->mockWebsiteCacheFactory);
        $this->frontController->process();
    }

    /**
     * assure that processing ends when request is cancelled
     *
     * @test
     */
    public function processWithProcessorCancellingRequest()
    {
        $this->mockProcessor->expects($this->any())->method('forceSSL')->will($this->returnValue(false));
        $postInterceptor1 = $this->getMock('stubPostInterceptor');
        $postInterceptor1->expects($this->never())->method('postProcess');
        $postInterceptor2 = $this->getMock('stubPostInterceptor');
        $postInterceptor2->expects($this->never())->method('postProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPreInterceptors')
                                         ->will($this->returnValue(array()));
        $this->mockInterceptorInitializer->expects($this->never())
                                         ->method('getPostInterceptors');
        $this->mockRequest->expects($this->exactly(2))->method('isCancelled')->will($this->onConsecutiveCalls(false, true));
        $this->mockProcessor->expects($this->any())->method('process');
        $this->mockResponse->expects($this->once())->method('send');
        $this->frontController->process();
    }

    /**
     * assure that processing ends when request is cancelled
     *
     * @test
     */
    public function processWithPostInterceptorCancellingRequest()
    {
        $this->mockProcessor->expects($this->any())->method('forceSSL')->will($this->returnValue(false));
        $postInterceptor1 = $this->getMock('stubPostInterceptor');
        $postInterceptor1->expects($this->once())->method('postProcess');
        $postInterceptor2 = $this->getMock('stubPostInterceptor');
        $postInterceptor2->expects($this->never())->method('postProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPreInterceptors')
                                         ->will($this->returnValue(array()));
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPostInterceptors')
                                         ->will($this->returnValue(array($postInterceptor1, $postInterceptor2)));
        $this->mockRequest->expects($this->exactly(3))
                          ->method('isCancelled')
                          ->will($this->onConsecutiveCalls(false, false, true));
        $this->mockProcessor->expects($this->any())->method('process');
        $this->mockResponse->expects($this->once())->method('send');
        $this->frontController->process();
    }

    /**
     * assure that processing ends when request is cancelled
     *
     * @test
     */
    public function processWithoutCancellingRequest()
    {
        $this->mockProcessor->expects($this->any())->method('forceSSL')->will($this->returnValue(false));
        $preInterceptor1 = $this->getMock('stubPreInterceptor');
        $preInterceptor1->expects($this->once())->method('preProcess');
        $preInterceptor2 = $this->getMock('stubPreInterceptor');
        $preInterceptor2->expects($this->once())->method('preProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPreInterceptors')
                                         ->will($this->returnValue(array($preInterceptor1, $preInterceptor2)));
        $postInterceptor1 = $this->getMock('stubPostInterceptor');
        $postInterceptor1->expects($this->once())->method('postProcess');
        $postInterceptor2 = $this->getMock('stubPostInterceptor');
        $postInterceptor2->expects($this->once())->method('postProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPostInterceptors')
                                         ->will($this->returnValue(array($postInterceptor1, $postInterceptor2)));
        $this->mockRequest->expects($this->any())->method('isCancelled')->will($this->returnValue(false));
        $this->mockProcessor->expects($this->any())->method('process');
        $this->mockResponse->expects($this->once())->method('send');
        $this->mockWebsiteCacheFactory->expects($this->once())->method('configure');
        $this->frontController->setWebsiteCacheFactory($this->mockWebsiteCacheFactory);
        $this->frontController->process();
    }

    /**
     * assure that processing ends when request is cancelled
     *
     * @test
     */
    public function forcesSSLAndIsSSL()
    {
        $this->mockProcessor->expects($this->any())->method('forceSSL')->will($this->returnValue(true));
        $this->mockProcessor->expects($this->any())->method('isSSL')->will($this->returnValue(true));
        $preInterceptor1 = $this->getMock('stubPreInterceptor');
        $preInterceptor1->expects($this->once())->method('preProcess');
        $preInterceptor2 = $this->getMock('stubPreInterceptor');
        $preInterceptor2->expects($this->once())->method('preProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPreInterceptors')
                                         ->will($this->returnValue(array($preInterceptor1, $preInterceptor2)));
        $postInterceptor1 = $this->getMock('stubPostInterceptor');
        $postInterceptor1->expects($this->once())->method('postProcess');
        $postInterceptor2 = $this->getMock('stubPostInterceptor');
        $postInterceptor2->expects($this->once())->method('postProcess');
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPostInterceptors')
                                         ->will($this->returnValue(array($postInterceptor1, $postInterceptor2)));
        $this->mockRequest->expects($this->any())->method('isCancelled')->will($this->returnValue(false));
        $this->mockProcessor->expects($this->any())->method('process');
        $this->mockResponse->expects($this->once())->method('send');
        $this->mockWebsiteCacheFactory->expects($this->once())->method('configure');
        $this->frontController->setWebsiteCacheFactory($this->mockWebsiteCacheFactory);
        $this->frontController->process();
    }

    /**
     * redirect to ssl if required
     *
     * @test
     */
    public function forcesSSLButIsNotSSL()
    {
        $this->mockProcessor->expects($this->any())->method('forceSSL')->will($this->returnValue(true));
        $this->mockProcessor->expects($this->any())->method('isSSL')->will($this->returnValue(false));
        $this->mockInterceptorInitializer->expects($this->once())
                                         ->method('getPreInterceptors')
                                         ->will($this->returnValue(array()));
        $this->mockInterceptorInitializer->expects($this->never())
                                         ->method('getPostInterceptors');
        $this->mockProcessor->expects($this->never())->method('process');
        $this->mockResponse->expects($this->once())->method('addHeader');
        $this->mockResponse->expects($this->once())->method('send');
        $this->frontController->process();
    }

    /**
     * wrong session class throws an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function wrongRequestClass()
    {
        stubRegistry::setConfig(stubRequest::CLASS_REGISTRY_KEY, 'stdClass');
        $mockWebsiteInitializer = $this->getMock('stubWebsiteInitializer');
        $mockWebsiteInitializer->expects($this->once())->method('getRegistryInitializer')->will($this->returnValue($this->getMock('stubRegistryInitializer')));
        $mockWebsiteInitializer->expects($this->once())->method('hasGeneralInitializer')->will($this->returnValue(false));
        $frontController = new stubFrontController($mockWebsiteInitializer);
    }

    /**
     * wrong response class throws an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function wrongResponseClass()
    {
        stubRegistry::setConfig(stubResponse::CLASS_REGISTRY_KEY, 'stdClass');
        $mockWebsiteInitializer = $this->getMock('stubWebsiteInitializer');
        $mockWebsiteInitializer->expects($this->once())->method('getRegistryInitializer')->will($this->returnValue($this->getMock('stubRegistryInitializer')));
        $mockWebsiteInitializer->expects($this->once())->method('hasGeneralInitializer')->will($this->returnValue(false));
        $frontController = new stubFrontController($mockWebsiteInitializer);
    }

    /**
     * wrong session class throws an exception
     *
     * @test
     * @expectedException  stubRuntimeException
     */
    public function wrongSessionClass()
    {
        stubRegistry::setConfig(stubRequest::CLASS_REGISTRY_KEY, get_class($this->getMock('stubRequest')));
        stubRegistry::setConfig(stubSession::CLASS_REGISTRY_KEY, 'stdClass');
        $mockWebsiteInitializer = $this->getMock('stubWebsiteInitializer');
        $mockWebsiteInitializer->expects($this->once())->method('getRegistryInitializer')->will($this->returnValue($this->getMock('stubRegistryInitializer')));
        $mockWebsiteInitializer->expects($this->once())->method('hasGeneralInitializer')->will($this->returnValue(false));
        $frontController = new stubFrontController($mockWebsiteInitializer);
    }
}
?>