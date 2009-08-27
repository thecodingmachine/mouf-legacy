<?php
/**
 * Interface for command executors.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  console
 */
stubClassLoader::load('net::stubbles::streams::stubOutputStream');
/**
 * Interface for command executors.
 *
 * @package     stubbles
 * @subpackage  console
 */
interface stubExecutor extends stubObject
{
    /**
     * sets the output stream to write data outputted by executed command to
     *
     * @param   stubOutputStream  $out
     * @return  stubExecutor
     */
    public function streamOutputTo(stubOutputStream $out);

    /**
     * returns the output stream to write data outputted by executed command to
     *
     * @return  stubOutputStream
     */
    public function getOutputStream();

    /**
     * executes given command
     *
     * @param   string        $command
     * @return  stubExecutor
     */
    public function execute($command);
}
?>