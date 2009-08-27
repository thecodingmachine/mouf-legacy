<?php
/**
 * The front controller for websites.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::stubWebsiteInitializer',
                      'net::stubbles::websites::cache::stubWebsiteCacheFactory'
);
/**
 * The front controller for websites.
 * 
 * @package     stubbles
 * @subpackage  websites
 */
class stubFrontController extends stubBaseObject
{
    /**
     * initializer
     *
     * @var  stubWebsiteInitializer
     */
    protected $websiteInitializer;
    /**
     * contains request data
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * session container
     *
     * @var  stubSession
     */
    protected $session;
    /**
     * response container
     *
     * @var  stubResponse
     */
    protected $response;
    /**
     * factory for the website cache
     *
     * @var  stubWebsiteCacheFactory
     */
    protected $websiteCacheFactory;

    /**
     * constructor
     * 
     * @param  stubWebsiteInitializer  $websiteInitializer  initializer to init basic stuff
     */
    public function __construct(stubWebsiteInitializer $websiteInitializer)
    {
        $websiteInitializer->init();
        $websiteInitializer->getRegistryInitializer()->init();
        if ($websiteInitializer->hasGeneralInitializer() === true) {
            $websiteInitializer->getGeneralInitializer()->init();
        }
        
        $this->websiteInitializer = $websiteInitializer;
        $this->createInstances();
    }

    /**
     * creates the required instances
     *
     * @throws  stubRuntimeException
     */
    protected function createInstances()
    {
        $fqClassName = stubRegistry::getConfig(stubRequest::CLASS_REGISTRY_KEY, 'net::stubbles::ipo::request::stubWebRequest');
        $className   = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($className, false) === false) {
            stubClassLoader::load($fqClassName);
        }
        
        $this->request = new $className();
        if (($this->request instanceof stubRequest) === false) {
            throw new stubRuntimeException('Configured request class is not an instance of net::stubbles::ipo::request::stubRequest.');
        }

        $fqClassName = stubRegistry::getConfig(stubResponse::CLASS_REGISTRY_KEY, 'net::stubbles::ipo::response::stubBaseResponse');
        $className   = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($className, false) === false) {
            stubClassLoader::load($fqClassName);
        }
        
        $this->response = new $className();
        if (($this->response instanceof stubResponse) === false) {
            throw new stubRuntimeException('Configured response class is not an instance of net::stubbles::ipo::response::stubResponse.');
        }
        
        $fqClassName = stubRegistry::getConfig(stubSession::CLASS_REGISTRY_KEY, 'net::stubbles::ipo::session::stubPHPSession');
        $className   = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($className, false) === false) {
            stubClassLoader::load($fqClassName);
        }
        
        $this->session = new $className($this->request, $this->response, stubRegistry::getConfig(stubSession::NAME_REGISTRY_KEY, stubSession::DEFAULT_SESSION_NAME));
        if (($this->session instanceof stubSession) === false) {
            throw new stubRuntimeException('Configured session class is not an instance of net::stubbles::ipo::session::stubSession.');
        }
    }

    /**
     * sets the website cache factory to be used
     *
     * @param  stubWebsiteCacheFactory  $websiteCacheFactory
     */
    public function setWebsiteCacheFactory(stubWebsiteCacheFactory $websiteCacheFactory)
    {
        $this->websiteCacheFactory = $websiteCacheFactory;
    }

    /**
     * does the whole processing
     */
    public function process()
    {
        if ($this->request->isCancelled() === true) {
            $this->response->send();
            return;
        }
        
        $processorResolverFactory = $this->websiteInitializer->getProcessorResolverFactory();
        $processorResolverFactory->init();
        $resolver  = $processorResolverFactory->getResolver();
        $processor = $resolver->resolve($this->request, $this->session, $this->response);
        $interceptorInitializer = $this->websiteInitializer->getInterceptorInitializer();
        $interceptorInitializer->setDescriptor($processor->getInterceptorDescriptor());
        $interceptorInitializer->init();
        foreach ($interceptorInitializer->getPreInterceptors() as $preInterceptor) {
            $preInterceptor->preProcess($this->request, $this->session, $this->response);
            if ($this->request->isCancelled() === true) {
                $this->response->send();
                return;
            }
        }
        
        $resolver->selectPage($processor);
        if ($processor->forceSSL() === true && $processor->isSSL() === false) {
            $this->response->addHeader('Location', 'https://' . $this->request->getURI());
            $this->request->cancel();
            $this->response->send();
            return;
        }
        
        if (null !== $this->websiteCacheFactory) {
            $processor = $this->websiteCacheFactory->configure($processor);
        }
        
        $processor->process();
        if ($this->request->isCancelled() === false) {
            foreach ($interceptorInitializer->getPostInterceptors() as $postInterceptor) {
                $postInterceptor->postProcess($this->request, $this->session, $this->response);
                if ($this->request->isCancelled() === true) {
                    break;
                }
            }
        }
        
        $this->response->send();
    }
}
?>