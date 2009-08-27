<?php
/**
 * Task to check that initializers do not throw any exception.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  phing_tasks
 * @version     $Id: stubCheckInitializersTask.php 1876 2008-09-30 15:52:07Z mikey $
 */
/**
 * Uses the Phing Task
 */
require_once 'phing/Task.php';
/**
 * Task to check that initializers do not throw any exception.
 *
 * @package     stubbles
 * @subpackage  phing_tasks
 */
class stubCheckInitializersTask extends Task
{
    /**
     * file containing a list of classes to check
     *
     * @var string
     */
    protected $classFile;
    /**
     * path to pat templates
     *
     * @var  string
     */
    protected $templatesPath;

    /**
     * set the file containing a list of classes to check
     *
     * @param  string  $classFile
     */
    public function setClassFile($classFile)
    {
        $this->classFile = $classFile;
    }

    /**
     * sets the path to pat templates
     *
     * @param  string  $templatesPath
     */
    public function setTemplatesPath($templatesPath)
    {
        $this->templatesPath = $templatesPath;
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
        stubClassLoader::load('net::stubbles::reflection::stubReflectionClass');
        stubClassLoader::load('net::stubbles::lang::stubRegistry');
        stubClassLoader::load('net::stubbles::websites::memphis::stubMemphisTemplate');
        stubRegistry::setConfig(stubMemphisTemplate::REGISTRY_KEY_DIR, $this->templatesPath);
        $classes = parse_ini_file($this->classFile);
        foreach ($classes as $fqClassName) {
            $this->log('Testing ' . $fqClassName);
            try {
                $class = new stubReflectionClass($fqClassName);
                $class->getMethod('init')->invoke($class->newInstance());
                // once again with caching
                $class->getMethod('init')->invoke($class->newInstance());
            } catch (Exception $e) {
                throw new BuildException(get_class($e) . ': ' . $e->getMessage());
            }
        }
        
        $this->log('Executed all initializers successfully.');
    }
}
?>