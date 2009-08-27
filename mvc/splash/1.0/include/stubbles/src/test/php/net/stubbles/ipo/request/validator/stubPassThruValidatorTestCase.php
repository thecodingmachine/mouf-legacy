<?php
/**
 * Tests for net::stubbles::ipo::request::validator::stubPassThruValidator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator_test
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator');
/**
 * Tests for net::stubbles::ipo::request::validator::stubPassThruValidator.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_validator
 */
class stubPassThruValidatorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubPassThruValidator
     */
    protected $passThruValidator;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->passThruValidator = new stubPassThruValidator();
    }

    /**
     * assure that validation works correct
     *
     * @test
     */
    public function validation()
    {
        $this->assertTrue($this->passThruValidator->validate(123));
        $this->assertTrue($this->passThruValidator->validate('1234'));
        $this->assertTrue($this->passThruValidator->validate(true));
        $this->assertTrue($this->passThruValidator->validate(null));
        $this->assertTrue($this->passThruValidator->validate(new stdClass()));
    }

    /**
     * assure that returning the criterias works correct
     *
     * @test
     */
    public function getCriteria()
    {
        $this->assertEquals(array(), $this->passThruValidator->getCriteria());
    }
}
?>