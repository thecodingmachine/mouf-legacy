<?php
/**
 * Basic JSON-RPC sub processor with helper methods for dynamic proxy generation.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator',
                      'net::stubbles::lang::stubMode',
                      'net::stubbles::service::jsonrpc::subprocessors::stubJsonRpcSubProcessor'
);
/**
 * Basic JSON-RPC sub processor with helper methods for dynamic proxy generation.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors
 */
abstract class stubJsonRpcAbstractGenerateSubProcessor extends stubBaseObject implements stubJsonRpcSubProcessor
{
    /**
     * default javascript namespace
     *
     * @var  string
     */
    protected $jsNamespace = 'stubbles.json.proxy';

    /**
     * helper method to detect the service url
     *
     * @param   stubRequest  $request
     * @return  string
     */
    protected function getServiceURL(stubRequest $request)
    {
        $tmp        = parse_url($request->getURI());
        $serviceUrl = '//' . $tmp['path'];
        if ($request->hasValue('processor') === true) {
            $serviceUrl .= '?processor=' . $request->getValidatedValue(new stubPassThruValidator(), 'processor');
        }
        
        return $serviceUrl;
    }

    /**
     * helper method to handle an exception
     *
     * @param  Exception     $exception
     * @param  stubResponse  $response
     * @param  string        $introduction
     */
    protected function handleException(Exception $exception, stubResponse $response, $introduction)
    {
        if (stubMode::$CURRENT->name() === 'PROD') {
            return;
        }
        
        stubClassLoader::load('net::stubbles::service::jsonrpc::util::stubFirebugEncoder');
        $firebugEncoder = new stubFirebugEncoder();
        $response->write($firebugEncoder->encode($introduction));
        $response->write($firebugEncoder->encode($exception->__toString()));
    }
}
?>