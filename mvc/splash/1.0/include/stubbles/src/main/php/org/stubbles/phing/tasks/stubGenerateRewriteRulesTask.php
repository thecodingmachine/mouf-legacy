<?php
/**
 * Task to generate .htaccess file with selected features.  
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @package     stubbles
 * @subpackage  phing_tasks
 */
require_once 'phing/Task.php';
/**
 * Task to generate .htaccess file with selected features.  
 *
 * @package     stubbles
 * @subpackage  phing_tasks
 */
class stubGenerateRewriteRulesTask extends Task
{
    /**
     * source file
     *
     * @var  string
     */
    protected $htaccessSource;
    /**
     * destination directory
     *
     * @var  string
     */
    protected $destinationDir;
    /**
     * gets htaccess lines appendend 
     *
     * @var  string
     */
    protected $htaccess;
    /**
     * commaseperated list of processors
     *
     * @var  string
     */
    protected $selectedProcessors;

    /**
     * constructor
     */
    public function init()
    {
        // intentionally empty
    }

    /**
     * setter for build process
     *
     * @param  string  $procs
     */
    public function setSelectedProcessors($procs)
    {
        $this->selectedProcessors = $procs;
    }

    /**
     * sets source file for .htaccess
     *
     * @param  string  $htaccessSource
     */
    public function setHtaccessSource($htaccessSource)
    {
        $this->htaccessSource = $htaccessSource;
    }

    /**
     * sets destination directory
     *
     * @param  string  $destinationDir
     */
    public function setDestinationDir($destinationDir)
    {
        $this->destinationDir = $destinationDir;
    }

    /**
     * construct .htaccess and write content
     *
     * @param  array<string>  $selectedProcs
     */
    public function writeHtacess($selectedProcs)
    {
        $lines = file($this->htaccessSource);
        foreach ($lines as $line) {
            if (preg_match('/^RewriteRule.*/', $line)) {
                foreach ($selectedProcs as $selProc) {
                    $this->htaccess .= (preg_match('/^RewriteRule ' . $selProc . '.*/', $line) ? ($line) : (''));
                }
            } else {                
                $this->htaccess .= $line;
            }
        }
        
        file_put_contents($this->destinationDir . '/.htaccess', $this->htaccess);
    }

    /**
     * implement main method of phing Task
     */
    public function main()
    {
        // fault-tolerant user input processing 
        // supports e.g. 'xml, page' (instead of only 'xml,page')
        $selectedProcs = explode(',', str_replace(' ', '', $this->selectedProcessors));
        $this->writeHtacess($selectedProcs);
    }
}
?>