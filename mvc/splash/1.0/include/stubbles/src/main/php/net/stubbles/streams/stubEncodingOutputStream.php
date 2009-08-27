<?php
/**
 * Encodes internal encoding into output charset.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  streams
 * @version     $Id: stubEncodingOutputStream.php 1826 2008-09-15 20:38:26Z mikey $
 */
stubClassLoader::load('net::stubbles::streams::stubOutputStream');
/**
 * Encodes internal encoding into output charset.
 *
 * @package     stubbles
 * @subpackage  streams
 */
class stubEncodingOutputStream extends stubBaseObject implements stubOutputStream
{
    /**
     * input stream to encode into internal encoding
     *
     * @var  stubOutputStream
     */
    protected $outputStream;
    /**
     * charset of output stream
     *
     * @var  string
     */
    protected $charset;

    /**
     * constructor
     *
     * @param  stubOutputStream  $outputStream
     * @param  string            $charset       charset of output stream
     */
    public function __construct(stubOutputStream $outputStream, $charset)
    {
        $this->outputStream = $outputStream;
        $this->charset      = $charset;
    }

    /**
     * returns enclosed output stream
     *
     * @return  stubOutputStream
     */
    public function getEnclosedOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * returns charset of output stream
     *
     * @return  string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * writes given bytes
     *
     * @param   string  $bytes
     * @return  int     amount of written bytes
     */
    public function write($bytes)
    {
        return $this->outputStream->write(iconv('UTF-8', $this->charset, $bytes));
    }

    /**
     * writes given bytes and appends a line break
     *
     * @param   string  $bytes
     * @return  int     amount of written bytes excluding line break
     */
    public function writeLine($bytes)
    {
        return $this->outputStream->writeLine(iconv('UTF-8', $this->charset, $bytes));
    }

    /**
     * closes the stream
     */
    public function close()
    {
        $this->outputStream->close();
    }
}
?>