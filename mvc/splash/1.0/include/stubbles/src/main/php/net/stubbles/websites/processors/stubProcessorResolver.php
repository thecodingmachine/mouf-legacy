<?php
/**
 * Interface for processor resolvers.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_processors
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession',
                      'net::stubbles::websites::processors::stubProcessor'
);
/**
 * Interface for processor resolvers.
 * 
 * @package     stubbles
 * @subpackage  websites_processors
 */
interface stubProcessorResolver extends stubSerializable
{
    /**
     * resolves the request and creates the appropriate processor
     *
     * @param   stubRequest    $request   the current request
     * @param   stubSession    $session   the current session
     * @param   stubResponse   $response  the current response
     * @return  stubProcessor
     */
    public function resolve(stubRequest $request, stubSession $session, stubResponse $response);

    /**
     * method to handle page based processors
     *
     * @param   stubProcessor  $processor
     * @throws  stubConfigurationException
     * @throws  stubRuntimeException
     */
    public function selectPage(stubProcessor $processor);
}
?>