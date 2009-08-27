<?php
/**
 * JSON-RPC sub processor that handles dynamic smd generation.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractGenerateSubProcessor',
                      'net::stubbles::service::jsonrpc::util::stubSmdGenerator'
);
/**
 * JSON-RPC sub processor that handles dynamic smd generation.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
class stubJsonRpcGenerateSmdSubProcessor extends stubJsonRpcAbstractGenerateSubProcessor
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
        
        $class     = $request->getValidatedValue(new stubRegexValidator('/^[A-Za-z0-9_\.]+$/'), '__smd');
        $generator = $this->getSmdGenerator($this->getServiceURL($request) . '&__class=' . $class);
        // get rid of namespace for class matching
        $class     = preg_replace('/' . preg_quote($config['namespace']) . '\./', '', $class);
        try {
            $response->write($generator->generateSmd($classMap[$class], $class));
        } catch (Exception $e) {
            $this->handleException($e, $response, 'Generation of SMD for ' . $classMap[$class] . ' failed.');
        }
    }

    /**
     * creates the smd generator
     *
     * @param   string            $serviceUrl
     * @return  stubSmdGenerator
     */
    // @codeCoverageIgnoreStart
    protected function getSmdGenerator($serviceUrl)
    {
        return new stubSmdGenerator($serviceUrl);
    }
    // @codeCoverageIgnoreEnd
}
?>