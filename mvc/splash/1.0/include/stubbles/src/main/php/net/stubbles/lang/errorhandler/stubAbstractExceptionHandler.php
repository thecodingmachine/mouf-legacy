<?php
/**
 * Abstract base implementation for exception handlers, containing logging of exceptions.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_errorhandler
 */
stubClassLoader::load('net::stubbles::ipo::response::stubBaseResponse',
                      'net::stubbles::lang::errorhandler::stubExceptionHandler',
                      'net::stubbles::util::log::log'
);
/**
 * Abstract base implementation for exception handlers, containing logging of exceptions.
 *
 * @package     stubbles
 * @subpackage  lang_errorhandler
 * @see         http://php.net/set_exception_handler
 */
abstract class stubAbstractExceptionHandler extends stubBaseObject implements stubExceptionHandler
{
    /**
     * switch whether logging is enabled or not
     *
     * @var  bool
     */
    protected $loggingEnabled = true;
    /**
     * target of the log data
     *
     * @var  string
     */
    protected $logTarget      = 'exceptions';
    /**
     * the error level to use for the log data
     *
     * @var  int
     */
    protected $logLevel       = stubLogger::LEVEL_ERROR;

    /**
     * set whether logging is enabled or not
     *
     * @param  bool  $loggingEnabled
     */
    public function setLogging($loggingEnabled)
    {
        $this->loggingEnabled = $loggingEnabled;
    }

    /**
     * sets the target of the log data
     *
     * @param  string  $logTarget
     */
    public function setLogTarget($logTarget)
    {
        $this->logTarget = $logTarget;
    }

    /**
     * sets the level of the log data
     *
     * @param  int  $logLevel
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * handles the exception
     *
     * @param  Exception  $exception  the uncatched exception
     */
    public function handleException(Exception $exception)
    {
        $response = new stubBaseResponse();
        $this->fillResponse($response, $exception);
        if (true === $this->loggingEnabled) {
            $this->log($exception);
        }
        
        // send the response because the request will end right after this
        // method has been finished
        $response->send();
    }

    /**
     * fills response with useful data for display
     *
     * @param  stubResponse  $response   response to be send
     * @param  Exception     $exception  the uncatched exception
     */
    protected abstract function fillResponse(stubResponse $response, Exception $exception);

    /**
     * logs the exception into a logfile
     *
     * @param  Exception  $exception  the uncatched exception
     */
    protected function log(Exception $exception)
    {
        stubClassLoader::load('net::stubbles::util::log::stubExceptionLog');
        $exceptionLog = new stubExceptionLog();
        $exceptionLog->setLogLevel($this->logLevel);
        $exceptionLog->setLogTarget($this->logTarget);
        $exceptionLog->log($exception);
    }
}