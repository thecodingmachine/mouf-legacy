<?php
/**
 * Decodes input stream into internal charset.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  streams
 * @version     $Id: stubDecodingInputStream.php 1826 2008-09-15 20:38:26Z mikey $
 */
stubClassLoader::load('net::stubbles::streams::stubInputStream');
/**
 * Decodes input stream into internal charset.
 *
 * @package     stubbles
 * @subpackage  streams
 */
class stubDecodingInputStream extends stubBaseObject implements stubInputStream
{
    /**
     * input stream to encode into internal encoding
     *
     * @var  stubInputStream
     */
    protected $inputStream;
    /**
     * charset of input stream
     *
     * @var  string
     */
    protected $charset;

    /**
     * constructor
     *
     * @param  stubInputStream  $inputStream
     * @param  string           $charset      charset of input stream
     */
    public function __construct(stubInputStream $inputStream, $charset)
    {
        $this->inputStream = $inputStream;
        $this->charset     = $charset;
    }

    /**
     * returns enclosed input stream
     *
     * @return  stubInputStream
     */
    public function getEnclosedInputStream()
    {
        return $this->inputStream;
    }

    /**
     * returns charset of input stream
     *
     * @return  string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * reads given amount of bytes
     *
     * @param   int     $length  optional  max amount of bytes to read
     * @return  string
     */
    public function read($length = 8192)
    {
        return iconv($this->charset, 'UTF-8', $this->inputStream->read($length));
    }

    /**
     * reads given amount of bytes or until next line break
     *
     * @param   int     $length  optional  max amount of bytes to read
     * @return  string
     */
    public function readLine($length = 8192)
    {
        return iconv($this->charset, 'UTF-8', $this->inputStream->readLine($length));
    }

    /**
     * returns the amount of byted left to be read
     *
     * @return  int
     */
    public function bytesLeft()
    {
        return $this->inputStream->bytesLeft();
    }

    /**
     * returns true if the stream pointer is at EOF
     *
     * @return  bool
     */
    public function eof()
    {
        return $this->inputStream->eof();
    }

    /**
     * closes the stream
     */
    public function close()
    {
        $this->inputStream->close();
    }
}
?>