<?php
/**
 * Holds a reference to another exception.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  php_serializer
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubChainedException');
/**
 * Holds a reference to another exception.
 *
 * @package     stubbles
 * @subpackage  php_serializer
 */
class stubExceptionReference extends stubChainedException
{
    /**
     * name of referenced exception
     *
     * @var  string
     */
    protected $exceptionName;
    /**
     * the stack trace of the referenced exception
     *
     * @var  array<string,scalar>
     */
    protected $stackTrace = array();

    /**
     * sets the name of the referenced exception
     *
     * @param  string  $exceptionName
     */
    public function setReferencedExceptionName($exceptionName)
    {
        $this->exceptionName = $exceptionName;
    }

    /**
     * returns the name of the referenced exception
     *
     * @return  string
     */
    public function getReferencedExceptionName()
    {
        return $this->exceptionName;
    }

    /**
     * sets the stack trace of the referenced exception
     *
     * @param  array<string,scalar>  $stackTrace
     */
    public function setReferencedStackTrace(array $stackTrace)
    {
        $this->stackTrace = $stackTrace;
    }

    /**
     * returns the referenced stack trace
     *
     * @return  array<string,scalar>
     */
    public function getReferencedStackTrace()
    {
        return $this->stackTrace;
    }
}
?>