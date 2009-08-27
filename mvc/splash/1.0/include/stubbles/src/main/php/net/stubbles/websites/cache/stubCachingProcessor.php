<?php
/**
 * Processor that can be applied onto any processor implementing the stubCachableProcessor interface.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_cache
 */
stubClassLoader::load('net::stubbles::websites::cache::stubCachableProcessor',
                      'net::stubbles::websites::cache::stubWebsiteCache'
);
/**
 * Processor that can be applied onto any processor implementing the stubCachableProcessor interface.
 *
 * @package     stubbles
 * @subpackage  websites_cache
 */
class stubCachingProcessor extends stubBaseObject implements stubProcessor
{
    /**
     * decorated processor instance
     *
     * @var  stubCachableProcessor
     */
    protected $processor;
    /**
     * the request
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * current session
     *
     * @var  stubSession
     */
    protected $session;
    /**
     * the created response
     *
     * @var  stubResponse
     */
    protected $response;
    /**
     * website cache to be used
     *
     * @var  stubWebsiteCache
     */
    protected $cache;

    /**
     * constructor
     *
     * @param  stubCachableProcessor  $processor
     * @param  stubWebsiteCache       $websiteCache
     */
    public function __construct(stubCachableProcessor $processor, stubWebsiteCache $websiteCache)
    {
        $this->processor = $processor;
        $this->request   = $this->processor->getRequest();
        $this->session   = $this->processor->getSession();
        $this->response  = $this->processor->getResponse();
        $this->cache     = $websiteCache;
    }

    /**
     * returns the decorated processor
     *
     * @return  stubCachableProcessor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * returns the request instance
     *
     * @return  stubRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * returns the session instance
     *
     * @return  stubSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * returns the response instance
     *
     * @return  stubResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * sets the interceptor descriptor
     *
     * @param  string  $interceptorDescriptor
     */
    public function setInterceptorDescriptor($interceptorDescriptor)
    {
        $this->processor->setInterceptorDescriptor($interceptorDescriptor);
    }

    /**
     * returns the interceptor descriptor
     *
     * @return  string
     */
    public function getInterceptorDescriptor()
    {
        return $this->processor->getInterceptorDescriptor();
    }

    /**
     * checks whether the current request forces ssl or not
     *
     * @return  bool
     */
    public function forceSSL()
    {
        return $this->processor->forceSSL();
    }

    /**
     * checks whether the request is ssl or not
     *
     * @return  bool
     */
    public function isSSL()
    {
        return $this->processor->isSSL();
    }

    /**
     * processes the request
     */
    public function process()
    {
        $isCachable = $this->processor->addCacheVars($this->cache);
        if (true === $isCachable) {
            $this->cache->addCacheVar('ssl', $this->processor->isSSL());
            if ($this->cache->retrieve($this->request, $this->response, $this->processor->getPageName()) === true) {
                $this->setSessionData();
                return;
            }
        }
        
        $this->processor->process();
        if (true === $isCachable) {
            $this->cache->store($this->request, $this->response, $this->processor->getPageName());
        }
        
        $this->setSessionData();
    }

    /**
     * helper method to replace session place holders with correct session data
     */
    protected function setSessionData()
    {
        $contents = str_replace('$SID', $this->session->getName() . '=' . $this->session->getId(), $this->response->getData());
        $contents = str_replace('$SESSION_NAME', $this->session->getName(), $contents);
        $this->response->replaceData(str_replace('$SESSION_ID', $this->session->getId(), $contents));
    }
}
?>