<?php
/**
 * Interface for JSON-RPC sub processors.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::response::stubResponse',
                      'net::stubbles::ipo::session::stubSession'
);
/**
 * Interface for JSON-RPC sub processors.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
interface stubJsonRpcSubProcessor extends stubObject
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
    public function process(stubRequest $request, stubSession $session, stubResponse $response, array $classMap, array $config);
}
?>