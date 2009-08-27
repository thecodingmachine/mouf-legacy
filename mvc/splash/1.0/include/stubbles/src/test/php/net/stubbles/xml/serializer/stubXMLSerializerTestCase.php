<?php
/**
 * Test for net::stubbles::xml::serializer::stubXMLSerializer.
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_test
 * @todo        Split this into smaller test cases
 */
stubClassLoader::load('net::stubbles::xml::stubDomXMLStreamWriter',
                      'net::stubbles::xml::serializer::stubXMLSerializer');

/**
 * Simple Test class to test the XMLSerializer
 */
class XMLSerializerDefaultObj {
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
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='foo')
 */
class XMLSerializerFoo {

    /**
     * Scalar property
     *
     * @var int
     * @XMLTag(tagName='bar')
     */
    public $bar = 42;

    /**
     * Another scalar property
     *
     * @var string
     * @XMLAttribute(attributeName='bar')
     */
    public $scalar = "test";

    /**
     * Should not be exported to XML
     *
     * @var string
     * @XMLIgnore
     */
    public $ignoreMe = 'Ignore';
}

/**
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='container')
 */
class XMLSerializerList {

    /**
     * Array property
     *
     * @var int
     * @XMLTag(tagName='list', elementTagName='item')
     */
    public $bar = array('one', 'two', 'three');
}

/**
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='container')
 */
class XMLSerializerList2 {

    /**
     * Array property
     *
     * @var int
     * @XMLTag(tagName=false, elementTagName='item')
     */
    public $bar = array('one', 'two', 'three');
}

/**
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='class')
 */
class XMLSerializerMethods {

    public function __construct() {}
    public function __destruct() {}
    public function __get($prop) {}

    /**
     * Return a value
     *
     * @return string
     * @XMLAttribute(attributeName='method')
     */
    public function getValue() {
        return "returned";
    }
}

/**
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='testObject')
 * @XMLProperties[XMLMatcher](pattern='/^([a-zA-Z]{3})$/')
 */
class XMLSerializerPropertyMatcher {
    public $one   = 'one';
    public $two   = 'two';
    public $three = 'three';
}


/**
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='testObject')
 * @XMLMethods[XMLMatcher](pattern='/^get(F.+)/')
 */
class XMLSerializerMethodMatcher {
    public function getFoo() {
        return "foo";
    }
    public function getBar() {
        return "bar";
    }
}

/**
 * Simple Test class to test the XMLSerializer
 *
 * @XMLTag(tagName='test')
 */
class XMLSerializerFragmentTest {
    /**
     * Property containing XML
     *
     * @XMLFragment(tagName='xml');
     * @var string
     */
    public $xml = '<foo>bar</foo>';

    /**
     * Property containing XML
     *
     * @XMLFragment(tagName=false);
     * @var string
     */
    public $xml2 = '<foo>bar</foo>';
}

/**
 * Class to test empty attributes
 *
 * @XMLTag(tagName='test')
 */
class XMLSerializerAttributeTest {

    /**
     * Empty property
     *
     * @var mixed
     * @XMLAttribute(attributeName='emptyProp')
     */
    public $emptyProp;

    /**
     * Empty property
     *
     * @var mixed
     * @XMLAttribute(attributeName='emptyProp2', skipEmpty=false)
     */
    public $emptyProp2;

    /**
     * Empty return value
     *
     * @return mixed
     * @XMLAttribute(attributeName='emptyMethod')
     */
    public function getEmpty() {
        return null;
    }

    /**
     * Empty return value
     *
     * @return mixed
     * @XMLAttribute(attributeName='emptyMethod2', skipEmpty=false)
     */
    public function getEmpty2() {
        return null;
    }
}

/**
 * Class to test german umlaut properties
 *
 * @XMLTag(tagName='test')
 */
class XMLSerializerUmlautTest {

    /**
     * test property
     *
     * @var string
     * @XMLTag(tagName='foo')
     */
    public $foo = 'Hähnchen';

    /**
     * test property
     *
     * @var string
     * @XMLAttribute(attributeName='bar')
     */
    public $ba = 'Hähnchen';

}

/**
 * Simple Test class to test the XMLSerializer and static properties/methods
 */
class stubXMLSerializerTestCase_Static {
    public static $foo = 'foo';

    public static function getBar() {
        return 'bar';
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
class stubXMLSerializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * The XMLSerializer to use
     *
     * @var stubXMLSerializer
     */
    protected $serializer;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->serializer = new stubXMLSerializer();
    }

