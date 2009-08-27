<?php
/**
 * Task to load stubbles configuration options.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  phing_tasks
 */
/**
 * Uses the Phing Task
 */
require_once 'phing/Task.php';
/**
 * Task to load stubbles configuration options.
 *
 * @package     stubbles
 * @subpackage  phing_tasks
 */
class stubInitConfigTask extends Task
{
    /**
     * path where config file can be found
     *
     * @var  string
     */
    protected $configPath;
    /**
     * config file to use
     *
     * @var  string
     */
    protected $configFile = 'php/config.php';

    /**
     * sets the config path
     *
     * @param  string  $configPath
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = realpath($configPath);
    }

    /**
     * sets the config file
     *
     * @param  string  $configFile
     */
    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;
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
        @include_once $this->configPath . DIRECTORY_SEPARATOR . $this->configFile;
        if (!class_exists('stubConfig', false)) {
            $this->log('Error loading Stubbles configuration from ' . $this->configPath . DIRECTORY_SEPARATOR . $this->configFile, Project::MSG_ERR);
            throw new BuildException('Error loading Stubbles configuration from ' . $this->configPath . DIRECTORY_SEPARATOR . $this->configFile);
        }
    }
}
?>