<?php
/**
 * Class to generate JSON-RPC proxies
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
 * Class to generate JSON-RPC proxies
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_util
 */
class stubJsonRpcProxyGenerator extends stubBaseObject
{
    /**
     * generate JS proxy for a specified class
     *
     * @param   string  $className    name of the class to generate the proxy from
     * @param   string  $jsClass      optional  name of the generated javascript proxy
     * @param   string  $jsNamespace  optional  custom javascript namespace
     * @return  string
     * @throws  stubClassNotFoundException
     */
    public function generateJavascriptProxy($className, $jsClass = null, $jsNamespace = 'stubbles.json.proxy')
    {
        $clazz = new stubReflectionClass($className);
        if (null === $jsClass) {
            $jsClass = $clazz->getName();
        }

        $jsCode  = "{$jsNamespace}.{$jsClass} = function(clientObj) {\n";
        $jsCode .= "    this.dispatcher = new stubbles.json.rpc.Client(clientObj);\n";
        $jsCode .= "};\n";

        foreach ($clazz->getMethods() as $method) {
            if ($method->hasAnnotation('WebMethod') === true) {
                $methodName = $method->getName();
                $jsCode    .= "{$jsNamespace}.{$jsClass}.prototype.{$methodName} = function() {\n";
                $jsCode    .= "    return this.dispatcher.doCall('{$jsClass}.{$methodName}', arguments);\n";
                $jsCode    .= "};\n";
            }
        }
        
        return $jsCode;
    }
}
?>