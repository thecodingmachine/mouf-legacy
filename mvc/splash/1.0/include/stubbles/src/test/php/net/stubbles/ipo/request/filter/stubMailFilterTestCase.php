<?php
/**
 * Tests for net::stubbles::ipo::request::filter::stubMailFilter.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubMailFilter');
/**
 * Tests for net::stubbles::ipo::request::filter::stubMailFilter.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubMailFilterTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubMailFilter
     */
    protected $mailFilter;
    /**
     * a mock to be used for the rveFactory
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockRequestValueErrorFactory;
    /**
     * a mock to be used for the mail validator
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockMailValidator;

    /**
     * create test environment
     *
     */
    public function setUp()
    {
        $this->mockRequestValueErrorFactory = $this->getMock('stubRequestValueErrorFactory');
        $this->mockMailValidator            = $this->getMock('stubValidator');
        $this->mailFilter = new stubMailFilter($this->mockRequestValueErrorFactory, $this->mockMailValidator);
    }

    /**
     * assure that empty values are returned as null
     *
     * @test
     */
    public function emptyValues()
    {
        $this->mockMailValidator->expects($this->never())->method('validate');
        $this->assertNull($this->mailFilter->execute(''));
        $this->assertNull($this->mailFilter->execute(null));
    }

    /**
     * assure that values are returned the expected way
     *
     * @test
     */
    public function value()
    {
        $this->assertSame($this->mockMailValidator, $this->mailFilter->getMailValidator());
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(true));
        $this->assertEquals('example@example.org', $this->mailFilter->execute('example@example.org'));
    }

    /**
     * assure that an exceptiom is thrown when a wrong value is passed
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function spacesTriggerException()
    {
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(false));
        $this->mockRequestValueErrorFactory->expects($this->once())
                                           ->method('create')
                                           ->with($this->equalTo('MAILADDRESS_CANNOT_CONTAIN_SPACES'))
                                           ->will($this->returnValue(new stubRequestValueError('foo', array('en_EN' => 'Something wrent wrong.'))));
        $this->mailFilter->execute('space in@mailadre.ss');
    }

    /**
     * assure that an exceptiom is thrown when a wrong value is passed
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function germanUmlautTriggerException()
    {
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(false));
        $this->mockRequestValueErrorFactory->expects($this->once())
                                           ->method('create')
                                           ->with($this->equalTo('MAILADDRESS_CANNOT_CONTAIN_UMLAUTS'))
                                           ->will($this->returnValue(new stubRequestValueError('foo', array('en_EN' => 'Something wrent wrong.'))));
        $this->mailFilter->execute('föö@mailadre.ss');
    }

    /**
     * assure that an exceptiom is thrown when a wrong value is passed
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function moreThanOneAtTriggerException()
    {
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(false));
        $this->mockRequestValueErrorFactory->expects($this->once())
                                           ->method('create')
                                           ->with($this->equalTo('MAILADDRESS_MUST_CONTAIN_ONE_AT'))
                                           ->will($this->returnValue(new stubRequestValueError('foo', array('en_EN' => 'Something wrent wrong.'))));
        $this->mailFilter->execute('foo@bar@mailadre.ss');
    }

    /**
     * assure that an exceptiom is thrown when a wrong value is passed
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function illegalCharsTriggerException()
    {
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(false));
        $this->mockRequestValueErrorFactory->expects($this->once())
                                           ->method('create')
                                           ->with($this->equalTo('MAILADDRESS_CONTAINS_ILLEGAL_CHARS'))
                                           ->will($this->returnValue(new stubRequestValueError('foo', array('en_EN' => 'Something wrent wrong.'))));
        $this->mailFilter->execute('foo&/4@mailadre.ss');
    }

    /**
     * assure that an exceptiom is thrown when a wrong value is passed
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function twoFollowingDotsTriggerException()
    {
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(false));
        $this->mockRequestValueErrorFactory->expects($this->once())
                                           ->method('create')
                                           ->with($this->equalTo('MAILADDRESS_CONTAINS_TWO_FOLLOWING_DOTS'))
                                           ->will($this->returnValue(new stubRequestValueError('foo', array('en_EN' => 'Something wrent wrong.'))));
        $this->mailFilter->execute('foo..bar@mailadre.ss');
    }

    /**
     * assure that an exceptiom is thrown when a wrong value is passed
     *
     * @test
     * @expectedException  stubFilterException
     */
    public function otherErrorsTriggerException()
    {
        $this->mockMailValidator->expects($this->once())->method('validate')->will($this->returnValue(false));
        $this->mockRequestValueErrorFactory->expects($this->once())
                                           ->method('create')
                                           ->with($this->equalTo('MAILADDRESS_INCORRECT'))
                                           ->will($this->returnValue(new stubRequestValueError('foo', array('en_EN' => 'Something wrent wrong.'))));
        $this->mailFilter->execute('foobar@mailadre.ss');
    }
}
?>