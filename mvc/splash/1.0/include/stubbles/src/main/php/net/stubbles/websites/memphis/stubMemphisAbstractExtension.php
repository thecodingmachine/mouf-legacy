<?php
/**
 * Page element for including a template file as content.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_memphis
 */
stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisExtension');
/**
 * Page element for including a template file as content.
 *
 * @package     stubbles
 * @subpackage  websites_memphis
 */
abstract class stubMemphisAbstractExtension extends stubBaseObject implements stubMemphisExtension
{
    /**
     * access to request
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * access to session
     *
     * @var  stubSession
     */
    protected $session;
    /**
     * access to response
     *
     * @var  stubResponse
     */
    protected $response;
    /**
     * access to context
     *
     * @var  array<string,mixed>
     */
    protected $context;

    /**
     * constructor
     *
     * @param  stubRequest   $request   the request data
     * @param  stubSession   $session   current session
     * @param  stubResponse  $response  contains response data
     * @Inject
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
     * sets the context
     *
     * @param  array  $context  additional context data
     * @Inject
     * @Named('context')
     */
    public function setContext(array $context)
    {
        $this->context = $context;
    }

    /**
     * checks whether extension is cachable or not
     *
     * @return  bool
     */
    public function isCachable()
    {
        return true;
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return array();
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return array();
    }
}
?>