<?php
/**
 * Exception to be thrown in case a file could not be found.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIOException');
/**
 * Exception to be thrown in case a file could not be found.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  lang_exceptions
 */
class stubFileNotFoundException extends stubIOException
{
    /**
     * constructor
     *
     * @param  string  $fileName  name of file that was not found
     */
    public function __construct($fileName)
    {
        $this->message = "The file {$fileName} could not be found or is not readable.";
    }
}
?>