    /**
     * Test serializing a null value
     *
     * @test
     */
    public function null()
    {
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize(null, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root><null/></root>', $writer->asXML());
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize(null, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<null><null/></null>', $writer->asXML());
    }

    /**
     * Test serializing a string
     *
     * @test
     */
    public function string()
    {
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize('This is a string.', $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root>This is a string.</root>', $writer->asXML());
    }

    /**
     * Test serializing an integer
     *
     * @test
     */
    public function integer()
    {
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize(45, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root>45</root>', $writer->asXML());
    }

    /**
     * Test serializing a float
     *
     * @test
     */
    public function float()
    {
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize(2.352, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root>2.352</root>', $writer->asXML());
    }

    /**
     * Test serializing a boolean
     *
     * @test
     */
    public function boolean()
    {
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize(true, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root>true</root>', $writer->asXML());
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize(true, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<boolean>true</boolean>', $writer->asXML());
    }

    /**
     * Test serializing an assoc array
     *
     * @test
     */
    public function assocArray()
    {
        $array  = array('one' => 'two',
                        'three' => 'four'
                  );
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($array, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root><one>two</one><three>four</three></root>', $writer->asXML());
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($array, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<array><one>two</one><three>four</three></array>', $writer->asXML());
    }

    /**
     * Test serializing an indexed array
     *
     * @test
     */
    public function indexedArray()
    {
        $array  = array('one', 'two', 'three');
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($array, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root><string>one</string><string>two</string><string>three</string></root>', $writer->asXML());
        $writer = new stubDomXMLStreamWriter();
        $this->serializer->serialize($array, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<array><string>one</string><string>two</string><string>three</string></array>', $writer->asXML());
    }

    /**
     * Test serializing an nested assoc array
     *
     * @test
     */
    public function nestedAssocArray()
    {
        $writer = new stubDomXMLStreamWriter();
        $array  = array('one' => 'two',
                        'three' => array('four' => 'five')
                  );
        $this->serializer->serialize($array, $writer, array(stubXMLSerializer::OPT_ROOT_TAG => 'root'));
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<root><one>two</one><three><four>five</four></three></root>', $writer->asXML());
    }

    /**
     * Test serializing an object
     *
     * @test
     */
    public function object()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerFoo();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<foo bar="test"><bar>42</bar></foo>', $writer->asXML());
    }

    /**
     * Test serializing a nested object
     *
     * @test
     */
    public function nestedObject()
    {
        $writer   = new stubDomXMLStreamWriter();
        $obj      = new XMLSerializerFoo();
        $obj->bar = new XMLSerializerFoo();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<foo bar="test"><bar bar="test"><bar>42</bar></bar></foo>', $writer->asXML());
    }

    /**
     * Test serializing an object with an array property
     *
     * @test
     */
    public function arrayProperty()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj = new XMLSerializerList();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<container><list><item>one</item><item>two</item><item>three</item></list></container>', $writer->asXML());

        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerList2();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<container><item>one</item><item>two</item><item>three</item></container>', $writer->asXML());
    }

    /**
     * Test serializing an object with methods
     *
     * @test
     */
    public function objectMethods()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerMethods();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<class method="returned"/>', $writer->asXML());
    }

    /**
     * Test serializing an object with a property matcher
     *
     * @test
     */
    public function objectPropertyMatcher()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerPropertyMatcher();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testObject><one>one</one><two>two</two></testObject>', $writer->asXML());
    }

    /**
     * Test serializing an object with a method matcher
     *
     * @test
     */
    public function objectMethodMatcher()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerMethodMatcher();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<testObject><foo>foo</foo></testObject>', $writer->asXML());
    }

    /**
     * Test serializing an object with an xml fragment
     *
     * @test
     */
    public function objectFragmentProperty()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerFragmentTest();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<test><xml><foo>bar</foo></xml><foo>bar</foo></test>', $writer->asXML());
    }

    /**
     * Test serializing an object with empty attributes
     *
     * @test
     */
    public function objectEmptyAttributes()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerAttributeTest();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<test emptyProp2="" emptyMethod2=""/>', $writer->asXML());
    }

    /**
     * Test serializing an object with static properties and methods
     *
     * @test
     */
    public function objectStatic()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new stubXMLSerializerTestCase_Static();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<stubXMLSerializerTestCase_Static/>', $writer->asXML());
    }

    /**
     * Test serializing an object with umlauts
     *
     * @test
     */
    public function objectUmlauts()
    {
        $writer = new stubDomXMLStreamWriter();
        $obj    = new XMLSerializerUmlautTest();
        $this->serializer->serialize($obj, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>' . "\n" .'<test bar="Hähnchen"><foo>Hähnchen</foo></test>', $writer->asXML());
    }

    /**
     * a resource can not be serialized
     *
     * @test
     */
    public function resource()
    {
        $writer = new stubDomXMLStreamWriter();
        $fp     = fopen(__FILE__, 'rb');
        $this->serializer->serialize($fp, $writer);
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>', $writer->asXML());
        fclose($fp);
    }

    /**
     * object data container can only be created for objects
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function objectDataConstructionOnlyForObjects()
    {
        $serializerData = new stubXMLSerializerObjectData('foo');
    }

    /**
     * object data container can only be created for objects
     *
     * @test
     * @expectedException  stubIllegalArgumentException
     */
    public function objectDataOnlyForObjects()
    {
        $serializerData = stubXMLSerializerObjectData::fromObject('foo');
    }
}
?>