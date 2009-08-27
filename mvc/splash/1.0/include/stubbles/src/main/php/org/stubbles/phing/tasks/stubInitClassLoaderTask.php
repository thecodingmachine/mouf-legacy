<?php
/**
 * Task to load the stubbles classloader.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  phing_tasks
 * @version     $Id: stubInitClassLoaderTask.php 1877 2008-09-30 16:04:27Z mikey $
 */
/**
 * Uses the Phing Task
 */
require_once 'phing/Task.php';
/**
 * Task to load the stubbles classloader.
 *
 * @package     stubbles
 * @subpackage  phing_tasks
 */
class stubInitClassLoaderTask extends Task
{
    /**
     * path to source files
     *
     * @var  string
     */
    protected $sourcePath;
    /**
     * path to lib files
     *
     * @var  string
     */
    protected $libPath;

    /**
     * sets the path to source files
     *
     * @param  string  $sourcePath
     */
    public function setSourcePath($sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

    /**
     * sets the path to lib files
     *
     * @param  string  $libPath
     */
    public function setLibPath($libPath)
    {
        $this->libPath = $libPath;
    }

    /**
     * The init method: Do init steps.
     */
    public function init()
    {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main()
    {
        if (class_exists('stubConfig', false) === false) {
            $this->log('Error loading Stubbles classloader: configuration not available. Please make sure that the stubInitConfigTask was executed before.', Project::MSG_ERR);
            throw new BuildException('Error loading Stubbles classloader');
        }
        
        if (file_exists($this->libPath . '/stubbles.php') === true) {
            if ((include_once $this->libPath . '/stubbles.php') === false) {
                $this->log('Error loading Stubbles library from ' . $this->libPath . '/stubbles.php', Project::MSG_ERR);
                throw new BuildException('Error loading Stubbles library from ' . $this->libPath. '/stubbles.php');
            }
        } else {
            if (!@include_once $this->sourcePath . '/php/net/stubbles/stubClassLoader.php') {
                $this->log('Error loading stubClassLoader from ' . $this->sourcePath . '/php/net/stubbles/stubClassLoader.php', Project::MSG_ERR);
                throw new BuildException('Error loading stubClassLoader from ' . $this->sourcePath . '/php/net/stubbles/stubClassLoader.php');
            }
            
            if ((include_once $this->libPath . '/starWriter.php') === false) {
                $this->log('Error loading StarWriter from ' . $this->libPath . '/starWriter.php', Project::MSG_ERR);
                throw new BuildException('Error loading StarWriter from ' . $this->libPath . '/starWriter.php');
            }
        }

        stubClassLoader::load('net::stubbles::lang::stubFactory');
    }
}
?>