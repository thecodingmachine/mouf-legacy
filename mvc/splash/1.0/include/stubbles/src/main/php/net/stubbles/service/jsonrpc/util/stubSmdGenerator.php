<?php
/**
 * Class to generate service method descriptions for JSON-RPC proxies.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_util
 */
stubClassLoader::load('net::stubbles::service::annotations::stubWebMethodAnnotation',
                      'net::stubbles::reflection::reflection'
);
/**
 * Class to generate service method descriptions for JSON-RPC proxies.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_util
 */
class stubSmdGenerator extends stubBaseObject
{
    /**
     * URL of the service
     *
     * @var  string
     */
    protected $serviceURL;

    /**
     * create a new generator
     *
     * @param  string  $serviceURL
     */
    public function __construct($serviceURL)
    {
        $this->serviceURL = $serviceURL;
    }

    /**
     * generate JS proxy for a specified class
     *
     * @param   string  $className  name of the class to generate the proxy from
     * @param   string  $jsClass    optional  name of the generated javascript proxy
     * @return  string
     */
    public function generateSmd($className, $jsClass = null)
    {
        $smdData = new stdClass();
        $smdData->SMDVersion  = 1;
        $smdData->serviceType = 'JSON-RPC';
        $smdData->serviceURL  = $this->serviceURL;
        $smdData->methods     = array();
        if (null !== $jsClass) {
            $smdData->objectName = $jsClass;
        }

        $clazz = new stubReflectionClass($className);
        foreach ($clazz->getMethods() as $method) {
            if ($method->hasAnnotation('WebMethod') === true) {
                $methodDef             = new stdClass();
                $methodDef->name       = $method->getName();
                $methodDef->parameters = array();
                $smdData->methods[]    = $methodDef;
                foreach ($method->getParameters() as $parameter) {
                    $paramDef = new stdClass();
                    $paramDef->name = $parameter->getName();
                    $methodDef->parameters[] = $paramDef;
                }
            }
        }
        
        return json_encode($smdData);
    }
}
?>