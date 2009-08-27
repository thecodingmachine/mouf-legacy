<?php
/**
 * JSON-RPC sub processor that handles post requests.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator',
                      'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractInvokingSubProcessor',
                      'net::stubbles::service::jsonrpc::stubJsonRpcWriter'
);
/**
 * JSON-RPC sub processor that handles post requests.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
class stubJsonRpcPostSubProcessor extends stubJsonRpcAbstractInvokingSubProcessor
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
        $requestJsonObj = $request->getValidatedRawData(new stubPassThruValidator());
        $phpJsonObj     = json_decode($requestJsonObj);
        if (is_object($phpJsonObj) === false) {
            $response->write(stubJsonRpcWriter::writeFault(null, 'Invalid request.'));
            return;
        }

        if (isset($phpJsonObj->id) === false) {
            $response->write(stubJsonRpcWriter::writeFault(null, 'Invalid request: No id given.'));
            return;
        }

        if (isset($phpJsonObj->method) === false) {
            $response->write(stubJsonRpcWriter::writeFault($phpJsonObj->id, 'Invalid request: No method given.'));
            return;
        }

        if (isset($phpJsonObj->params) === false) {
            $response->write(stubJsonRpcWriter::writeFault($phpJsonObj->id, 'Invalid request: No params given.'));
            return;
        }

        try {
            $className = null;
            if ($request->hasValue('__class') === true) {
                $className = $request->getValidatedValue(new stubPassThruValidator(), '__class');
            }
            
            $reflect = $this->getClassAndMethod($classMap, $phpJsonObj->method, $className);
            $result  = $this->invokeServiceMethod($reflect['class'], $reflect['method'], $phpJsonObj->params);
            $response->write(stubJsonRpcWriter::writeResponse($phpJsonObj->id, $result));
        } catch (Exception $e) {
            $response->write(stubJsonRpcWriter::writeFault($phpJsonObj->id, $e->getMessage()));
        }
    }
}
?>