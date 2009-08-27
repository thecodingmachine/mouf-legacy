<?php
/**
 * Exception handler for production mode: triggers a 500 Internal Server Error response.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler
 */
stubClassLoader::load('net::stubbles::lang::errorhandler::stubAbstractExceptionHandler');
/**
 * Exception handler for production mode: triggers a 500 Internal Server Error response.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler
 */
class stubProdModeExceptionHandler extends stubAbstractExceptionHandler
{
    /**
     * fills response with useful data for display
     *
     * @param  stubResponse  $response   response to be send
     * @param  Exception     $exception  the uncatched exception
     */
    protected function fillResponse(stubResponse $response, Exception $exception)
    {
        $response->setStatusCode(500, 'Internal Server Error');
        $response->write(file_get_contents(stubConfig::getConfigPath() . '/errors/500.html'));
    }
}