<?php
/**
 * JSON-RPC processor (generic proxy for web services).
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc
 */
stubClassLoader::load('net::stubbles::lang::stubRegistry',
                      'net::stubbles::websites::processors::stubAbstractProcessor'
);
/**
 * JSON-RPC processor (generic proxy for web services).
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc
 * @link        http://json-rpc.org/wiki/specification
 */
class stubJsonRpcProcessor extends stubAbstractProcessor
{
    /**
     * configuration file with list of client classes
     *
     * @var  string
     */
    protected $configFile;

    /**
     * optional template method to do some constructor work in derived classes
     */
    protected function doConstruct()
    {
        $this->configFile = stubConfig::getConfigPath() . DIRECTORY_SEPARATOR . 'json-rpc-service.ini';
    }

    /**
     * processes the request
     *
     * This method only dispatches the request to different subprocessors.
     */
    public function process()
    {
        $fqClassName = $this->getSubProcessorClassName();
        $nqClassName = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($nqClassName, false) === false) {
            stubClassLoader::load($fqClassName);
        }
        
        $subProcessor = new $nqClassName();
        $config       = $this->loadConfig();
        $subProcessor->process($this->request, $this->session, $this->response, $config['classmap'], $config['config']);
    }

    /**
     * loads the class map
     *
     * @return  array<string,string>
     * @throws  stubFileNotFoundException
     */
    protected function loadConfig()
    {
        if (file_exists($this->configFile) === false || is_readable($this->configFile) === false) {
            stubClassLoader::load('net::stubbles::lang::exceptions::stubFileNotFoundException');
            throw new stubFileNotFoundException($this->configFile);
        }
        
        return parse_ini_file($this->configFile, true);
    }

    /**
     * returns the subprocessor class to be used
     *
     * @return  string
     */
    protected function getSubProcessorClassName()
    {
        if ($this->request->getMethod() === 'post') {
            return 'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcPostSubProcessor';
        } elseif ($this->request->hasValue('__generateProxy') === true) {
            return 'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateProxiesSubProcessor';
        } elseif ($this->request->hasValue('__smd') === true) {
            return 'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGenerateSmdSubProcessor';
        }

        return 'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcGetSubProcessor';
    }
}
?>