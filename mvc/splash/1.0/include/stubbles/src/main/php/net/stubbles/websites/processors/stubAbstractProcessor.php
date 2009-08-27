<?php
/**
 * Base processor implementation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubEqualValidator',
                      'net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::websites::processors::stubProcessor'
);
/**
 * Base processor implementation.
 *
 * @package     stubbles
 * @subpackage  websites_processors
 */
abstract class stubAbstractProcessor extends stubBaseObject implements stubProcessor
{
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
     * the interceptor descriptor
     *
     * @var  string
     */
    protected $interceptorDescriptor = 'interceptors';
    /**
     * switch whether we are running in ssl mode or not
     *
     * @var  bool
     */
    private $ssl                    = null;

    /**
     * constructor
     *
     * @param  stubRequest   $request   the current request
     * @param  stubSession   $session   the current session
     * @param  stubResponse  $response  the current response
     */
    public function __construct(stubRequest $request, stubSession $session, stubResponse $response)
    {
        $this->request  = $request;
        $this->session  = $session;
        $this->response = $response;
        $this->doConstruct();
    }

    /**
     * optional template method to do some constructor work in derived classes
     */
    protected function doConstruct()
    {
        // intentionally empty
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
        $this->interceptorDescriptor = $interceptorDescriptor;
    }

    /**
     * returns the interceptor descriptor
     *
     * @return  string
     */
    public function getInterceptorDescriptor()
    {
        return $this->interceptorDescriptor;
    }

    /**
     * checks whether the current request forces ssl or not
     *
     * @return  bool
     */
    public function forceSSL()
    {
        return false;
    }

    /**
     * checks whether the request is ssl or not
     *
     * @return  bool
     */
    public function isSSL()
    {
        if (null === $this->ssl) {
            $this->ssl = false;
            if ($this->request->validateValue(new stubEqualValidator(443), 'SERVER_PORT', stubRequest::SOURCE_HEADER) === true) {
                $this->ssl = true;
            }
        }
        
        return $this->ssl;
    }
}
?>