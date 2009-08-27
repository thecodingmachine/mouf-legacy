<?php
/**
 * Service class to be used in tests.
 *
 * @author      Richard Sternagel
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
/**
 * Service class to be used in tests.
 *
 * @package     stubbles
 * @subpackage  service_jsonrpc_subprocessors_test
 */
class TestService extends stubBaseObject
{
    /**
     * test method for web service
     *
     * @param   int  $a
     * @param   int  $b
     * @return  int
     * @WebMethod
     */
    public function add($a, $b)
    {
        return ($a + $b);
    }

    /**
     * another method that is not marked as WebMethod
     *
     * @param   int  $a
     * @param   int  $b
     * @return  int
     */
    public function mod($a, $b)
    {
        return ($a % $b);
    }
}
?>