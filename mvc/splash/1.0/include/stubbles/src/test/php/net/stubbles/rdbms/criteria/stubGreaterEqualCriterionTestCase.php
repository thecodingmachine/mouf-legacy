<?php
/**
 * Test for net::stubbles::rdbms::criteria::stubGreaterEqualCriterion.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubGreaterEqualCriterion');
/**
 * Test for net::stubbles::rdbms::criteria::stubGreaterEqualCriterion.
 *
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 * @group       rdbms
 * @group       rdbms_criteria
 */
class stubGreaterEqualCriterionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * check that a null value throws a stubIllegalArgumentException
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function equalsNull()
    {
        $greaterEqualCriterion = new stubGreaterEqualCriterion('foo', null);
    }

    /**
     * check that any other value gets correct sql result
     *
     * @test
     */
    public function value()
    {
        $greaterEqualCriterion = new stubGreaterEqualCriterion('foo', 5);
        $this->assertEquals("`foo` >= '5'", $greaterEqualCriterion->toSQL());
        $greaterEqualCriterion = new stubGreaterEqualCriterion('foo', 6, 'baz');
        $this->assertEquals("`baz`.`foo` >= '6'", $greaterEqualCriterion->toSQL());
    }
}
?>