<?php
/**
 * Base class for a composition of several criteria.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
stubClassLoader::load('net::stubbles::lang::exceptions::stubIllegalStateException',
                      'net::stubbles::rdbms::criteria::stubCompositeCriterion'
);
/**
 * Base class for a composition of several criteria.
 * 
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
abstract class stubAbstractCompositeCriterion extends stubBaseObject implements stubCompositeCriterion
{
    /**
     * the list of criteria
     *
     * @var  array<stubCriterion>
     */
    protected $criteria = array();

    /**
     * add a criterion to the composition
     *
     * @param  stubCriterion  $criterion
     */
    public function addCriterion(stubCriterion $criterion)
    {
        $this->criteria[] = $criterion;
    }

    /**
     * checks whether there is any criterion
     *
     * @return  bool
     */
    public function hasCriterion()
    {
        return (count($this->criteria) > 0);
    }

    /**
     * returns the criterion as sql
     * 
     * @return  string
     * @throws  stubIllegalStateException
     */
    public function toSQL()
    {
        if (count($this->criteria) == 0) {
            throw new stubIllegalStateException('Can not translate to sql: criterion does not have any criteria to connect.');
        }
        
        $sql = '(';
        foreach ($this->criteria as $key => $criterion) {
            if (0 < $key) {
                $sql .= ' ' . $this->getOperator() . ' ';
            }
            
            $sql .= $criterion->toSQL();
        }
        
        return $sql . ')';
    }

    /**
     * returns the the operator to connect the criteria
     *
     * @return  string
     */
    protected abstract function getOperator();
}
?>