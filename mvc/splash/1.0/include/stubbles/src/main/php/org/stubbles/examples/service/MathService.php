<?php
/**
 * Simple Math Service used for examples.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  service
 */
/**
 * Simple Math Service used for examples.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  service
 */
class MathService
{
    /**
     * Add two numbers
     *
     * @WebMethod
     * @param   int     $arrKey
     * @return  string
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

    /**
     * Method to throw an exception
     *
     * @WebMethod
     */
    public function throwException()
    {
        throw new stubException("This exception is intended.");
    }
}
?>