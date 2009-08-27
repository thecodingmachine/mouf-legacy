<?php
/**
 * Tests for net::stubbles::ipo::request::validator::stubMailValidator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_validator_test
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubMailValidator');
/**
 * Tests for net::stubbles::ipo::request::validator::stubMailValidator.
 *
 * @package     stubbles
 * @subpackage  ipo_request_validator_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_validator
 */
class stubMailValidatorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubMailValidator
     */
    protected $mailValidator;
    
    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->mailValidator = new stubMailValidator();
    }
    
    /**
     * assure that validation works correct
     *
     * @test
     */
    public function validation()
    {
        $this->assertFalse($this->mailValidator->validate(null));
        $this->assertFalse($this->mailValidator->validate(''));
        $this->assertFalse($this->mailValidator->validate('xcdsfad'));
        $this->assertFalse($this->mailValidator->validate('foobar@thishost.willnever.exist'));
        $this->assertTrue($this->mailValidator->validate('example@example.org'));
        $this->assertTrue($this->mailValidator->validate('example.foo.bar@example.org'));
        $this->assertFalse($this->mailValidator->validate('.foo.bar@example.org'));
        $this->assertFalse($this->mailValidator->validate('example@example.org\n'));
        $this->assertFalse($this->mailValidator->validate('example@exa"mple.org'));
        $this->assertFalse($this->mailValidator->validate('example@example.org\nBcc: example@example.com'));
    }

    /**
     * test other failing addresses
     *
     * @test
     */
    public function failingAddresses()
    {
        $this->assertFalse($this->mailValidator->validate('space in@mailadre.ss'));
        $this->assertFalse($this->mailValidator->validate('föö@mailadre.ss'));
        $this->assertFalse($this->mailValidator->validate('foo@bar@mailadre.ss'));
        $this->assertFalse($this->mailValidator->validate('foo&/4@mailadre.ss'));
        $this->assertFalse($this->mailValidator->validate('foo..bar@mailadre.ss'));
    }

    /**
     * assure that returning the criterias works correct
     *
     * @test
     */
    public function getCriteria()
    {
        $this->assertEquals(array(), $this->mailValidator->getCriteria());
    }
}
?>