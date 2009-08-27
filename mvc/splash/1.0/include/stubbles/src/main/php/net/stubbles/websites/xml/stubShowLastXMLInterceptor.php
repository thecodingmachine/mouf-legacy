<?php
/**
 * Preinterceptor that is able to display the last created XML result document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubPreInterceptor');
/**
 * Preinterceptor that is able to display the last created XML result document.
 *
 * This interceptor cancels the request in case the request param
 * showLastRequestXML is set and the session is not new.
 *
 * @package     stubbles
 * @subpackage  websites_xml
 */
class stubShowLastXMLInterceptor extends stubBaseObject implements stubPreInterceptor
{
    /**
     * does the preprocessing stuff
     *
     * @param  stubRequest   $request   access to request data
     * @param  stubSession   $session   access to session data
     * @param  stubResponse  $response  access to response data
     */
    public function preProcess(stubRequest $request, stubSession $session, stubResponse $response)
    {
        if ($request->hasValue('showLastRequestXML') === true && $session->isNew() === false) {
            $response->addHeader('Content-type', 'text/xml');
            $response->write($session->getValue('net.stubbles.websites.lastRequestResponseData'));
            $request->cancel();
        }
    }
}
?>