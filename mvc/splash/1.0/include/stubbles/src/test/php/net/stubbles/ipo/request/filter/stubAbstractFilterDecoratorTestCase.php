<?php
/**
 * Tests for net::stubbles::ipo::request::filter::stubAbstractFilterDecorator.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubAbstractFilterDecorator');
/**
 * Tests for net::stubbles::ipo::request::filter::stubStrategyFilterDecorator.
 *
 * @package     stubbles
 * @subpackage  ipo_request_filter_test
 * @group       ipo
 * @group       ipo_request
 * @group       ipo_request_filter
 */
class stubAbstractFilterDecoratorTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * a mock to be used for the minimum validator
     *
     * @var  stubAbstractFilterDecorator
     */
    protected $abstractFilterDecorator;

    /**
     * create test environment
     */
    public function setUp()
    {
        $this->abstractFilterDecorator = $this->getMock('stubAbstractFilterDecorator',
                                                        array('execute')
                                         );
    }

    /**
     * assert that decorated filter is same as in constructor
     *
     * @test
     */
    public function decoratedFilter()
    {
        $mockFilter = $this->getMock('stubFilter');
        $this->abstractFilterDecorator->setDecoratedFilter($mockFilter);
        $this->assertSame($mockFilter, $this->abstractFilterDecorator->getDecoratedFilter());
    }
}
?>