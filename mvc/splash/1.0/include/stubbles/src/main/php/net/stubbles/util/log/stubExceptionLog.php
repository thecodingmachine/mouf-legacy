<?php
/**
 * Log exceptions.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_log
 * @version     $Id: stubExceptionLog.php 1753 2008-07-30 15:56:54Z mikey $
 */
stubClassLoader::load('net::stubbles::util::log::log');
/**
 * Log exceptions.
 *
 * @package     stubbles
 * @subpackage  util_log
 */
class stubExceptionLog extends stubBaseObject
{
    /**
     * target of the log data
     *
     * @var  string
     */
    protected $logTarget = 'exceptions';
    /**
     * the error level to use for the log data
     *
     * @var  int
     */
    protected $logLevel  = stubLogger::LEVEL_ERROR;

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
     * logs an exception
     *
     * @param  Exception  $exception
     */
    public function log(Exception $exception)
    {
        $logData = stubLogDataFactory::create($this->logTarget, $this->logLevel);
        $logData->addData(($exception instanceof stubThrowable) ? ($exception->getClassName()) : (get_class($exception)));
        $logData->addData($exception->getMessage());
        $logData->addData($exception->getFile());
        $logData->addData($exception->getLine());
        if ($exception instanceof stubChainedException && null !== $exception->getCause()) {
            $cause = $exception->getCause();
            $logData->addData(($cause instanceof stubThrowable) ? ($cause->getClassName()) : (get_class($cause)));
            $logData->addData($cause->getMessage());
            $logData->addData($cause->getFile());
            $logData->addData($cause->getLine());
        } else {
            $logData->addData('');
            $logData->addData('');
            $logData->addData('');
            $logData->addData('');
        }
        
        stubLogger::logToAll($logData);
    }
}
?>