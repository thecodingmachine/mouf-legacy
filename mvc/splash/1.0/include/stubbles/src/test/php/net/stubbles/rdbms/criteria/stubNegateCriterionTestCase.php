<?php
/**
 * Test for net::stubbles::rdbms::criteria::stubNegateCriterion.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubNegateCriterion');
/**
 * Test for net::stubbles::rdbms::criteria::stubNegateCriterion.
 *
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 * @group       rdbms
 * @group       rdbms_criteria
 */
class stubNegateCriterionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * check that the given criterion is negated
     *
     * @test
     */
    public function negation()
    {
        $mockCriterion = $this->getMock('stubCriterion');
        $mockCriterion->expects($this->once())->method('toSQL')->will($this->returnValue('foo'));
        $negateCriterion = new stubNegateCriterion($mockCriterion);
        $this->assertEquals('NOT (foo)', $negateCriterion->toSQL());
    }
}
?>