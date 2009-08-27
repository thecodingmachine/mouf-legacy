<?php
/**
 * JSON-RPC sub processor that handles dynamic proxy generation.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractGenerateSubProcessor',
                      'net::stubbles::service::jsonrpc::util::stubJsonRpcProxyGenerator'
);
/**
 * JSON-RPC sub processor that handles dynamic proxy generation.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
class stubJsonRpcGenerateProxiesSubProcessor extends stubJsonRpcAbstractGenerateSubProcessor
{
    /**
     * does the processing of the subtask
     *
     * @param  stubRequest                         $request   the current request
     * @param  stubSession                         $session   the current session
     * @param  stubResponse                        $response  the current response
     * @param  array<string,array<string,string>>  $classMap  list of available webservice classes
     * @param  array<string,string>                $config    json-rpc config
     */
    public function process(stubRequest $request, stubSession $session, stubResponse $response, array $classMap, array $config)
    {
        if (isset($config['namespace']) === false) {
            $config['namespace'] = $this->jsNamespace;
        }
        
        $classes = $request->getValidatedValue(new stubRegexValidator('/^[A-Za-z,0-9_\.]+$/'), '__generateProxy');
        if ('__all' !== $classes) {
            $classes = explode(',', $classes);
        }
            
        $response->write($config['namespace'] . " = {};\n\n");
        $generator = $this->getProxyGenerator();
        foreach ($classMap as $jsClass => $fqClassName) {
            if (is_array($classes) === false || in_array($jsClass, $classes) === true) {
                try {
                    $response->write($generator->generateJavascriptProxy($fqClassName, $jsClass, $config['namespace']));
                } catch (Exception $e) {
                    $this->handleException($e, $response, 'Generation of proxy for ' . $fqClassName . ' failed.');
                }
            }
        }
    }

    /**
     * helper method to create the proxy generator
     *
     * @return  stubJsonRpcProxyGenerator
     */
    // @codeCoverageIgnoreStart
    protected function getProxyGenerator()
    {
        return new stubJsonRpcProxyGenerator();
    }
    // @codeCoverageIgnoreEnd
}
?>