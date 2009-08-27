<?php
/**
 * XMLSerializer main class
 *
 * @author      Stephan Schmidt <schst@stubbles.net>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_serializer
 */
stubClassLoader::load('net::stubbles::xml::stubXMLStreamWriter',
                      'net::stubbles::xml::serializer::stubXMLSerializerObjectData'
);
/**
 * XMLSerializer main class
 *
 * @package     stubbles
 * @subpackage  xml_serializer
 */
class stubXMLSerializer extends stubBaseObject
{
    /**
     * Option to define the root tag of the serialized document
     */
    const OPT_ROOT_TAG     = 'root-tag';
    /**
     * Option to define the strategy
     */
    const OPT_STRATEGY     = 'strategy';
    /**
     * Do not export any properties or methods
     */
    const STRATEGY_NONE    = 0;
    /**
     * export only public properties
     */
    const STRATEGY_PROPS   = 1;
    /**
     * export only public methods
     */
    const STRATEGY_METHODS = 2;
    /**
     * export public properties and methods
     */
    const STRATEGY_ALL     = 3;
    /**
     * Default options
     *
     * @var  array
     */
    private $defaultOpts = array(self::OPT_ROOT_TAG => null,
                                 self::OPT_STRATEGY => self::STRATEGY_ALL
                           );
    /**
     * Currently used options
     *
     * @var  array
     */
    private $opts;

    /**
     * serialize any data structure to XML
     *
     * @param  mixed                $data       data to serialize
     * @param  stubXMLStreamWriter  $xmlWriter  XML Writer to use
     * @param  array                $opts       Options to influence the serializing
     */
    public function serialize($data, stubXMLStreamWriter $xmlWriter, array $opts = array())
    {
        // set the currently used options
        $this->opts = array_merge($this->defaultOpts, $opts);
        $this->serializeDispatcher($data, $xmlWriter, ((isset($this->opts[self::OPT_ROOT_TAG]) == true) ? ($this->opts[self::OPT_ROOT_TAG]) : (null)));
    }

    /**
     * serialize any data structure to XML
     *
     * @param  mixed                $data       data to serialize
     * @param  stubXMLStreamWriter  $xmlWriter  XML Writer to use
     * @param  string               $tagName    name of the XML tag
     */
    protected function serializeDispatcher($data, stubXMLStreamWriter $xmlWriter, $tagName = null)
    {
        switch (gettype($data)) {
            case 'NULL':
                if (null === $tagName) {
                    $tagName = 'null';
                }
                
                $xmlWriter->writeStartElement($tagName);
                $xmlWriter->writeStartElement('null');
                $xmlWriter->writeEndElement();
                $xmlWriter->writeEndElement();
                break;
            
            case 'boolean':
                if (null === $tagName) {
                    $tagName = 'boolean';
                }
                
                $xmlWriter->writeStartElement($tagName);
                $xmlWriter->writeText($data === true ? 'true' : 'false');
                $xmlWriter->writeEndElement();
                break;
            
            case 'string':
            case 'integer':
            case 'double':
                if (null === $tagName) {
                    $tagName = gettype($data);
                }
                
                $xmlWriter->writeStartElement($tagName);
                $xmlWriter->writeText(strval($data));
                $xmlWriter->writeEndElement();
                break;
            
            case 'array':
                $this->serializeArray($data, $xmlWriter, $tagName);
                break;
            
            case 'object':
                $this->serializeObject($data, $xmlWriter, $tagName);
                break;
            
            default:
                // nothing to do
        }
    }

    /**
     * serialize an object
     *
     * @param  object               $object     object to serialize
     * @param  stubXMLStreamWriter  $xmlWriter  XML Writer to use
     * @param  string               $tagName    name of the XML tag
     */
    protected function serializeObject($object, stubXMLStreamWriter $xmlWriter, $tagName)
    {
        $serializerData = stubXMLSerializerObjectData::fromObject($object);
        $xmlWriter->writeStartElement($serializerData->getTagName($tagName));
        $strategy = $serializerData->getStrategy($this->opts[self::OPT_STRATEGY]);
        foreach ($serializerData->getProperties() as $propertyName => $propertyData) {
            $this->handle($object->$propertyName, $xmlWriter, $propertyData, ($strategy & self::STRATEGY_PROPS));
        }
        
        foreach ($serializerData->getMethods() as $methodName => $methodData) {
            $this->handle($object->$methodName(), $xmlWriter, $methodData, ($strategy & self::STRATEGY_METHODS));
        }
        
        $xmlWriter->writeEndElement();
    }

    /**
     * serializes given value with instructions from $data
     *
     * @param  mixed                               $value          the value to serialize
     * @param  stubXMLStreamWriter                 $xmlWriter      XML Writer to use
     * @param  array<string,array<string,scalar>>  $data           instructions on how to serialize
     * @param  int                                 $mustSerialize  whether the element must be serialized or not
     */
    protected function handle($value, stubXMLStreamWriter $xmlWriter, array $data, $mustSerialize)
    {
        switch ($data['type']) {
            case 'attribute':
                if ('' === (string) $value && true === $data['shouldSkipEmpty']) {
                    return;
                }
                
                $xmlWriter->writeAttribute($data['attributeName'], (string) $value);
                break;
            
            case 'fragment':
                if (null != $data['tagName']) {
                    $xmlWriter->writeStartElement($data['tagName']);
                    $xmlWriter->writeXmlFragment($value);
                    $xmlWriter->writeEndElement();
                } else {
                    $xmlWriter->writeXmlFragment($value);
                }
                break;
            
            default:
                if (false === $data['mustSerialize'] && 0 === $mustSerialize) {
                    return;
                }
                
                if (is_array($value) === true) {
                    $this->serializeArray($value, $xmlWriter, $data['tagName'], $data['elementName']);
                } else {
                    $this->serializeDispatcher($value, $xmlWriter, $data['tagName']);
                }
        }
    }

    /**
     * serialize an array
     *
     * @param  array                $array       array to serialize
     * @param  stubXMLStreamWriter  $xmlWriter   XML Writer to use
     * @param  string               $tagName     'root' name for the array
     * @param  string               $defaultTag  The default tag for indexed arrays
     */
    protected function serializeArray($array, stubXMLStreamWriter $xmlWriter, $tagName, $defaultTag = null)
    {
        if (null === $tagName) {
            $tagName = 'array';
        }
        
        if (false !== $tagName) {
            $xmlWriter->writeStartElement($tagName);
        }
        
        foreach ($array as $key => $value) {
            if (is_int($key) === true) {
                if (null === $defaultTag) {
                    $this->serializeDispatcher($value, $xmlWriter);
                } else {
                    $this->serializeDispatcher($value, $xmlWriter, $defaultTag);
                }
            } else {
                $this->serializeDispatcher($value, $xmlWriter, $key);
            }
        }
        
        if (false !== $tagName) {
            $xmlWriter->writeEndElement();
        }
    }
}
?>