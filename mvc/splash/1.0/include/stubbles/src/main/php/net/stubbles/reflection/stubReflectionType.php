<?php
/**
 * Basic interface for type references.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection
 */
/**
 * Basic interface for type references.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  reflection
 */
interface stubReflectionType
{
    /**
     * returns the name of the type
     *
     * @return  string
     */
    public function getName();

    /**
     * checks whether the type is an object
     *
     * @return  bool
     */
    public function isObject();

    /**
     * checks whether the type is a primitive
     *
     * @return  bool
     */
    public function isPrimitive();

    /**
     * checks whether a value is equal to the class
     *
     * @param   mixed  $compare
     * @return  bool
     */
    public function equals($compare);

    /**
     * returns a string representation of the class
     *
     * @return  string
     */
    public function __toString();
}
?>