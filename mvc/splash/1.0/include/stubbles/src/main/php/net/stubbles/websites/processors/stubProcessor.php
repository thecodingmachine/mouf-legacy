<?php
/**
 * Interface for processors.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::websites::stubPageFactory'
);
/**
 * Interface for processors.
 * 
 * @package     stubbles
 * @subpackage  websites_processors
 */
interface stubProcessor extends stubObject
{
    /**
     * constructor
     *
     * @param  stubRequest   $request   the current request
     * @param  stubSession   $session   the current session
     * @param  stubResponse  $response  the current response
     */
    #public function __construct(stubRequest $request, stubSession $session, , stubResponse $response);

    /**
     * returns the request instance
     *
     * @return  stubRequest
     */
    public function getRequest();

    /**
     * returns the session instance
     *
     * @return  stubSession
     */
    public function getSession();

    /**
     * returns the response instance
     *
     * @return  stubResponse
     */
    public function getResponse();

    /**
     * sets the interceptor descriptor
     *
     * @param  string  $interceptorDescriptor
     */
    public function setInterceptorDescriptor($interceptorDescriptor);

    /**
     * returns the interceptor descriptor
     *
     * @return  string
     */
    public function getInterceptorDescriptor();

    /**
     * checks whether the current request forces ssl or not
     *
     * @return  bool
     */
    public function forceSSL();

    /**
     * checks whether the request is ssl or not
     *
     * @return  bool
     */
    public function isSSL();

    /**
     * processes the request
     */
    public function process();
}
?>