<?php
/**
 * Interface for input streams.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  streams
 */
/**
 * Interface for input streams.
 *
 * @package     stubbles
 * @subpackage  streams
 */
interface stubInputStream extends stubObject
{
    /**
     * reads given amount of bytes
     *
     * @param   int     $length  optional  max amount of bytes to read
     * @return  string
     */
    public function read($length = 8192);

    /**
     * reads given amount of bytes or until next line break
     *
     * @param   int     $length  optional  max amount of bytes to read
     * @return  string
     */
    public function readLine($length = 8192);

    /**
     * returns the amount of byted left to be read
     *
     * @return  int
     */
    public function bytesLeft();

    /**
     * returns true if the stream pointer is at EOF
     *
     * @return  bool
     */
    public function eof();

    /**
     * closes the stream
     */
    public function close();
}
?>