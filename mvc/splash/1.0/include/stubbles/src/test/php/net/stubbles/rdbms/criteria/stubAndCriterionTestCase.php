<?php
/**
 * Test for net::stubbles::rdbms::criteria::stubAndCriterion.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubAndCriterion');
/**
 * Test for net::stubbles::rdbms::criteria::stubAndCriterion.
 *
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 * @group       rdbms
 * @group       rdbms_criteria
 */
class stubAndCriterionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubAndCriterion
     */
    protected $andCriterion;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->andCriterion = new stubAndCriterion();
    }

    /**
     * check that no criterion added triggers a stubIllegalStateException
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function zero()
    {
        $this->assertFalse($this->andCriterion->hasCriterion());
        $this->andCriterion->toSQL();
    }

    /**
     * check that one criterion is handled correct
     *
     * @test
     */
    public function one()
    {
        $mockCriterionOne = $this->getMock('stubCriterion');
        $mockCriterionOne->expects($this->once())->method('toSQL')->will($this->returnValue('foo'));
        $this->andCriterion->addCriterion($mockCriterionOne);
        $this->assertTrue($this->andCriterion->hasCriterion());
        $this->assertEquals('(foo)', $this->andCriterion->toSQL());
    }

    /**
     * check that two criteria are handled correct
     *
     * @test
     */
    public function two()
    {
        $mockCriterionOne = $this->getMock('stubCriterion');
        $mockCriterionOne->expects($this->once())->method('toSQL')->will($this->returnValue('foo'));
        $this->andCriterion->addCriterion($mockCriterionOne);
        $mockCriterionTwo = $this->getMock('stubCriterion');
        $mockCriterionTwo->expects($this->once())->method('toSQL')->will($this->returnValue('bar'));
        $this->andCriterion->addCriterion($mockCriterionTwo);
        $this->assertTrue($this->andCriterion->hasCriterion());
        $this->assertEquals('(foo AND bar)', $this->andCriterion->toSQL());
    }
}
?>