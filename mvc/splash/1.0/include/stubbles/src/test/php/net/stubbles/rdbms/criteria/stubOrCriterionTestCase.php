<?php
/**
 * Test for net::stubbles::rdbms::criteria::stubOrCriterion.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 */
stubClassLoader::load('net::stubbles::rdbms::criteria::stubOrCriterion');
/**
 * Test for net::stubbles::rdbms::criteria::stubOrCriterion.
 *
 * @package     stubbles
 * @subpackage  rdbms_criteria_test
 * @group       rdbms
 * @group       rdbms_criteria
 */
class stubOrCriterionTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubOrCriterion
     */
    protected $orCriterion;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->orCriterion = new stubOrCriterion();
    }

    /**
     * check that no criterion added triggers a stubIllegalStateException
     *
     * @test
     * @expectedException  stubIllegalStateException
     */
    public function zero()
    {
        $this->assertFalse($this->orCriterion->hasCriterion());
        $this->orCriterion->toSQL();
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
        $this->orCriterion->addCriterion($mockCriterionOne);
        $this->assertTrue($this->orCriterion->hasCriterion());
        $this->assertEquals('(foo)', $this->orCriterion->toSQL());
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
        $this->orCriterion->addCriterion($mockCriterionOne);
        $mockCriterionTwo = $this->getMock('stubCriterion');
        $mockCriterionTwo->expects($this->once())->method('toSQL')->will($this->returnValue('bar'));
        $this->orCriterion->addCriterion($mockCriterionTwo);
        $this->assertTrue($this->orCriterion->hasCriterion());
        $this->assertEquals('(foo OR bar)', $this->orCriterion->toSQL());
    }
}
?>