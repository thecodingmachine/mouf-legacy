<?php
/**
 * Interface for output streams.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  streams
 */
/**
 * Interface for output streams.
 *
 * @package     stubbles
 * @subpackage  streams
 */
interface stubOutputStream extends stubObject
{
    /**
     * writes given bytes
     *
     * @param   string  $bytes
     * @return  int     amount of written bytes
     */
    public function write($bytes);

    /**
     * writes given bytes and appends a line break
     *
     * @param   string  $bytes
     * @return  int     amount of written bytes excluding line break
     */
    public function writeLine($bytes);

    /**
     * closes the stream
     */
    public function close();
}
?>