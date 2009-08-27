<?php
/**
 * Test for net::stubbles::xml::serializer::stubXMLSerializer.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_test
 */
stubClassLoader::load('net::stubbles::xml::stubDomXMLStreamWriter',
                      'net::stubbles::xml::serializer::stubXMLSerializer');

/**
 * Simple Test class to test the XMLSerializer
 */
class testXMLSerializerStrategyDefault {
    private $ignore = 'ignore';
    public $foo = 'foo';

    public function getBar() {
        return 'bar';
    }

    public function setBar($bar) {
        throw new Exception('setBar has been called');
    }
}

/**
 * Class that uses the ALL strategy
 *
 * @XMLStrategy(stubXMLSerializer::STRATEGY_ALL)
 * @XMLTag(tagName='obj')
 */
class testXMLSerializerStrategyAll {

    public $prop = 'propValue';

    public function method() {
        return 'return';
    }
}

/**
 * Class that uses the NONE strategy
 *
 * @XMLStrategy(stubXMLSerializer::STRATEGY_NONE)
 * @XMLTag(tagName='obj')
 */
class testXMLSerializerStrategyNone {

    public $prop = 'propValue';

    public function method() {
        return 'return';
    }
}

/**
 * Class that uses the PROPS strategy
 *
 * @XMLStrategy(stubXMLSerializer::STRATEGY_PROPS)
 * @XMLTag(tagName='obj')
 */
class testXMLSerializerStrategyProps {

    public $prop = 'propValue';

    public function method() {
        return 'return';
    }
}

/**
 * Class that uses the METHODS strategy
 *
 * @XMLStrategy(stubXMLSerializer::STRATEGY_METHODS)
 * @XMLTag(tagName='obj')
 */
class testXMLSerializerStrategyMethods {

    public $prop = 'propValue';

    public function method() {
        return 'return';
    }
}
/**
 * Test for net::stubbles::xml::serializer::stubXMLSerializer.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_test
 * @group       xml
 * @group       xml_serializer
 */
class stubXMLSerializerStrategyTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * The XMLSerializer to use
     *
     * @var stubXMLSerializer
     */
    protected $serializer;

    /**
     * Setup method
     *
     * Creates the serializer instance
     */
    public function setUp()
    {
        $this->serializer = new stubXMLSerializer();
    }

    /**
     * Test serializing an object without annotations and different stragies
     *
     * @todo Implement tests for mixing annotations with strategies
     *
     * @test
     */
    public function defaultObjectStrategies()
    {
        $obj = new testXMLSerializerStrategyDefault();

        // No strategy
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testXMLSerializerStrategyDefault><foo>foo</foo><getBar>bar</getBar></testXMLSerializerStrategyDefault>', $writer->asXML());

        // STRATEGY_ALL
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer, array(stubXMLSerializer::OPT_STRATEGY => stubXMLSerializer::STRATEGY_ALL));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testXMLSerializerStrategyDefault><foo>foo</foo><getBar>bar</getBar></testXMLSerializerStrategyDefault>', $writer->asXML());

        // STRATEGY_NONE
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer, array(stubXMLSerializer::OPT_STRATEGY => stubXMLSerializer::STRATEGY_NONE));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testXMLSerializerStrategyDefault/>', $writer->asXML());

        // STRATEGY_PROPS
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer, array(stubXMLSerializer::OPT_STRATEGY => stubXMLSerializer::STRATEGY_PROPS));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testXMLSerializerStrategyDefault><foo>foo</foo></testXMLSerializerStrategyDefault>', $writer->asXML());

        // STRATEGY_METHODS
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer, array(stubXMLSerializer::OPT_STRATEGY => stubXMLSerializer::STRATEGY_METHODS));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testXMLSerializerStrategyDefault><getBar>bar</getBar></testXMLSerializerStrategyDefault>', $writer->asXML());
    }

    /**
     * Test serializing an object with the XMLStrategy=ALL annotation
     *
     * @test
     */
    public function strategyAll()
    {
        $obj = new testXMLSerializerStrategyAll();

        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<obj><prop>propValue</prop><method>return</method></obj>', $writer->asXML());
    }

    /**
     * Test serializing an object with the XMLStrategy=PROPS annotation
     *
     * @test
     */
    public function strategyProps()
    {
        $obj = new testXMLSerializerStrategyProps();

        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<obj><prop>propValue</prop></obj>', $writer->asXML());
    }

    /**
     * Test serializing an object with the XMLStrategy=METHODS annotation
     *
     * @test
     */
    public function strategyMethods()
    {
        $obj = new testXMLSerializerStrategyMethods();

        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<obj><method>return</method></obj>', $writer->asXML());
    }

    /**
     * Test serializing an object with the XMLStrategy=NONE annotation
     *
     * @test
     */
    public function strategyNone()
    {
        $obj = new testXMLSerializerStrategyNone();

        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<obj/>', $writer->asXML());
    }
}
?>