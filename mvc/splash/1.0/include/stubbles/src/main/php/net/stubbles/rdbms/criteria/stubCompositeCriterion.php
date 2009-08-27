<?php
/**
 * Interface for a composition of several criteria.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubCriterion');
/**
 * Interface for a composition of several criteria.
 * 
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
interface stubCompositeCriterion extends stubCriterion
{
    /**
     * add a criterion to the composition
     *
     * @param  stubCriterion  $criterion
     */
    public function addCriterion(stubCriterion $criterion);
}
?>