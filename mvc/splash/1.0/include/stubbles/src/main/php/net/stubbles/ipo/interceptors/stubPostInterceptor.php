<?php
/**
 * interface for postinterceptors
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession'
);
/**
 * interface for postinterceptors
 * 
 * Postinterceptors are called after all data processing is done. They can change
 * the response or add additional data to the response.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
interface stubPostInterceptor extends stubObject
{
    /**
     * does the postprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function postProcess(stubRequest $request, stubSession $session, stubResponse $response);
}
?>