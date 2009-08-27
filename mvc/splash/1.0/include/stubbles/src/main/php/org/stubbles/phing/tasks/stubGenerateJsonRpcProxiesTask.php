<?php
/**
 * Task to generate JSON-RPC proxies.
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
 * Task to generate JSON-RPC proxies.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  phing_tasks
 */
class stubGenerateJsonRpcProxiesTask extends Task
{
    /**
     * Target folder of the generated proxy files
     *
     * @var  string
     */
    protected  $docroot;
    /**
     * File containing the service definitions
     *
     * @var  string
     */
    protected $serviceFile;
    /**
     * Namespace for the generated javascript code
     *
     * @var  string
     */
    protected $javaScriptNamespace = 'stubbles.json.proxy';
    /**
     * default target dir within docroot
     *
     * @var  string
     */
    protected $genJsDir            = 'javascript/genjs';

    /**
     * Set the target folder for the generated proxies
     *
     * @param  string  $docroot
     */
    public function setDocroot($docroot)
    {
        $this->docroot = $docroot;
    }

    /**
     * Set the service file, that contains the web service definitions
     *
     * @param string $serviceFile
     */
    public function setServiceFile($serviceFile)
    {
        $this->serviceFile = $serviceFile;
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
        stubClassLoader::load('net::stubbles::service::jsonrpc::util::stubJsonRpcProxyGenerator');
        $services   = parse_ini_file($this->serviceFile, true);
        $generator  = new stubJsonRpcProxyGenerator();
        if (isset($services['config']['namespace']) === false) {
            $services['config']['namespace'] = $this->javaScriptNamespace;
        }
        
        if (isset($services['config']['genjsdir']) === false) {
            $services['config']['genjsdir'] = $this->genJsDir;
        }
        
        if (file_exists($this->docroot . '/' . $services['config']['genjsdir']) === false) {
            mkdir($this->docroot . '/' . $services['config']['genjsdir'], null, true);
        }
        
        $fullJsCode = $services['config']['namespace'] . " = {};\n\n";
        foreach ($services['classmap'] as $class => $fqClassName) {
            try {
                $jsCode = $generator->generateJavascriptProxy($fqClassName, $class, $services['config']['namespace']);
            } catch (stubClassNotFoundException $e) {
                $this->log("Cannot generate proxy for {$fqClassName}, class does not exist.", Project::MSG_ERR);
                throw new BuildException("Cannot generate proxy for {$fqClassName}, class does not exist.");
            }
            
            $targetFile = $this->docroot . '/' . $services['config']['genjsdir'] . '/' . $class . '.js';
            if (@file_put_contents($targetFile, $jsCode)) {;
                $this->log("Wrote proxy for {$fqClassName} to {$targetFile}.");
            } else {
                $this->log("Cannot write proxy for {$fqClassName} to {$targetFile}.", Project::MSG_ERR);
                throw new BuildException("Cannot write proxy classes to {$targetFile}.");
            }
            
            $fullJsCode .= $jsCode;
        }
        
        if (empty($jsCode)) {
            return;
        }
        
        $targetFile = $this->docroot . '/' . $services['config']['genjsdir'] . '/allClients.js';
        if (@file_put_contents($targetFile, $fullJsCode)) {;
            $this->log("Wrote proxy for all classes to {$targetFile}.");
        } else {
            $this->log("Cannot write proxy classes to {$targetFile}.", Project::MSG_ERR);
            throw new BuildException("Cannot write proxy classes to {$targetFile}.");
        }
    }
}
?>