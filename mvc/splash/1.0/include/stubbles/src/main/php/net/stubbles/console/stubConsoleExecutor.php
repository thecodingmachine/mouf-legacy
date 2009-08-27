<?php
/**
 * Class to execute commands on the command line.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  console
 */
stubClassLoader::load('net::stubbles::console::stubExecutor',
                      'net::stubbles::lang::exceptions::stubRuntimeException'
);
/**
 * Class to execute commands on the command line.
 *
 * @package     stubbles
 * @subpackage  console
 */
class stubConsoleExecutor extends stubBaseObject implements stubExecutor
{
    /**
     * output stream to write data outputted by executed command to
     *
     * @var  stubOutputStream
     */
    protected $out;
    /**
     * redirect direction
     *
     * @var  string
     */
    protected $redirect = '2>&1';

    /**
     * sets the output stream to write data outputted by executed command to
     *
     * @param   stubOutputStream  $out
     * @return  stubExecutor
     */
    public function streamOutputTo(stubOutputStream $out)
    {
        $this->out = $out;
        return $this;
    }

    /**
     * returns the output stream to write data outputted by executed command to
     *
     * @return  stubOutputStream
     */
    public function getOutputStream()
    {
        return $this->out;
    }

    /**
     * sets the redirect
     *
     * @param   string        $redirect
     * @return  stubExecutor
     */
    public function redirectTo($redirect)
    {
        $this->redirect = $redirect;
        return $this;
    }

    /**
     * executes given command
     *
     * @param   string        $command
     * @return  stubExecutor
     * @throws  stubRuntimeException
     */
    public function execute($command)
    {
        $pd = popen(escapeshellcmd($command) . ' ' . $this->redirect, 'r');
        if (false === $pd) {
            throw new stubRuntimeException('Can not execute ' . $command);
        }
        
        while (feof($pd) === false && false !== ($line = fgets($pd, 4096))) {
            $line = chop($line);
            if (null !== $this->out) {
                $this->out->writeLine($line);
            }
        }
        
        $returnCode = pclose($pd);
        if (0 != $returnCode) {
            throw new stubRuntimeException('Executing command ' . $command . ' failed: #' . $returnCode);
        }
        
        return $this;
    }
}
?>