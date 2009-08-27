<?php
/**
 * Interface for exception handlers.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler
 */
/**
 * Interface for exception handlers.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler
 * @see         http://php.net/set_exception_handler
 */
interface stubExceptionHandler extends stubObject
{
    /**
     * handles the exception
     *
     * @param  Exception  $exception  the uncatched exception
     */
    public function handleException(Exception $exception);
}