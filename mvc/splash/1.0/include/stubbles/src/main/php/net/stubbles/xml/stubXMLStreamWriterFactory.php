<?php
/**
 * Factory to create a xml stream writer.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @package     stubbles
 * @subpackage  xml
 */
stubClassLoader::load('net::stubbles::xml::stubXMLException',
                      'net::stubbles::xml::stubXMLStreamWriter'
);
/**
 * Factory to create a xml stream writer.
 *
 * @package     stubbles
 * @subpackage  xml
 * @static
 */
class stubXMLStreamWriterFactory
{
    /**
     * list of available streamwriter types
     *
     * @var  array
     */
    protected static $types = array('dom'       => 'Dom',
                                    'xmlwriter' => 'LibXml'
                              );
    /**
     * default version of xml stream writers to create
     *
     * @var  string
     */
    protected static $version  = '1.0';
    /**
     * default encoding of xml stream writers to create
     *
     * @var  string
     */
    protected static $encoding = 'UTF-8';

    /**
     * sets the default version of xml stream writers to create
     *
     * @param  string  $version
     */
    public static function setVersion($version)
    {
        self::$version = $version;
    }

    /**
     * sets the default encoding of xml stream writers to create
     *
     * @param  string  $encoding
     */
    public static function setEncoding($encoding)
    {
        self::$encoding = $encoding;
    }

    /**
     * creates a xml stream writer of the given type
     *
     * @param   string               $type  concrete type to create
     * @return  stubXMLStreamWriter
     */
    public static function create($type)
    {
        $fqClassName = self::getFqClassName($type);
        $nqClassName = stubClassLoader::getNonQualifiedClassName($fqClassName);
        if (class_exists($nqClassName, false) === false) {
            stubClassLoader::load($fqClassName);
        }

        $xmlStreamWriter = new $nqClassName(self::$version, self::$encoding);
        return $xmlStreamWriter;
    }

    /**
     * creates a xml stream writer depending on available xml extensions
     *
     * If an order is submitted it will use this order, else it uses the default
     * order.
     * Warning: if a list of features is provided an instance of every possible
     * xml stream writer will be created to check the feature list until a
     * sufficient xml stream writer class was found.
     *
     * @param   array                $order     optional  extensions in order to use
     * @param   array                $features  optional  features the implementation must provide
     * @return  stubXMLStreamWriter
     * @throws  stubXMLException
     */
    public static function createAsAvailable(array $order = null, array $features = array())
    {
        if (null === $order) {
            $order = array_keys(self::$types);
        }

        foreach ($order as $xmlExtension) {
            $name = self::checkExtension($xmlExtension);
            if (null === $name) {
                continue;
            }
            
            $xmlStreamWriter = self::checkFeatures($name, $features);
            if (null !== $xmlStreamWriter) {
                return $xmlStreamWriter;
            }
        }

        throw new stubXMLException('Not any single xml extension available that provides the requested features, can not create a xml stream writer!');
    }

    /**
     * returns full qualified class name for the xml stream writer of the given type
     *
     * @param   string  $type  concrete type of stream writer
     * @return  string
     */
    public static function getFqClassName($type)
    {
        return 'net::stubbles::xml::stub' . $type . 'XMLStreamWriter';
    }

    /**
     * returns full qualified class name for the xml stream writer depending on available xml extensions
     * 
     * If an order is submitted it will use this order, else it uses the default
     * order.
     * Warning: if a list of features is provided an instance of every possible
     * xml stream writer will be created to check the feature list until a
     * sufficient xml stream writer class was found.
     *
     * @param   array   $order     optional  extensions in order to use
     * @param   array   $features  optional  features the implementation must provide
     * @return  string
     * @throws  stubXMLException
     */
    public static function getFqClassNameAsAvailable(array $order = null, array $features = array())
    {
        if (null === $order) {
            $order = array_keys(self::$types);
        }
        
        foreach ($order as $xmlExtension) {
            $name = self::checkExtension($xmlExtension);
            if (null === $name) {
                continue;
            }
            
            if (count($features) === 0) {
                return self::getFqClassName($name);
            }
            
            $xmlStreamWriter = self::checkFeatures($name, $features);
            if (null !== $xmlStreamWriter) {
                return $xmlStreamWriter->getClassName();
            }
        }
        
        throw new stubXMLException('Not any single xml extension available that provides the requested features, can not return a class name for a xml stream writer!');
    }

    /**
     * checks given extension and returns their type name
     * 
     * If extension is not loaded the return value will be null.
     *
     * @param   string  $xmlExtension  name of the extension to check
     * @return  string
     */
    protected static function checkExtension($xmlExtension)
    {
        if (extension_loaded($xmlExtension) === false) {
            return null;
        }
        
        return ((isset(self::$types[$xmlExtension]) === true) ? (self::$types[$xmlExtension]) : ($xmlExtension));
    }

    /**
     * method to check if a given type of xml stream writer has requested features
     * 
     * Returns an instance of the xml stream writer if it has the requested
     * features, else null.
     *
     * @param   string               $type      type of stream writer to check
     * @param   array                $features  features the implementation must provide
     * @return  stubXMLStreamWriter
     */
    protected static function checkFeatures($type, array $features)
    {
        $writer = self::create($type);
        foreach ($features as $feature) {
            if ($writer->hasFeature($feature) === false) {
                return null;
            }
        }
        
        return $writer;
    }
}
?>