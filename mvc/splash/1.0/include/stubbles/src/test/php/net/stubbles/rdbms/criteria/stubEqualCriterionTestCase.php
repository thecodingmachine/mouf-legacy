<?php
/**
 * Test for net::stubbles::rdbms::criteria::stubEqualCriterion.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubEqualCriterion');
/**
 * Test for net::stubbles::rdbms::criteria::stubEqualCriterion.
 *
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 * @group       rdbms
 * @group       rdbms_criteria
 */
class stubEqualCriterionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * check that a null value gets correct operator
     *
     * @test
     */
    public function equalsNull()
    {
        $equalCriterion = new stubEqualCriterion('foo', null);
        $this->assertEquals('`foo` IS NULL',$equalCriterion->toSQL());
        $equalCriterion = new stubEqualCriterion('foo', null, 'bar');
        $this->assertEquals('`bar`.`foo` IS NULL', $equalCriterion->toSQL());
    }

    /**
     * check that any other value gets correct operator
     *
     * @test
     */
    public function value()
    {
        $equalCriterion = new stubEqualCriterion('foo', 'bar');
        $this->assertEquals("`foo` = 'bar'", $equalCriterion->toSQL());
        $equalCriterion = new stubEqualCriterion('foo', 'bar', 'baz');
        $this->assertEquals("`baz`.`foo` = 'bar'", $equalCriterion->toSQL());
    }
}
?>