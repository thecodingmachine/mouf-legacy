<?php
/**
 * Tests for net::stubbles::ipo::request::validator::stubIpValidator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator_test
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubIpValidator');
/**
 * Tests for net::stubbles::ipo::request::validator::stubIpValidator.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_validator
 */
class stubIpValidatorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubIpValidator
     */
    protected $ipValidator;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->ipValidator = new stubIpValidator();
    }
    
    /**
     * assure that validation works correct
     *
     * @test
     */
    public function noIps()
    {
        $this->assertFalse($this->ipValidator->validate('foo'));
        $this->assertFalse($this->ipValidator->validate(null));
        $this->assertFalse($this->ipValidator->validate(true));
        $this->assertFalse($this->ipValidator->validate(false));
        $this->assertFalse($this->ipValidator->validate(4));
        $this->assertFalse($this->ipValidator->validate(6));
    }
    
    /**
     * assure that validation works correct
     *
     * @test
     */
    public function wrongIps()
    {
        $this->assertFalse($this->ipValidator->validate('255.55.55'));
        $this->assertFalse($this->ipValidator->validate('111.222.333.444.555'));
        $this->assertFalse($this->ipValidator->validate('1..3.4'));
    }
    
    /**
     * assure that validation works correct
     *
     * @test
     */
    public function correctIps()
    {
        $this->assertTrue($this->ipValidator->validate('255.255.255.255'));
        $this->assertTrue($this->ipValidator->validate('0.0.0.0'));
        $this->assertTrue($this->ipValidator->validate('1.2.3.4'));
    }

    /**
     * ip validator has no specific criteria
     *
     * @test
     */
    public function criteria()
    {
        $this->assertEquals(array(), $this->ipValidator->getCriteria());
    }
}
?>