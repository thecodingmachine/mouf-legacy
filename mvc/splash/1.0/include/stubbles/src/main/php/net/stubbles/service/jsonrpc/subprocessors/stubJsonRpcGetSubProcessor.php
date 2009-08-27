<?php
/**
 * JSON-RPC sub processor that handles get requests.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator',
                      'net::stubbles::ipo::request::validator::stubRegexValidator',
                      'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcAbstractInvokingSubProcessor',
                      'net::stubbles::service::jsonrpc::stubJsonRpcWriter'
);
/**
 * JSON-RPC sub processor that handles get requests.
 *
 * This is mainly used for debugging purposes.
 *
 * http://localhost/stubbles/docroot/json.php?
 * <paramName>=2
 * [&<paramName>=3]*
 * &method=<classname>.<methodname>
 * &id=186252
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
class stubJsonRpcGetSubProcessor extends stubJsonRpcAbstractInvokingSubProcessor
{
    /**
     * Regexp to validate param param
     */
    const PARAM_PATTERN            = '/^[a-zA-Z0-9_]+$/';
    /**
     * Regexp to validate id param
     */
    const ID_PATTERN               = '/^\d{6,7}$/';

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
        $requestId = $request->getValidatedValue(new stubRegexValidator(self::ID_PATTERN), 'id');
        if (null === $requestId) {
            $response->write(stubJsonRpcWriter::writeFault($requestId, 'Invalid request: No id given.'));
            return;
        }
        
        $method = $request->getValidatedValue(new stubPassThruValidator(), 'method');
        if (null === $method) {
            $response->write(stubJsonRpcWriter::writeFault($requestId, 'Invalid request: No method given.'));
            return;
        }
        
        try {
            $reflect   = $this->getClassAndMethod($classMap, $method);
            $params    = $this->retrieveGETParams($request, $reflect['method']);
            $result    = $this->invokeServiceMethod($reflect['class'], $reflect['method'], $params);
            $response->write(stubJsonRpcWriter::writeResponse($requestId, $result));
        } catch (Exception $e) {
            $response->write(stubJsonRpcWriter::writeFault($requestId, $e->getMessage()));
        }
    }

    /**
     * Get the parameters from the GET request
     *
     * @param   stubRequest           $request
     * @param   stubReflectionMethod  $method
     * @return  array
     * @throws  stubException
     */
    protected function retrieveGETParams(stubRequest $request, stubReflectionMethod $method)
    {
        $paramPattern = new stubRegexValidator(self::PARAM_PATTERN);
        $paramValues  = array();
        foreach ($method->getParameters() as $param) {
            $paramName  = $param->getName();
            $paramValue = $request->getValidatedValue($paramPattern, $paramName);
            if (null === $paramValue) {
                throw new stubException('Param '. $paramName . ' is missing.');
            }

            array_push($paramValues, $paramValue);
        }

        return $paramValues;
    }
}
?>