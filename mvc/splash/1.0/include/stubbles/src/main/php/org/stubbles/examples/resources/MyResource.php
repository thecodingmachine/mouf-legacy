<?php
/**
 * Example resource interface.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles_examples
 * @subpackage  resources
 */
/**
 * Example resource interface.
 *
 * @package     stubbles_examples
 * @subpackage  resources
 */
interface MyResource extends stubObject
{
    /**
     * returns the current count value
     *
     * @return  int
     */
    public function getCount();

    /**
     * increments the counter
     */
    public function incrementCount();
}
?>