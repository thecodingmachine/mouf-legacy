<?php
/**
 * interface for criteria
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
/**
 * interface for criteria
 * 
 * @package     stubbles
 * @subpackage  rdbms_criteria
 */
interface stubCriterion extends stubObject
{
    /**
     * returns the criterion as sql
     * 
     * @return  string
     */
    public function toSQL();
}
?>