<?php
/**
 * Interface for commands to be executed on the command line.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  console
 */
/**
 * Interface for commands to be executed on the command line.
 *
 * @package     stubbles
 * @subpackage  console
 */
interface stubConsoleCommand extends stubObject
{
    /**
     * runs the command
     */
    public function run();
}
?>