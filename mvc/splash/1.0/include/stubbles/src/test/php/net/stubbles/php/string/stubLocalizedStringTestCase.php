<?php
/**
 * Tests for net::stubbles::php::string::stubLocalizedString.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  php_string_test
 */
stubClassLoader::load('net::stubbles::php::string::stubLocalizedString',
                      'net::stubbles::xml::serializer::annotations::stubXMLAttributeAnnotation',
                      'net::stubbles::xml::serializer::annotations::stubXMLTagAnnotation'
);
/**
 * Tests for net::stubbles::php::string::stubLocalizedString.
 *
 * @package     stubbles
 * @subpackage  php_string_test
 * @group       php
 * @group       php_string
 */
class stubLocalizedStringTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubLocalizedString
     */
    protected $localizedString;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->localizedString = new stubLocalizedString('en_EN', 'This is a localized string.');
    }

    /**
     * make sure annotations are present
     *
     * @test
     */
    public function annotationsPresent()
    {
        $class = $this->localizedString->getClass();
        $this->assertTrue($class->hasAnnotation('XMLTag'));
        $this->assertTrue($class->getMethod('getLocale')->hasAnnotation('XMLAttribute'));
        $this->assertTrue($class->getMethod('getMessage')->hasAnnotation('XMLTag'));
    }

    /**
     * locale should be returned
     *
     * @test
     */
    public function localeAttribute()
    {
        $this->assertEquals('en_EN', $this->localizedString->getLocale());
    }

    /**
     * content should be returned
     *
     * @test
     */
    public function contentOfString()
    {
        $this->assertEquals('This is a localized string.', $this->localizedString->getMessage());
    }
}
?>