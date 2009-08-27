<?php
/**
 * Default collection of PHP error handlers.
 * 
 * @author      Frank Kleine  <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler
 */
stubClassLoader::load('net::stubbles::lang::errorhandler::stubCompositeErrorHandler',
                      'net::stubbles::lang::errorhandler::stubIllegalArgumentErrorHandler',
                      'net::stubbles::lang::errorhandler::stubLogErrorHandler'
);
/**
 * Default collection of PHP error handlers.
 *
 * The collection consists of:
 *  - stubIllegalArgumentErrorHandler
 *      throws a stubIllegalArgumentException in case of an E_RECOVERABLE saying
 *      that a type hint was violated
 *  - stubLogErrorHandler
 *      logs all remaining errors into the logfile php-errors with log level error
 * 
 * @package     stubbles
 * @subpackage  lang_errorhandler
 * @see         http://php.net/set_error_handler
 */
class stubDefaultErrorHandler extends stubCompositeErrorHandler
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->addErrorHandler(new stubIllegalArgumentErrorHandler());
        $this->addErrorHandler(new stubLogErrorHandler());
    }
}
?>