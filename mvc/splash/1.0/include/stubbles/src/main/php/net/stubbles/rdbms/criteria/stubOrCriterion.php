<?php
/**
 * Composition of several criteria connected by OR.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubAbstractCompositeCriterion');
/**
 * Composition of several criteria connected by OR.
 * 
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
class stubOrCriterion extends stubAbstractCompositeCriterion
{
    /**
     * returns the the operator to connect the criteria
     *
     * @return  string
     */
    protected function getOperator()
    {
        return 'OR';
    }
}
?>