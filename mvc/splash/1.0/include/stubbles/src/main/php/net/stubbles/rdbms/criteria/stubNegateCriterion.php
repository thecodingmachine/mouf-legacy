<?php
/**
 * Criterion to negate another criterion.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubCriterion');
/**
 * Criterion to negate another criterion.
 * 
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
class stubNegateCriterion extends stubBaseObject implements stubCriterion
{
    /**
     * the criterion that should be negated
     *
     * @var  stubCriterion
     */
    protected $criterion;
    
    /**
     * constructor
     *
     * @param  stubCriterion  $criterion  criterion that should be negated
     */
    public function __construct(stubCriterion $criterion)
    {
        $this->criterion = $criterion;
    }
    
    /**
     * returns the criterion as sql
     *
     * @return  string
     */
    public function toSQL()
    {
        return 'NOT (' . $this->criterion->toSQL() . ')';
    }
}
?>