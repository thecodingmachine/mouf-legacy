star60      20080709162958                                                                                                                                                                                                                                     net::xjconf::converters::AbstractObjectValueConverter                                                                                                                                                                                   7712    0               net::xjconf::converters::ArrayValueConverter                                                                                                                                                                                            2712    7712            net::xjconf::converters::AutoPrimitiveValueConverter                                                                                                                                                                                    1402    10424           net::xjconf::converters::ConstructorValueConverter                                                                                                                                                                                      3862    11826           net::xjconf::converters::factories::ArrayValueConverterFactory                                                                                                                                                                          1035    15688           net::xjconf::converters::factories::AutoPrimitiveValueConverterFactory                                                                                                                                                                  981     16723           net::xjconf::converters::factories::ConstructorValueConverterFactory                                                                                                                                                                    1280    17704           net::xjconf::converters::factories::FactoryMethodValueConverterFactory                                                                                                                                                                  1487    18984           net::xjconf::converters::factories::PrimitiveValueConverterFactory                                                                                                                                                                      648     20471           net::xjconf::converters::factories::StaticClassValueConverterFactory                                                                                                                                                                    1265    21119           net::xjconf::converters::factories::ValueConverterFactory                                                                                                                                                                               778     22384           net::xjconf::converters::factories::ValueConverterFactoryChain                                                                                                                                                                          2584    23162           net::xjconf::converters::FactoryMethodValueConverter                                                                                                                                                                                    3304    25746           net::xjconf::converters::PrimitiveValueConverter                                                                                                                                                                                        2252    29050           net::xjconf::converters::StaticClassValueConverter                                                                                                                                                                                      4228    31302           net::xjconf::converters::ValueConverter                                                                                                                                                                                                 500     35530           net::xjconf::DefinedTag                                                                                                                                                                                                                 4746    36030           net::xjconf::DefinitionParser                                                                                                                                                                                                           8894    40776           net::xjconf::definitions::AbstractTagDefinition                                                                                                                                                                                         3396    49670           net::xjconf::definitions::AttributeDefinition                                                                                                                                                                                           6928    53066           net::xjconf::definitions::CDataDefinition                                                                                                                                                                                               4150    59994           net::xjconf::definitions::ChildDefinition                                                                                                                                                                                               3322    64144           net::xjconf::definitions::ConcreteTagDefinition                                                                                                                                                                                         924     67466           net::xjconf::definitions::ConstructorDefinition                                                                                                                                                                                         3457    68390           net::xjconf::definitions::Definition                                                                                                                                                                                                    1869    71847           net::xjconf::definitions::FactoryMethodDefinition                                                                                                                                                                                       3848    73716           net::xjconf::definitions::handler::AbstractTagDefinitionHandler                                                                                                                                                                         4289    77564           net::xjconf::definitions::handler::AttributeDefinitionHandler                                                                                                                                                                           2607    81853           net::xjconf::definitions::handler::CDataDefinitionHandler                                                                                                                                                                               2111    84460           net::xjconf::definitions::handler::ChildDefinitionHandler                                                                                                                                                                               2058    86571           net::xjconf::definitions::handler::ConcreteTagDefinitionHandler                                                                                                                                                                         4193    88629           net::xjconf::definitions::handler::ConstructorDefinitionHandler                                                                                                                                                                         1868    92822           net::xjconf::definitions::handler::DefinitionHandler                                                                                                                                                                                    1136    94690           net::xjconf::definitions::handler::DefinitionHandlerFactory                                                                                                                                                                             1117    95826           net::xjconf::definitions::handler::EmptyDefinitionHandler                                                                                                                                                                               1259    96943           net::xjconf::definitions::handler::FactoryMethodDefinitionHandler                                                                                                                                                                       2170    98202           net::xjconf::definitions::handler::MethodCallTagDefinitionHandler                                                                                                                                                                       2250    100372          net::xjconf::definitions::handler::TagDefinitionHandler                                                                                                                                                                                 598     102622          net::xjconf::definitions::MethodCallTagDefinition                                                                                                                                                                                       3788    103220          net::xjconf::definitions::NamespaceDefinition                                                                                                                                                                                           2128    107008          net::xjconf::definitions::NamespaceDefinitions                                                                                                                                                                                          3575    109136          net::xjconf::definitions::TagDefinition                                                                                                                                                                                                 11277   112711          net::xjconf::definitions::ValueModifier                                                                                                                                                                                                 566     123988          net::xjconf::exceptions::InvalidNamespaceDefinitionException                                                                                                                                                                            424     124554          net::xjconf::exceptions::InvalidTagDefinitionException                                                                                                                                                                                  406     124978          net::xjconf::exceptions::MissingAttributeException                                                                                                                                                                                      390     125384          net::xjconf::exceptions::UnknownNamespaceException                                                                                                                                                                                      414     125774          net::xjconf::exceptions::UnknownTagException                                                                                                                                                                                            396     126188          net::xjconf::exceptions::UnsupportedOperationException                                                                                                                                                                                  420     126584          net::xjconf::exceptions::ValueConversionException                                                                                                                                                                                       376     127004          net::xjconf::exceptions::XJConfException                                                                                                                                                                                                279     127380          net::xjconf::ext::Extension                                                                                                                                                                                                             819     127659          net::xjconf::ext::xinc::XInclude                                                                                                                                                                                                        2054    128478          net::xjconf::ext::xinc::XIncludeException                                                                                                                                                                                               366     130532          net::xjconf::GenericTag                                                                                                                                                                                                                 4413    130898          net::xjconf::Tag                                                                                                                                                                                                                        2250    135311          net::xjconf::XJConfClassLoader                                                                                                                                                                                                          625     137561          net::xjconf::XJConfFacade                                                                                                                                                                                                               6192    138186          net::xjconf::XJConfLoader                                                                                                                                                                                                               3331    144378          net::xjconf::XmlParser                                                                                                                                                                                                                  11404   147709          <?php
/**
 * Base class to convert a value to an object.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::ValueConverter',
                   'exceptions::ValueConversionException'
);
/**
 * Base class to convert a value to an object.
 *
 * @package     XJConf
 * @subpackage  converters
 */
abstract class AbstractObjectValueConverter implements ValueConverter
{
    /**
     * Name of the target class
     *
     * @var  string
     */
    protected $className;

    /**
     * Enter description here...
     *
     * @param Tag $tag
     * @param TagDefinition $def
     * @param unknown_type $instance
     */
    protected function callMethods(Tag $tag, TagDefinition $def, $instance) {
        $children = $tag->getChildren();
        foreach ($children as $child) {
            if (!$child instanceof ValueModifier) {
                continue;
            }
        }
    }

    /**
     * Add all attributes using the appropriate setter methods
     *
     * @param   Tag            $tag
     * @param   TagDefinition  $def
     * @param   object         $instance
     * @throws  ValueConversionException
     */
    protected function addAttributesToValue(Tag $tag, TagDefinition $def, $instance)
    {
        $class = new ReflectionClass(get_class($instance));
        // set all attributes
        foreach ($def->getAttributes() as $att) {
            $val = $att->convertValue($tag);
            // attribute has not been set and there is no
            // default value, skip the method call
            if (null === $val) {
                continue;
            }

            try {
                if ($class->hasMethod($att->getSetterMethod($tag)) == true) {
                    $method = $class->getMethod($att->getSetterMethod($tag));
                } elseif ($class->hasMethod('__set') == true) {
                    $method = $class->getMethod('__set');
                } elseif ($class->hasProperty($att->getName()) == true) {
                    $property = $class->getProperty($att->getName());
                    if ($property->isPublic() == true) {
                        $property->setValue($instance, $val);
                        continue;
                    }
                    
                    throw new ValueConversionException('Could not add attribute "' . $att->getName() . '" to "' . $this->getType() . '" using "' . $att->getSetterMethod($tag) . '()" or "__set()" or public property "' . $att->getName() . '", no such method defined.');
                } else {
                    throw new ValueConversionException('Could not add attribute "' . $att->getName() . '" to "' . $this->getType() . '" using "' . $att->getSetterMethod($tag) . '()" or "__set()" or public property "' . $att->getName() . '", no such method defined.');
                }
                
                if ($method->getName() != '__set') {
                    $method->invoke($instance, $val);
                } else {
                    $method->invokeArgs($instance, array($att->getName(), $val));
                }
            } catch (ReflectionException $re) {
                throw new ValueConversionException('Could not set attribute "' . $att->getName() . '" of "' . $this->getType() . '" using "' . $att->getSetterMethod($tag) . '()" or "__set()" or public property "' . $att->getName() . '", exception message: "' . $re->getMessage() . '".');
            }
        }
    }

    /**
     * Add all children to the created instance
     *
     * @param   Tag         $tag
     * @param   Definition  $def
     * @param   object      $instance
     * @throws  ValueConversionException
     */
    protected function addChildrenToValue(Tag $tag, Definition $def, $instance, $ignore = array())
    {
        // traverse all children
        $children = $tag->getChildren();
        if (count($children) == 0) {
            return;
        }

        $class = new ReflectionClass(get_class($instance));
        foreach ($children as $child) {

            if (in_array($child->getName(), $ignore) == true) {
                continue;
            }

            $childDef = $child->getDefinition();
            if ($childDef instanceof ValueModifier) {
                $childDef->modifyValue($instance, $child);
                continue;
            }
            
            try {
                if ($class->hasMethod($child->getSetterMethod()) == true) {
                    $method = $class->getMethod($child->getSetterMethod());
                } elseif ($class->hasMethod($child->getKey()) == true) {
                    $method = $class->getMethod($child->getKey());
                } elseif ($class->hasMethod('__set') == true) {
                    $method = $class->getMethod('__set');
                } elseif ($class->hasProperty($child->getKey()) == true) {
                    $property = $class->getProperty($child->getKey());
                    if ($property->isPublic() == true) {
                        $property->setValue($instance, $child->getConvertedValue());
                        continue;
                    }
                    
                    throw new ValueConversionException('Could not add child "' . $child->getKey() . '" to "' . $this->getType() . '" using "' . $child->getSetterMethod() . '()" or "' . $child->getKey() . '" or "__set()" or public property "' . $child->getKey() . '", no such method defined.');
                } else {
                    throw new ValueConversionException('Could not add child "' . $child->getKey() . '" to "' . $this->getType() . '" using "' . $child->getSetterMethod() . '()" or "' . $child->getKey() . '" or "__set()" or public property "' . $child->getKey() . '", no such method defined.');
                }
                
                if ($method->getName() != '__set') {
                    $method->invoke($instance, $child->getConvertedValue());
                } else {
                    $method->invokeArgs($instance, array($child->getName(), $child->getConvertedValue()));
                }
            } catch (ReflectionException $re) {
                throw new ValueConversionException('Could not add child "' . $child->getKey() . '" to "' . $this->getType() . '" using "' . $child->getSetterMethod() . '()" or "' . $child->getKey() . '" or "__set()" or public property "' . $child->getKey() . '", exception message: "' . $re->getMessage() . '".');
            }
        }
    }

    /**
     * Add the CData to the value
     *
     * @param   Tag         $tag
     * @param   Definition  $def
     * @param   object      $instance
     * @throws  ValueConversionException
     */
    protected function addCDataToValue(Tag $tag, Definition $def, $instance, $ignore = array())
    {
        // check, whether the CData has been specifically defined
        if (!$def->hasChildDefinition('CDataDefinition')) {
            return;
        }
        $cDataDefinition = $def->getChildDefinition('CDataDefinition');
        $value = $cDataDefinition->convertValue($tag);
        try {
            $class = new ReflectionClass(get_class($instance));
            $class->getMethod($cDataDefinition->getSetterMethod($tag))->invoke($instance, $value);
        } catch (ReflectionException $re) {
            throw new ValueConversionException('Could not add cdata to "' . $this->getType() . '" using "' . $cDataDefinition->getSetterMethod($tag) . '()", exception message: "' . $re->getMessage() . '".');
        }
    }

    /**
     * returns the type of the converter
     *
     * @return  string
     */
    public function getType()
    {
        return $this->className;
    }
}
?><?php
/**
 * Converter to convert a value to an array.
 *
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::ValueConverter',
                   'definitions::Definition',
                   'definitions::TagDefinition'
);
/**
 * Converter to convert a value to an array.
 *
 * @package     XJConf
 * @subpackage  converters
 */
class ArrayValueConverter implements ValueConverter
{
    /**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  array  the converted value
     */
    public function convertValue(Tag $tag, Definition $def)
    {
        $return = array();
        $return = $this->addAttributesToValue($tag, $def, $return);
        $return = $this->addChildrenToValue($tag, $def, $return);
        return $return;
    }

   /**
     * returns the type of the converter
     *
     * @return  string
     */
    public function getType()
    {
        return 'array';
    }
    
    /**
     * Add all attributes using the appropriate setter methods
     *
     * @param   Tag            $tag
     * @param   TagDefinition  $def
     * @param   array          $array
     */
    protected function addAttributesToValue(Tag $tag, TagDefinition $def, $array)
    {
        // set all attributes
        foreach ($def->getAttributes() as $att) {
            $val = $att->convertValue($tag);
            // attribute has not been set and there is no
            // default value, skip the method call
            if (null === $val) {
                continue;
            }

            $array[$att->getName()] = $val;
        }
        
        return $array;
    }
    
    /**
     * Add all children to the created instance
     *
     * @param   Tag         $tag
     * @param   Definition  $def
     * @param   array       $array
     */
    protected function addChildrenToValue(Tag $tag, Definition $def, $array)
    {
        // traverse all children
        $children = $tag->getChildren();
        if (count($children) == 0) {
            return $array;
        }
        
        foreach ($children as $child) {
            if (null != $child->getContent()) {
                $val = $child->getContent();
            } else {
                $val = $child->getConvertedValue();
            }
            
            if (null === $val) {
                continue;
            }
            
            if ($child->getKey() === null || $child->getKey() == '__none' || strlen($child->getKey()) === 0) {
                $array[] = $val;
            } else {
                $array[$child->getKey()] = $val;
            }
        }
        
        return $array;
    }
}
?><?php
/**
 * Converter to convert a value into a primitive
 * by trying to guess its type
 *
 * @package XJConf
 * @author  Stephan Schmidt <schst@xjconf.net>
 */
XJConfLoader::load('converters::ValueConverter',
                   'exceptions::ValueConversionException'
);
/**
 * Converter to convert a value into a primitive
 * by trying to guess its type
 *
 * @package XJConf
 */
class AutoPrimitiveValueConverter implements ValueConverter {

    /**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  mixed  the converted value
     * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag, Definition $def) {
        $value = $tag->getData();

        if ($value === 'null') {
            return null;
        }
        if ($value === 'true') {
            return true;
        }
        if ($value === 'false') {
            return false;
        }

        if (preg_match('/^[+-]?[0-9]+$/', $value)) {
            settype($value, 'int');
        	return $value;
        }
        if (preg_match('/^[+-]?[0-9]*\.[0-9]+$/', $value)) {
        	settype($value, 'double');
        	return $value;
        }
        return $value;
    }

   /**
     * Returns the type of the converter
     *
     * @return  string
     */
    public function getType() {
        return null;
    }
}
?><?php
/**
 * Class to convert a value to an object using the constructor of the object.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::ValueConverter',
                   'converters::AbstractObjectValueConverter',
                   'exceptions::ValueConversionException'
);
/**
 * Class to convert a value to an object using the constructor of the object.
 *
 * @package     XJConf
 * @subpackage  converters
 */
class ConstructorValueConverter extends AbstractObjectValueConverter implements ValueConverter
{
    /**
     * Create a new converter
     *
     * @param  string  $className  name of the target class
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  mixed  the converted value
     * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag, Definition $def)
    {
        if (class_exists($this->className) == false) {
            throw new ValueConversionException('Class "' . $this->className . '" does not exist.');
        }

        $constructor = $def->getChildDefinition('ConstructorDefinition');
        $tmpParams   = $constructor->getParams();
        $cParams     = array();
        // get all values and their types
        foreach ($tmpParams as $key => $conParam) {
            $cParams[] = $conParam->convertValue($tag);
        }

        // try to create a new instance
        try {
            $refClass  = new ReflectionClass($this->className);
            if (count($cParams) > 1 && method_exists($refClass, 'newInstanceArgs') == true) {
                $instance = $refClass->newInstanceArgs($cParams);
            } elseif (count($cParams) == 1) {
                // check if the constructor has arguments
                // if the first argument has a type hint and we have an empty 
                // string replace this with an appropriate value
                $refMethod = $refClass->getConstructor();
                if (null != $refMethod) {
                    $params = $refMethod->getParameters();
                    if (count($params) >= 1 && $params[0]->getClass() != null && empty($cParams[0]) == true) {
                        $cParams[0] = null;
                    } elseif (count($params) >= 1 && $params[0]->isArray() == true && empty($cParams[0]) == true) {
                        if ($params[0]->allowsNull() == true) {
                            $cParams[0] = null;
                        } else {
                            $cParams[0] = array();
                        }
                    }
                }
                try {
                    $instance = $refClass->newInstance($cParams[0]);
                } catch (ReflectionException $re) {
                    $instance = $refClass->newInstance();
                }
            } elseif (count($cParams) == 0) {
                $instance = $refClass->newInstance();
            } else {
                throw new ValueConversionException('Could not create instance of "' . $this->className . '" as Reflection does not support newInstanceArgs().');
            }
        } catch (ReflectionException $re) {
            throw new ValueConversionException('Could not create instance of "' . $this->className . '": ' . $re->getMessage());
        }

        // add attributes and child elements
        if ($def instanceof TagDefinition) {
            $this->addAttributesToValue($tag, $def, $instance);
            $this->addCDataToValue($tag, $def, $instance);
        }
        $this->addChildrenToValue($tag, $def, $instance, $constructor->getUsedChildrenNames());

        return $instance;
    }
}
?><?php
/**
 * Factory to create an ArrayValueConverter.
 * 
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::ArrayValueConverter'
);
/**
 * Factory to create an ArrayValueConverter.
 * 
 * @package     XJConf
 * @subpackage  converters
 */
class ArrayValueConverterFactory implements ValueConverterFactory
{
    /**
     * Decides whether the ArrayValueConverter is responsible for the given Definition.
     *
     * @param   Definition  $def
     * @return  boolean     true if is responsible, else false
     */
    public function isResponsible(Definition $def)
    {
        return ($def->getType() == 'array');
    }
    
    /**
     * creates an instance of the ArrayValueConverter
     *
     * @param   Definition          $def
     * @return  ArrayValueConverter
     */
    public function createValueConverter(Definition $def)
    {
        $converter = new ArrayValueConverter();
        return $converter;
    }
}
?><?php
/**
 * Factory for AutoPrimitiveValueConverter objects
 *
 * @author  Stephan Schmidt <schst@xjconf.net>
 * @package XJConf
 */
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::AutoPrimitiveValueConverter');

/**
 * Factory for AutoPrimitiveValueConverter objects
 *
 * @package XJConf
 */
class AutoPrimitiveValueConverterFactory implements ValueConverterFactory {

    /**
     * This factory is only responsible, if the type
     * is set to "xjconf:auto-primitive"
     *
     * @param Definition $def
     * @return boolean
     */
    public function isResponsible(Definition $def) {
        return 'xjconf:auto-primitive' === $def->getType();
    }

    /**
     * Create a value converter
     *
     * @param Definition $def
     * @return ValueConverter
     */
    public function createValueConverter(Definition $def) {
        $converter = new AutoPrimitiveValueConverter();
        return $converter;
    }
}
?><?php
/**
 * Factory to create a ConstructorValueConverter.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::ConstructorValueConverter');
/**
 * Factory to create an ConstructorValueConverter.
 * 
 * @package     XJConf
 * @subpackage  converters
 */
class ConstructorValueConverterFactory implements ValueConverterFactory
{

    /**
     * Decides whether the ConstructorValueConverter is responsible for the given Definition.
     *
     * @param   Definition  $def
     * @return  boolean     true if is responsible, else false
     */
    public function isResponsible(Definition $def)
    {
        if (class_exists($def->getType()) == false) {
            return false;
        }
        
        return $def->hasChildDefinition('ConstructorDefinition');
    }
    
    /**
     * creates an instance of the ConstructorValueConverter
     *
     * @param   Definition                 $def
     * @return  ConstructorValueConverter
     */
    public function createValueConverter(Definition $def)
    {
        $converter = new ConstructorValueConverter($def->getType());
        return $converter;
    }
}
?><?php
/**
 * Factory to create a FactoryMethodValueConverter.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::FactoryMethodValueConverter'
);
/**
 * Factory to create an FactoryMethodValueConverter.
 * 
 * @package     XJConf
 * @subpackage  converters
 */
class FactoryMethodValueConverterFactory implements ValueConverterFactory
{
    /**
     * Decides whether the FactoryMethodValueConverter is responsible for the given Definition.
     *
     * @param   Definition  $def
     * @return  boolean     true if is responsible, else false
     */
    public function isResponsible(Definition $def)
    {
        if (class_exists($def->getType()) == false) {
            return false;
        }
        
        return $def->hasChildDefinition('FactoryMethodDefinition');
    }
    
    /**
     * creates an instance of the FactoryMethodValueConverter
     *
     * @param   Definition                   $def
     * @return  FactoryMethodValueConverter
     */
    public function createValueConverter(Definition $def)
    {
        $factoryMethod = $def->getChildDefinition('FactoryMethodDefinition');
        if (null == $factoryMethod) {
            return null;
        }
        
        $converter = new FactoryMethodValueConverter($def->getType(), $factoryMethod->getName());
        return $converter;
    }
}
?><?php
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::PrimitiveValueConverter');

class PrimitiveValueConverterFactory implements ValueConverterFactory {

    private $primitives = array (
                            'int', 'integer', 'double', 'float', 'bool', 'boolean', 'string'
                          );

    public function isResponsible(Definition $def) {
        return in_array($def->getType(), $this->primitives);
    }

    public function createValueConverter(Definition $def) {
        $converter = new PrimitiveValueConverter($def->getType());
        return $converter;
    }
}
?><?php
/**
 * Factory to create a StaticClassValueConverter.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::StaticClassValueConverter');
/**
 * Factory to create an StaticClassValueConverter.
 *
 * @package     XJConf
 * @subpackage  converters
 */
class StaticClassValueConverterFactory implements ValueConverterFactory
{

    /**
     * Decides whether the ConstructorValueConverter is responsible for the given Definition.
     *
     * @param   Definition  $def
     * @return  boolean     true if is responsible, else false
     */
    public function isResponsible(Definition $def)
    {
        if (class_exists($def->getType()) == false) {
            return false;
        }

        if (!$def instanceof TagDefinition) {
            return false;
        }
        return $def->isStatic();
    }

    /**
     * creates an instance of the ConstructorValueConverter
     *
     * @param   Definition                 $def
     * @return  ConstructorValueConverter
     */
    public function createValueConverter(Definition $def)
    {
        $converter = new StaticClassValueConverter($def->getType());
        return $converter;
    }
}
?><?php
/**
 * Interface for factories, that create a value converter for
 * a value.
 *
 * All factories can be put in a chain of responsibility, so
 * that they can decide themselves, whether they are able
 * to create the ValueConverter instance.
 *
 * @author  Stephan Schmidt <me@schst.net>
 */
interface ValueConverterFactory {

    /**
     * Check, whether the factory is responsible for
     * creating the ValueConverter for the definition
     *
     * @param   Definition  $def
     * @return  boolean
     */
    public function isResponsible(Definition $def);

    /**
     * Create a value converter for the definition
     *
     * @param   Definition      $def
     * @return  ValueConverter
     */
    public function createValueConverter(Definition $def);
}
?><?php
/**
 * Factory to create the correct ValueConverterFactory.
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <frank.kleine@schlund.de>
 * @package     XJConf
 * @subpackage  converters
 */
XJConfLoader::load('converters::factories::ValueConverterFactory',
                   'converters::factories::AutoPrimitiveValueConverterFactory',
                   'converters::factories::PrimitiveValueConverterFactory',
                   'converters::factories::ArrayValueConverterFactory',
                   'converters::factories::ConstructorValueConverterFactory',
                   'converters::factories::FactoryMethodValueConverterFactory',
                   'converters::factories::StaticClassValueConverterFactory',
                   'definitions::Definition'
);
/**
 * Factory to create the correct ValueConverterFactory.
 *
 * @package     XJConf
 * @subpackage  converters
 */
class ValueConverterFactoryChain
{
    /**
     * contains a list of all available ValueConverterFactorys
     *
     * @var  array<ValueConverterFactory>
     */
    private static $factories = array();

    /**
     * add a ValueConverterFactory to list of known ValueConverterFactorys
     *
     * @param  ValueConverterFactory  $factory
     */
    public static function push(ValueConverterFactory $factory)
    {
        array_push(self::$factories, $factory);
    }

    /**
     * return the correct ValueConverterFactory depending on the given definition
     *
     * @param   Definition             $def
     * @return  ValueConverterFactory
     * @throws  ValueConversionException
     */
    public static function getFactory(Definition $def)
    {
        foreach (self::$factories as $factory) {
            if ($factory->isResponsible($def) == true) {
                return $factory;
            }
        }
        throw new ValueConversionException('Could not find ValueConverterFactory for definition of tag <' . $def->getName() . '> with type "' . $def->getType() . '". If this type is a class it probably has not been loaded.');
    }

}
// initialize the ValueConverterFactoryChain
ValueConverterFactoryChain::push(new PrimitiveValueConverterFactory());
ValueConverterFactoryChain::push(new AutoPrimitiveValueConverterFactory());
ValueConverterFactoryChain::push(new ArrayValueConverterFactory());
ValueConverterFactoryChain::push(new StaticClassValueConverterFactory());
ValueConverterFactoryChain::push(new ConstructorValueConverterFactory());
ValueConverterFactoryChain::push(new FactoryMethodValueConverterFactory());
?><?php
/**
 * Value converter that uses a factory method to create an object.
 *
 * @author  Stephan Schmidt <me@schst.net>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::ValueConverter',
                   'converters::AbstractObjectValueConverter',
                   'exceptions::ValueConversionException'
);
/**
 * Value converter that uses a factory method to create an object.
 *
 * @package     XJConf
 * @subpackage  converters
 */
class FactoryMethodValueConverter extends AbstractObjectValueConverter implements ValueConverter
{
	/**
	 * name of method to use as factory method
	 *
	 * @var  string
	 */
	protected $methodName;

	/**
	 * constructor
	 *
	 * @param  string  $className   name of class to use
	 * @param  string  $methodName  name of method to use
	 */
	public function __construct($className, $methodName)
	{
		$this->className  = $className;
		$this->methodName = $methodName;
	}

	/**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  mixed  the converted value
     * @throws  ValueConversionException
     */
	public function convertValue(Tag $tag, Definition $def)
	{
	    if (class_exists($this->className) == false) {
	        throw new ValueConversionException('Class "' . $this->className . '" does not exist.');
	    }

	    $factoryMethod = $def->getChildDefinition('FactoryMethodDefinition');
	    $tmpParams     = $factoryMethod->getParams();
        $cParams       = array();
        // get all values and their types
        foreach ($tmpParams as $key => $conParam) {
            $cParams[] = $conParam->convertValue($tag);
        }

        try {
            $refClass  = new ReflectionClass($this->className);
            $refMethod = $refClass->getMethod($this->methodName);
            if (!$refMethod->isStatic()) {
                throw new ValueConversionException('Could not create instance of "' . $this->className . '" using the factory method "' . $this->methodName . '" as it is no static method.');
            }
            if (method_exists($refMethod, 'invokeArgs') == true) {
        	    $instance = $refMethod->invokeArgs(null, $cParams);
        	} elseif (count($cParams) == 0) {
        	    $instance = $refMethod->invoke(null, null);
        	} elseif (count($cParams) == 1) {
        	    $instance = $refMethod->invoke(null, $cParams[0]);
        	} else {
        	    throw new ValueConversionException('Could not create instance of "' . $this->className . '" as Reflection does not support invokeArgs().');
        	}
        } catch (ReflectionException $re) {
        	throw new ValueConversionException('Could not create instance of "' . $this->className . '" using the factory method "' . $this->methodName . '": ' . $re->getMessage());
        }

        if (null != $instance && get_class($instance) !== false) {
            // add attributes and child elements
            if ($def instanceof TagDefinition) {
                $this->addAttributesToValue($tag, $def, $instance);
                $this->addCDataToValue($tag, $def, $instance);
            }
            $this->addChildrenToValue($tag, $def, $instance, $factoryMethod->getUsedChildrenNames());
        }

        return $instance;
	}
}
?><?php
/**
 * Converter to convert a value to a primitive type
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@xjconf.net>
 * @package     XJConf
 * @subpackage  converters
 */
XJConfLoader::load('converters::ValueConverter',
                   'converters::PrimitiveValueConverter',
                   'exceptions::ValueConversionException'
);
/**
 * Converter to convert a value to a primitive type
 *
 * @package     XJConf
 * @subpackage  converters
 */
class PrimitiveValueConverter implements ValueConverter
{
    /**
     * type of the primitive
     *
     * @var  string
     */
    private $type;

    /**
     * Create a new converter
     * @param type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  mixed  the converted value
     * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag, Definition $def)
    {
        $value = $tag->getData();
        switch ($this->type) {
            case 'boolean':
            case 'bool':
                if ('false' === $value || '0' === $value) {
                    return false;
                }
                
                return (boolean) $value;
            case 'integer':
            case 'int':
                if ('0' === $value{0}) {
                    return octdec($value);
                }
                
                return (integer) $value;
            case 'double':
                return (double) $value;
            case 'float':
                return (float) $value;
            case 'string':
                return (string) $value;
        }
        
        return null;
    }

   /**
     * returns the type of the converter
     *
     * @return  string
     */
    public function getType()
    {
        switch ($this->type) {
            case 'boolean':
            case 'bool':
            case 'integer':
            case 'int':
            case 'double':
            case 'float':
            case 'string':
                return $this->type;
        }

        return null;
    }
}
?><?php
/**
 * Class to convert a value to an object using the constructor of the object.
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@xjconf.net>
 * @package     XJConf
 * @subpackage  converters
 */
XJConfLoader::load('converters::ValueConverter',
                   'exceptions::ValueConversionException'
);
/**
 * Class to convert a value to an object using the constructor of the object.
 *
 * @package     XJConf
 * @subpackage  converters
 */
class StaticClassValueConverter implements ValueConverter
{
    /**
     * Name of the target class
     *
     * @var  string
     */
    protected $className;

    /**
     * Create a new converter
     *
     * @param  string  $className  name of the target class
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  null   no value is created
     * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag, Definition $def)
    {
        if (class_exists($this->className) == false) {
            throw new ValueConversionException('Class "' . $this->className . '" does not exist.');
        }

        if ($def instanceof TagDefinition) {
            $this->addAttributesToValue($tag, $def);
        }
        $this->addChildrenToValue($tag, $def);
        return null;
    }

    public function getType() {
        return null;
    }

    /**
     * Add all attributes using the appropriate setter methods
     *
     * @param   Tag            $tag
     * @param   TagDefinition  $def
     * @param   object         $instance
     * @throws  ValueConversionException
     */
    protected function addAttributesToValue(Tag $tag, TagDefinition $def)
    {
        $class = new ReflectionClass($this->className);
        // set all attributes
        foreach ($def->getAttributes() as $att) {
            $val = $att->convertValue($tag);
            // attribute has not been set and there is no
            // default value, skip the method call
            if (null === $val) {
                continue;
            }

            try {
                $method = $class->getMethod($att->getSetterMethod($tag));
                if (!$method->isStatic()) {
                    throw new ValueConversionException('Could not set attribute "' . $att->getName() . '" of "' . $this->getType() . '" using "' . $att->getSetterMethod() . '()" as the method is not static.');
                }
                $method->invoke(null, $val);
            } catch (ReflectionException $re) {
                throw new ValueConversionException('Could not set attribute "' . $att->getName() . '" of "' . $this->getType() . '" using "' . $att->getSetterMethod() . '()", exception message: "' . $re->getMessage() . '".');
            }
        }
    }

    /**
     * Add all children to the static class
     *
     * @param   Tag         $tag
     * @param   Definition  $def
     * @param   object      $instance
     * @throws  ValueConversionException
     */
    protected function addChildrenToValue(Tag $tag, Definition $def)
    {
        // traverse all children
        $children = $tag->getChildren();
        if (count($children) == 0) {
            return;
        }

        $class = new ReflectionClass($this->className);
        foreach ($children as $child) {
            try {
                $method = $class->getMethod($child->getSetterMethod());
                if (!$method->isStatic()) {
                    throw new ValueConversionException('Could not add child "' . $child->getKey() . '" to "' . $this->getType() . '" using "' . $child->getSetterMethod() . '()" as the method is not static.');
                }
                $method->invoke(null, $child->getConvertedValue());
            } catch (ReflectionException $re) {
                throw new ValueConversionException('Could not add child "' . $child->getKey() . '" to "' . $this->getType() . '" using "' . $child->getSetterMethod() . '()", exception message: "' . $re->getMessage() . '".');
            }
        }
    }

}
?><?php
/**
 * @author Stephan Schmidt <stephan.schmidt@schlund.de>
 */
interface ValueConverter
{
    /**
     * converts the given values into the given types
     *
     * @param   array  $values  list of values to convert
     * @return  mixed  the converted value
     * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag, Definition $def);

    /**
     * returns the type of the converter
     *
     * @return  string
     */
    public function getType();
}
?><?php
/**
 * Contains data of tag in the default namespace.
 *
 * @author  Stephan Schmidt <me@schst.net>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('Tag',
                   'definitions::TagDefinition'
);
/**
 * Contains data of tag in the default namespace.
 *
 * @package  XJConf
 */
class DefinedTag implements Tag
{

	/**
	 * name of the tag
	 *
	 * @var  string
	 */
	private $name = null;

	/**
	 * character data
	 *
	 * @var  string
	 */
	private $data = null;

	/**
	 * content of the tag
	 *
	 * @var  mixed
	 */
	private $content = null;

	/**
	 * attributes of the tag
	 *
	 * @var  array
	 */
	private $atts = array();

	/**
	 * Children of the tag
	 *
	 * @var  array
	 */
	private $children = array();

	/**
	 * value of the tag
	 *
	 * @var  TagDefinition
	 */
	private $tagDef = null;

	/**
	 * Create a new tag without attributes
	 *
	 * @param name   name of the tag
	 */
	public function __construct($name, $atts = array())
	{
		$this->name = $name;
		$this->atts = $atts;
	}

	/**
	 * Get the name of the tag
	 *
	 * @return   name of the tag
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the key
	 *
	 * @param  string  $key
	 */
	public function setKey($key)
	{
		$this->key = $key;
	}

	/**
	 * Get the key under which the value will be stored
	 *
	 * @return  string
	 */
	public function getKey()
	{
		return $this->tagDef->getKey($this);
	}

	/**
	 * Add text data
	 *
	 * @param   string  $buf
	 * @return  int     new length of data
	 */
	public function addData($buf)
	{
		$this->data .= $buf;
		return strlen($this->data);
	}

	/**
	 * Get the character data of the tag
	 *
	 * @return   character data
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Check, whether the tag has a certain attribute
	 *
	 * @param   string   $name
	 * @return  boolean
	 */
	public function hasAttribute($name)
	{
		return isset($this->atts[$name]);
	}

	/**
	 * get an attribute
	 *
	 * @param   string  $name  name of the attribute
	 * @return  string  value of the attribute
	 */
	public function getAttribute($name)
	{
		if ($this->hasAttribute($name) == true) {
		    return $this->atts[$name];
		}

		return null;
	}

	/**
	 * get all attributes
	 *
	 * @return  array
	 */
	public function getAttributes()
	{
		return $this->atts;
	}

	/**
     * Add a new child to this tag.
     *
     * @param child  child to add
     * @return   int    number of childs added
     */
    public function addChild(Tag $child)
    {
    	array_push($this->children, $child);
        return count($this->children);
    }

	/**
	 * Get the child with a specific name
	 *
	 * @param   string  $name
	 * @return  Tag
	 */
	public function getChild($name)
	{
	    foreach ($this->children as $child) {
		    if ($child->getName() == $name) {
				return $child;
			}
		}

		return null;
	}

	/**
	 * Get all children of the tag
	 *
	 * @return  array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
     * Set the content (overrides the character data)
     *
     * @param  mixed  $content
     */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Get the content
	 *
	 * @return  mixed
	 */
	public function getContent()
	{
		if (null != $this->content) {
			return $this->content;
		}

		return $this->getData();
	}

	/**
	 * Fetch the value
	 *
	 * @return	mixed  the value of the tag
	 */
	public function getConvertedValue()
	{
		return $this->tagDef->convertValue($this);
	}

	/**
	 * Get the type of the value
	 *
	 * @param   Tag     $tag
	 * @return  string
	 */
	public function getValueType(Tag $tag)
	{
		return $this->tagDef->getValueType($tag);
	}

	/**
	 * Get the setter method
	 *
	 * @return  string
	 */
    public function getSetterMethod()
    {
    	return $this->tagDef->getSetterMethod($this);
    }

	/**
	 * Checks, whether the tag supports indexed children
	 *
	 * @return  boolean
	 */
	public function supportsIndexedChildren()
	{
		return $this->tagDef->supportsIndexedChildren();
	}

	/**
    * Set the tag definition object used for this tag
    *
    * @param  TagDefinition  $tagDef
    */
    public function setDefinition(Definition $tagDef)
    {
        if ($tagDef instanceof ConcreteTagDefinition) {
        	$this->tagDef = $tagDef;
            return;
        }

        $this->tagDef = clone $tagDef;
        if ($tagDef instanceof TagDefinition) {
            $this->tagDef->setType($this->getAttribute($this->tagDef->getConcreteTypeAttributeName()));
        }
    }

    /**
     * get the tag definition object used for this tag
     *
     * @return  TagDefinition
     */
    public function getDefinition()
    {
        return $this->tagDef;
    }
}
?><?php
/**
 * Parse tag definitions files.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('definitions::AttributeDefinition',
                   'definitions::CDataDefinition',
                   'definitions::ChildDefinition',
                   'definitions::ConstructorDefinition',
                   'definitions::FactoryMethodDefinition',
                   'definitions::MethodCallTagDefinition',
                   'definitions::NamespaceDefinition',
                   'definitions::NamespaceDefinitions',
                   'definitions::TagDefinition',
                   'definitions::handler::DefinitionHandlerFactory',
                   'exceptions::InvalidNamespaceDefinitionException',
                   'exceptions::XJConfException'
);
/**
 * Parse tag definitions files.
 *
 * This parser reads xml files that define the tags used by other
 * xml documents which describe a data structure.
 *
 * @package  XJConf
 */
class DefinitionParser
{
    /**
     * this tag defines a namespace
     */
    const TAG_NAMESPACE      = 'namespace';
    /**
     * stack for currently open definitions
     *
     * @var  array<Definition>
     */
    private $defStack        = array();
    /**
     * stack for currently opened definition handlers
     *
     * @var  DefinitionHandler
     */
    private $defHandlerStack = array();
    /**
     * Constant for the default namespace
     */
    const DEFAULT_NAMESPACE  = '__default';
    /**
     * The current namespace
     *
     * @var  string
     */
    private $currentNamespace;
    /**
     * All extracted namespace definitions
     *
     * @var  NamespaceDefinitions
     */
    private $defs;
    /**
     * the real xml parser
     *
     * @var  XMLReader
     */
    private $reader;
    /**
     * list of node types, used for compatibility between PHP 5.0 and 5.1
     *
     * @var  array
     */
    private $nodeTypes       = array();
    /**
     * hashmap of class loaders where the key is the namespace the class loader
     *  should be used for
     *
     * @var  array<String,XJConfClassLoader>
     */
    private $classLoaders    = array();

    /**
     * constructor
     *
     * Sets the node types depending on your PHP version using the constants
     * defined by the XMLReader PHP extension.
     *
     * @param  array<String,XJConfClassLoader>  $classLoaders  optional
     */
    public function __construct($classLoaders = array())
    {
        $this->defs             = new NamespaceDefinitions();
        $this->currentNamespace = self::DEFAULT_NAMESPACE;
        $this->classLoaders     = $classLoaders;

        if (!defined('XMLREADER_ELEMENT')) {
            $this->nodeTypes = array('startTag' => XMLReader::ELEMENT,
                                     'text'     => XMLReader::TEXT,
                                     'endTag'   => XMLReader::END_ELEMENT
                               );
        } else {
            $this->nodeTypes = array('startTag' => XMLREADER_ELEMENT,
                                     'text'     => XMLREADER_TEXT,
                                     'endTag'   => XMLREADER_END_ELEMENT
                               );
        }
    }

    /**
     * returns the current namespace
     *
     * @return  string
     */
    public function getCurrentNamespace()
    {
        return $this->currentNamespace;
    }

    /**
     * returns the list of created namespace definitions
     *
     * @return  NamespaceDefinitions
     */
    public function getNamespaceDefinitions()
    {
        return $this->defs;
    }

    /**
     * check whether a class loader exists for given namespace
     *
     * @param   string  $namespace
     * @return  bool
     */
    public function hasClassLoader($namespace)
    {
        return (isset($this->classLoaders[$namespace]) == true || isset($this->classLoaders['__default']) == true);
    }

    /**
     * return the class loader for the given namespace
     *
     * @param   string             $namespace
     * @return  XJConfClassLoader
     */
    public function getClassLoader($namespace)
    {
        if (isset($this->classLoaders[$namespace]) == true) {
            return $this->classLoaders[$namespace];
        }
        
        if (isset($this->classLoaders['__default']) == true) {
            return $this->classLoaders['__default'];
        }

        return null;
    }

    /**
     * returns the definition stack
     *
     * @return  array<Definition>
     */
    public function &getDefStack()
    {
        return $this->defStack;
    }

    /**
     * initializes the parser
     */
    private function initParser()
    {
        if (null == $this->reader) {
            $this->reader = new XMLReader();
        }
    }

    /**
     * parse a tag definitions file and return
     * an instance of NamespaceDefinition
     *
     * @param   string               $filename  filename of the defintions file
     * @return  NamespaceDefinition
     * @throws  XJConfException
     * @throws  InvalidNamespaceDefinitionException
     */
    public function parse($filename)
    {
        $this->initParser();
        if (@$this->reader->open($filename) === false) {
            throw new XJConfException('Can not open file ' . $filename);
        }
        
        while ($this->reader->read()) {
            switch ($this->reader->nodeType) {
                case $this->nodeTypes['startTag']:
                    $nameSpaceURI = $this->reader->namespaceURI;
                    $elementName  = $this->reader->localName;
                    $attributes   = array();
                    $empty = $this->reader->isEmptyElement;
                    if (TRUE == $this->reader->hasAttributes) {
                        // go to first attribute
                        $attribute = $this->reader->moveToFirstAttribute();
                        // save data of all attributes
                        while (TRUE == $attribute) {
                            $attributes[$this->reader->localName] = $this->reader->value;
                            $attribute = $this->reader->moveToNextAttribute();
                        }
                    }
                    $this->startElement($nameSpaceURI, $elementName, $attributes);
                    if (true === $empty) {
                        $this->endElement($nameSpaceURI, $elementName);
                    }
                    break;

                case $this->nodeTypes['text']:
                    $this->characters($this->reader->value);
                    break;

                case $this->nodeTypes['endTag']:
                    $this->endElement($this->reader->namespaceURI, $this->reader->localName);
                    break;
            }
        }

        $this->reader->close($filename);

        return $this->defs;
    }

    /**
     * Start Element handler
     *
     * Creates the Definition object and places it on
     * the stack.
     *
     * @param   string  $namespaceURI  namespace of start tag
     * @param   string  $sName         name of start tag
     * @param   array   $atts          attributes of tag
     * @throws  InvalidNamespaceDefinitionException
     */
    private function startElement($namespaceURI, $sName, $atts)
    {
        // a new namespace
        if (self::TAG_NAMESPACE  == $sName) {
            if (isset($atts['uri']) == false) {
                throw new InvalidNamespaceDefinitionException('The <' . self::TAG_NAMESPACE . '> tag is missing the uri attribute.');
            }

            // change current namespace to new namespace
            $this->currentNamespace = $atts['uri'];
            return;
        }

        // create the appropriate definition handler and use this
        // to create the required definition
        $defHandler = DefinitionHandlerFactory::create($sName, $this);
        $def        = $defHandler->startElement($namespaceURI, $sName, $atts);
        if (null != $def) {
            array_push($this->defStack, $def);
        }

        array_push($this->defHandlerStack, $defHandler);
    }

    /**
     * End Element handler
     *
     * Fetches the Definition from the stack and
     * adds it to the NamespaceDefinition object.
     *
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     */
    private function endElement($namespaceURI, $sName)
    {
        // namespace definition ends, switch back to default namespace
        if (self::TAG_NAMESPACE  == $sName) {
            $this->currentNamespace = self::DEFAULT_NAMESPACE;
            return;
        }

        // use definition handler to finalize the definition of the current tag
        $defHandler = array_pop($this->defHandlerStack);
        $defHandler->endElement($namespaceURI, $sName);
    }
}
?><?php
/**
 * Definition of an abstract XML tag.
 *
 * @author  Frank Kleine <mikey@xjconf.net>
 */
XJConfLoader::load('definitions::TagDefinition',
                   'exceptions::ValueConversionException'
);
/**
 * Definition of an abstract XML tag.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class AbstractTagDefinition extends TagDefinition
{
	/**
     * abstract type of the tag
     *
     * @var  string
     */
	private $abstractType          = null;
	/**
     * attribute that contains the concrete type name
     *
     * @var  string
     */
	private $concreteTypeAttribute = null;

    /**
     * Create a new tag definition
     *
     * @param   string  $name                   name of the tag
     * @param   string  $abstractType           type of the tag
     * @param   string  $concreteTypeAttribute  attribute name containing the concrete type
     * @throws  XJConfException
     */
	public function __construct($name, $abstractType, $concreteTypeAttribute)
	{
		if (null == $name || strlen($name) == 0) {
			throw new InvalidTagDefinitionException('AbstractTagDefinition needs a name.');
		}
		if (null == $abstractType || strlen($abstractType) == 0) {
			throw new InvalidTagDefinitionException('AbstractTagDefinition needs an abstract type.');
		}
		if (null == $concreteTypeAttribute || strlen($concreteTypeAttribute) == 0) {
			throw new InvalidTagDefinitionException('AbstractTagDefinition needs a concrete type attribute name.');
		}

		$this->name                  = $name;
		$this->tagName               = $name;
		$this->abstractType          = $abstractType;
		$this->concreteTypeAttribute = $concreteTypeAttribute;
	}
	
	/**
	 * returns the name of the attribute which contains the concrete type
	 *
	 * @return  string
	 */
	public function getConcreteTypeAttributeName()
	{
	    return $this->concreteTypeAttribute;
	}

	/**
	 * get the type of the tag
	 *
	 * @return  string
	 */
	public function getType()
	{
	    if (null != $this->type) {
    	    if (null != $this->classLoader && in_array($this->type, $this->simpleTypes) == false) {
    	        return $this->classLoader->getType($this->type);
    	    }
    	    
            return $this->type;
	    }
	    
	    if (null != $this->classLoader && in_array($this->abstractType, $this->simpleTypes) == false) {
	        return $this->classLoader->getType($this->abstractType);
	    }
	    
	    return $this->abstractType;
	}

	/**
	 * Convert the value of the tag.
	 *
	 * @param   Tag    $tag  tag that will be converted
	 * @return  mixed  converted value
	 * @throws  ValueConversionException
	 */
	public function convertValue(Tag $tag)
    {
        $instance = parent::convertValue($tag);
        if (is_subclass_of($instance, $this->abstractType) == true) {
            return $instance;
        }
        
        $refClass = new ReflectionClass(get_class($instance));
        if (null != $this->classLoader && in_array($this->abstractType, $this->simpleTypes) == false) {
            $abstractType = $this->classLoader->getType($this->abstractType);
        } else {
            $abstractType = $this->abstractType;
        }
        if (in_array($abstractType, array_keys($refClass->getInterfaces())) == true) {
            return $instance;
        }
        
        throw new ValueConversionException('Created instance is not an instance of ' . $this->abstractType);
	}
}
?><?php
/**
 * Definition container of an attribute.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactoryChain',
                   'definitions::Definition',
                   'exceptions::InvalidTagDefinitionException',
                   'exceptions::MissingAttributeException',
                   'exceptions::XJConfException',
                   'GenericTag'
);
/**
 * Definition container of an attribute.
 *
 * This class is used to store information on how
 * an attribute of a specific tag should be handled.
 *
 * Options include
 * - Type of the Attribute
 * - Default value for non-existent attributes
 * - Setter method to set the attribute
 * - Whether the attribute is required, or not
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class AttributeDefinition implements Definition
{
    /**
     * name of the attribute
     *
     * @var  string
     */
	private $name         = null;
    /**
     * Type of the attribute
     *
     * @var  string
     */
	private $type         = null;
    /**
     * Name of the setter method
     *
     * @var  string
     */
	private $setter       = null;
    /**
     * Default value
     *
     * @var  string
     */
    private $defaultValue = null;
    /**
     * Whether the attribute is required
     *
     * @var  boolean
     */
    private $required     = false;
    /**
     * Converter used to convert the attribute
     *
     * @param  ValueConverter
     */
    private $valueConverter;

	/**
	 * create a new attribute definition for a String attribute
	 *
	 * @param   string  $name  name of the attribute
	 * @param   string  $type  optional  type of the tag
     * @throws  XJConfException
	 */
	public function __construct($name, $type = null)
	{
		if (null == $name || strlen($name) == 0) {
			throw new InvalidTagDefinitionException('AttributeDefinition needs a name.');
		}

		$this->name = $name;
		if (null == $type || strlen($type) == 0) {
            $this->type = 'string';
		} else {
		    $this->type = $type;
		}
	}

	/**
	 * Get the name of the attribute.
	 *
	 * @return  string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the type of the attribute
	 *
	 * @return  string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Convert a value to the defined type
	 *
	 * The value you pass in will be cast to a
	 * String before it is converted to the defined
	 * type.
     *
     * The type of the returned value can be specified in
     * the constructor using the type argument.
	 *
	 * @param   Tag    $tag
	 * @return  mixed  concerted value
	 * @throws  ValueConversionException
	 * @throws  MissingAttributeException
	 */
	public function convertValue(Tag $tag)
    {
	    if ($tag->hasAttribute($this->getName())) {
            $value = $tag->getAttribute($this->getName());
        } else {
            $value = $this->getDefault();
        }



        if (null === $value) {
            if ($this->isRequired() == true) {
                throw new MissingAttributeException('The attribute "' . $this->getName() . '" is required for the tag "' . $tag->getName() . '".');
            }

            // it's useless to create an instance passing null to the constructor.
            return null;
        }

        $tag = new GenericTag($this->getName(), array());
        $tag->addData($value);

		$instance = $this->getValueConverter()->convertValue($tag, $this);
		return $instance;
	}

	/**
     * Get the type of the attribute
     *
     * @param   Tag     $tag
     * @return  string
     */
    public function getValueType(Tag $tag)
    {
        return $this->valueConverter->getType();
    }

    /**
	 * Set the setter method
     *
     * If no setter method is specified, the standard
     * name "setAttributename()" will be used instead.
	 *
	 * @param  string  $setter  name of the setter method
	 * @see    getSetterMethod()
	 */
	public function setSetterMethod($setter)
	{
		$this->setter = $setter;
	}

	/**
	 * Get the name of the setter method that should be used
     * to set the attribute value in the parent container
	 *
	 * @return  string
     * @see     setSetterMethod()
	 */
	public function getSetterMethod(Tag $tag)
	{
		if (null == $this->setter) {
			return 'set' . ucfirst($this->getName());
		}

		return $this->setter;
	}

    /**
     * Add a child definition
     *
     * Attributes can not have any children.
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        // attributes can not have any children.
    }

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        return null;
    }

    /**
     * Return all child definitions.
     *
     * Currently, it is not possible to add any child
     * definitions to an attribute
     *
     * @return  array
     */
    public function getChildDefinitions() {
        return array();
    }

    /**
     * Set the default value for the attribute.
     *
     * @param  string  $defaultValue  default value that will be used, if a tag does not provide the attribute
     * @see    getDefault()
     */
    public function setDefault($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Get the default value of the attribute.
     *
     * @return  string
     * @see     setDefault()
     */
    public function getDefault()
    {
        return $this->defaultValue;
    }

    /**
     * returns whether the attribute is required or not
     *
     * @return  boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * set if attribute is required
     *
     * @param  boolean  $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Get the value converter for this tag
     *
     * @return  ValueConverter
     */
    protected function getValueConverter()
    {
        if (null == $this->valueConverter) {
            $this->valueConverter = ValueConverterFactoryChain::getFactory($this)->createValueConverter($this);
        }

        return $this->valueConverter;
    }

    /**
     * This definition does not support named child definitions
     *
     * @param string $name
     * @return Definition
     */
    public function getChildDefinitionByTagName($name) {
        return null;
    }
}
?><?php
/**
 * Definition for the character data inside a tag.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactoryChain',
                   'definitions::Definition'
);
/**
 * Definition for the character data inside a tag.
 *
 * This is used to pass the character data to the constructor
 * of the tag, while casting it to the desired class.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class CDataDefinition implements Definition
{
    /**
     * type of the character data
     *
     * @var  string
     */
	private $type           = null;
    /**
     * name of the setter
     *
     * @var  string
     */
	private $setter         = 'setData';
    /**
     * Converter used to convert the character data
     *
     * @var  ValueConverter
     */
    private $valueConverter;

    /**
     * Create a new CDataDefinition for any other type
     *
     * @param  string  $type  optional  type of the content
     */
    public function __construct($type = null)
    {
        if (null == $type) {
            $type = 'string';
        }

        $this->type = $type;
    }

    /**
     * get the name under which the data will be stored
     *
     * @return  string
     */
    public function getName()
    {
        return 'data';
    }

    /**
	 * get the type of the data
	 *
	 * @return  string
	 */
	public function getType()
	{
		return $this->type;
	}

    /**
     * Convert the character data to any type
     *
     * @param   Tag    $tag
	 * @return  mixed  concerted value
     */
    public function convertValue(Tag $tag)
    {
        $instance = $this->getValueConverter()->convertValue($tag, $this);
		return $instance;
    }

    /**
     * Get the type of the cdata
     *
     * @param   Tag     $tag
     * @return  string
     */
    public function getValueType(Tag $tag) {
        return $this->getValueConverter()->getType();
    }

    /**
     * Set the setter method
     *
     * If no setter method is specified, the standard
     * name "setAttributename()" will be used instead.
     *
     * @param  string  $setter  name of the setter method
     * @see    getSetterMethod()
     */
    public function setSetterMethod($setter)
	{
		$this->setter = $setter;
	}

	/**
     * Get the setter method, which is setData() by default
     *
     * @return  string
     * @see     setSetterMethod()
     */
    public function getSetterMethod(Tag $tag)
    {
        return $this->setter;
    }

	/**
     * Character data cannot have any child definitions
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        // Character data can not have any children.
    }

	/**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        return null;
    }

    /**
     * Return all child definitions.
     *
     * Currently it is not possible to add any child
     * definitions to cdata
     *
     * @return  array
     */
    public function getChildDefinitions()
    {
        return array();
    }

    /**
     * Get the value converter for this character data
     *
     * @return  ValueConverter
     */
    protected function getValueConverter()
    {
        if (null == $this->valueConverter) {
            $this->valueConverter = ValueConverterFactoryChain::getFactory($this)->createValueConverter($this);
        }

        return $this->valueConverter;
    }

    /**
     * This definition does not support named child definitions
     *
     * @param string $name
     * @return Definition
     */
    public function getChildDefinitionByTagName($name) {
        return null;
    }
}
?><?php
XJConfLoader::load('definitions::Definition',
                   'exceptions::ValueConversionException',
                   'exceptions::XJConfException'
);
/**
 * Definition to access the child of the tag.
 *
 * This can be used to pass the child to the constructor.
 *
 * @author Stephan Schmidt <stephan.schmidt@schlund.de>
 */
class ChildDefinition implements Definition
{
    /**
     * Name of the child to access
     *
     * @var  string
     */
	private $name = null;

    /**
     * Create a new child definition
     *
     * @param   string  $name  name of child
     * @throws  XJConfException
     */
    public function __construct($name)
    {
        if (null == $name || strlen($name) == 0) {
			throw new XJConfException('ChildDefinition needs a name.');
		}

		$this->name = $name;
    }

    /**
	 * Get the name of the child.
	 *
	 * @return  string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * get the type of the child
	 *
	 * @return  string
	 */
	public function getType()
	{
		return null;
	}

    /**
     * Convert the value
     *
     * @param   Tag    $tag
	 * @return  mixed  concerted value
	 * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag)
    {
        $child = $tag->getChild($this->getName());
        if (null == $child) {
            throw new ValueConversionException('Child element "' . $this->getName() . '" does not exist.');
        }

        return $child->getConvertedValue();
    }

    /**
     * Get the type of the child
     *
     * @param   Tag     $tag
     * @return  string
     * @throws  ValueConversionException
     */
    public function getValueType(Tag $tag)
    {
        $child = $tag->getChild($this->getName());
        if (null == $child) {
            throw new ValueConversionException('Child element "' . $this->getName() . '" does not exist.');
        }

        return $child->getValueType($tag);
    }

    /**
     * This does not provide a setter method.
     *
     * @return  null
     */
    public function getSetterMethod(Tag $tag)
    {
        return null;
    }

    /**
     * It's not possible to add a new child.
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        // Character data can not have any children.
    }

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        return null;
    }

    /**
     * Return all child definitions.
     *
     * Currently, it is not possible to add any child
     * definitions to a child
     *
     * @return  array
     */
    public function getChildDefinitions() {
        return array();
    }

    /**
     * This definition does not support named child definitions
     *
     * @param string $name
     * @return Definition
     */
    public function getChildDefinitionByTagName($name) {
        return null;
    }
}
?><?php
/**
 * Definition of an XML tag.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('definitions::TagDefinition');
/**
 * Definition of an XML tag.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class ConcreteTagDefinition extends TagDefinition
{
    /**
     * Create a new tag definition
     *
     * @param   string  $name  name of the tag
     * @param   string  $type  type of the tag
     * @throws  InvalidTagDefinitionException
     */
	public function __construct($name, $type)
	{
		if (null == $name || strlen($name) == 0) {
			throw new InvalidTagDefinitionException('TagDefinition needs a name.');
		}
		if (null == $type || strlen($type) == 0) {
			throw new InvalidTagDefinitionException('TagDefinition needs a type.');
		}

		$this->name    = $name;
		$this->tagName = $name;
		$this->setType($type);
	}
}
?><?php
/**
 * Definition for the constructor of a class
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('definitions::Definition');
/**
 * Definition for the constructor of a class
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class ConstructorDefinition implements Definition
{
    /**
     * Parameters of the constructor
     *
     * @var  array<Definition>
     */
    private $params = array();

    /**
    * Get the name under which it will be stored
    *
    * @return  string
    */
    public function getName()
    {
        return '__constructor';
    }

    /**
	 * get the type of the constructor
	 *
	 * @return  string
	 */
	public function getType()
	{
		return null;
	}

	/**
     * Convert the constructor.
     *
     * This does not do anything!
     *
     * @param  Tag  $tag
     * @return  null
     */
    public function convertValue(Tag $tag)
    {
        return null;
    }

    /**
     * Get the type of the constructor
     *
     * @param   Tag  $tag
     * @return  null
     */
    public function getValueType(Tag $tag)
    {
        return null;
    }

    /**
     * Get the setter method
     *
     * @return  null
     */
    public function getSetterMethod(Tag $tag)
    {
        return null;
    }

    /**
     * Add a new child definition (equals a parameter of the constructor)
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        array_push($this->params, $def);
    }

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        foreach ($this->params as $param) {
            if (get_class($param) == $def) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        foreach ($this->params as $param) {
            if (get_class($param) == $def) {
                return $param;
            }
        }

        return null;
    }

    /**
     * Return all child definitions.
     *
     * @return  array
     */
    public function getChildDefinitions()
    {
        return $this->params;
    }

    /**
     * Get the names of all child elements that are used in
     * the constructor.
     *
     * These children are not used, when adding them using
     * setter-methods.
     *
     * @return  array
     */
    public function getUsedChildrenNames()
    {
        $childrenNames = array();
        foreach ($this->params as $param) {
            if ($param instanceof ChildDefinition) {
                array_push($childrenNames, $param->getName());
            }
        }

        return $childrenNames;
    }

    /**
     * Get the parameters of the constructor
     *
     * @return  array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * This definition does not support named child definitions
     *
     * @param string $name
     * @return Definition
     */
    public function getChildDefinitionByTagName($name) {
        return null;
    }
}
?><?php
/**
 * Interface for tag and attribute definitions.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
/**
 * Interface for tag and attribute definitions.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
interface Definition
{
    /**
     * Get the name under which the information
     * will be stored.
     *
     * @return  string  name of the value
     */
	public function getName();

	/**
     * Get the type of the definition
     *
     * @return  string
     */
	public function getType();

    /**
     * Get the converted value.
     *
     * XJConf will pass the complete tag to this method
     *
     * @param   Tag  $tag  value
     * @return  mixed
     */
    public function convertValue(Tag $tag);

    /**
     * Get the type of the converted value
     * @param   Tag  $tag  value
     * @return  string
     */
    public function getValueType(Tag $tag);

    /**
     * Get the name of the setter method
     *
     * @return  string
     */
    public function getSetterMethod(Tag $tag);

    /**
     * Add a child definition of any type
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def);

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def);

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def);

    /**
	 * Get all child definitions of the definition
	 *
	 * @return  array
	 */
	public function getChildDefinitions();

	public function getChildDefinitionByTagName($name);
}
?><?php
/**
 * FactoryMethodDefinition
 *
 * @author  Stephan Schmidt <me@schst.net>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('definitions::Definition',
                   'exceptions::UnsupportedOperationException'
);
/**
 * FactoryMethodDefinition
 *
 * Stores information about a factory method that
 * is used to create an instance.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class FactoryMethodDefinition implements Definition
{
    /**
     * Parameters of the factory method
     *
     * @var  array
     */
    private $params = array();
	/**
	 * name of factory method
	 *
	 * @var  string
	 */
	private $name   = '';

	/**
	 * construcor
	 *
	 * @param  string  $name  name of factory method
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
     * Get the name under which it will be stored
     *
     * @return  string
     */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * get the type of the factory method
	 *
	 * @return  string
	 */
	public function getType()
	{
		return null;
	}

	/**
     * Convert the factory method.
     *
     * @param   Tag  $tag
     * @throws  UnsupportedOperationException
     */
	public function convertValue(Tag $tag)
	{
		throw new UnsupportedOperationException();
	}

	/**
     * Get the type of the factory method.
     *
     * @param   Tag  $tag
     * @throws  UnsupportedOperationException
     */
	public function getValueType(Tag $tag)
	{
		throw new UnsupportedOperationException();
	}

	/**
     * Get the setter method
     *
     * @throws  UnsupportedOperationException
     */
	public function getSetterMethod(Tag $tag)
	{
		throw new UnsupportedOperationException();
	}

    /**
     * Add a new child definition (equals a parameter of the factory method)
     *
     *  @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        array_push($this->params, $def);
    }

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        foreach ($this->params as $param) {
            if (get_class($param) == $def) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        foreach ($this->params as $param) {
            if (get_class($param) == $def) {
                return $param;
            }
        }

        return null;
    }

    /**
     * Return all child definitions.
     *
     * @return  array
     */
    public function getChildDefinitions()
    {
        return $this->params;
    }

    /**
     * Get the names of all child elements that are used in
     * the constructor.
     *
     * These children are not used, when adding them using
     * setter-methods.
     *
     * @return  array
     */
    public function getUsedChildrenNames()
    {
        $childrenNames = array();
        foreach ($this->params as $param) {
            if ($param instanceof ChildDefinition) {
                array_push($childrenNames, $param->getName());
            }
        }

        return $childrenNames;
    }

    /**
     * Get the parameters of the factory method
     *
     * @return  array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * This definition does not support named child definitions
     *
     * @param string $name
     * @return Definition
     */
    public function getChildDefinitionByTagName($name) {
        return null;
    }
}
?><?php
/**
 * Creates an AbstractTagDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::AbstractTagDefinition',
                   'definitions::NamespaceDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates an AbstractTagDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class AbstractTagDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;

    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }

    /**
     * Start Element handler
     *
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  InvalidTagDefinitionException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        // ensure that the name has been set
        if (isset($atts['name']) == false) {
            throw new InvalidTagDefinitionException('The <abstractTag> tag is missing the name attribute.');
        }

        if (isset($atts['abstractType']) == false) {
            throw new InvalidTagDefinitionException('The <abstractTag> tag is missing the abstractType attribute.');
        }

        if (isset($atts['concreteTypeAttribute']) == false) {
            throw new InvalidTagDefinitionException('The <abstractTag> tag is missing the concreteTypeAttribute attribute.');
        }

        // The definition extends another definition
        if (isset($atts['extends']) != false) {
            $nsDef = $this->defParser->getNamespaceDefinitions()->getNamespaceDefinition($this->defParser->getCurrentNamespace());
            $extendedDef = $nsDef->getDefinition($atts['extends']);
            if ($extendedDef instanceof AbstractTagDefinition) {
                $def = clone $extendedDef;
                $def->setName($atts['name']);
                $def->setTagName($atts['name']);
            } else {
                $def = new AbstractTagDefinition($atts['name'], $atts['abstractType'], $atts['concreteTypeAttribute']);
                $def->extend($extendedDef);
            }
        } else {
            $def = new AbstractTagDefinition($atts['name'], $atts['abstractType'], $atts['concreteTypeAttribute']);
        }

        // key attribute
        if (isset($atts['keyAttribute']) == true) {
            $def->setKeyAttribute($atts['keyAttribute']);
        } elseif (isset($atts['key']) == true) {
            $def->setName($atts['key']);
        }

        // setter
        if (isset($atts['setter']) == true) {
            $def->setSetterMethod($atts['setter']);
        }

        // static
        if (isset($atts['static']) == true && strtolower($atts['static']) == 'true') {
            $def->setStatic(true);
        }

        // give definition the correct class loader
        if ($this->defParser->hasClassLoader($this->defParser->getCurrentNamespace()) == true) {
            $def->setClassLoader($this->defParser->getClassLoader($this->defParser->getCurrentNamespace()));
        }

        return $def;
    }

    /**
     * End Element handler
     *
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack =& $this->defParser->getDefStack();
        $def      = array_pop($defStack);

        $nsDefs = $this->defParser->getNamespaceDefinitions();

        if ($nsDefs->isNamespaceDefined($this->defParser->getCurrentNamespace()) == false) {
            $nsDefs->addNamespaceDefinition($this->defParser->getCurrentNamespace(), new NamespaceDefinition($this->defParser->getCurrentNamespace()));
        }

        $nsDef = $nsDefs->getNamespaceDefinition($this->defParser->getCurrentNamespace());
        $nsDef->addTagDefinition($def);
    }
}
?><?php
/**
 * Creates a AttributeDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::AttributeDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a AttributeDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class AttributeDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;

    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }

    /**
     * Start Element handler
     *
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        if (isset($atts['type']) == false) {
            $atts['type'] = $atts['primitive'];
        }

        // ensure that the name has been set
        if (isset($atts['name']) == false) {
        	throw new InvalidTagDefinitionException('The <attribute> tag is missing the name attribute.');
        }

        $attDef = new AttributeDefinition($atts['name'], $atts['type']);

        // setter
        if (isset($atts['setter']) == true) {
            $attDef->setSetterMethod($atts['setter']);
        }

        // default value
        if (isset($atts['default']) == true) {
            $attDef->setDefault($atts['default']);
        }

        // required
        if (isset($atts['required']) == true && 'true' == $atts['required']) {
            $attDef->setRequired(true);
        }

        // get the current tag
        $defStack =& $this->defParser->getDefStack();
        $def      = array_pop($defStack);
        try {
            $def->addChildDefinition($attDef);
        } catch (Exception $e) {
            throw new InvalidTagDefinitionException('Could not register attribute:' .  $e->getMessage());
        }
        return $def;
    }

    /**
     * End Element handler
     *
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        // nothing to do here
    }
}
?><?php
/**
 * Creates a CDataDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::CDataDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a CDataDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class CDataDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;
    
    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }
    
    /**
     * Start Element handler
     * 
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        if (isset($atts['type']) == false) {
            $def = new CDataDefinition();
        } else {
            $def = new CDataDefinition($atts['type']);
        }
        
        // setter
        if (isset($atts['setter']) == true) {
            $def->setSetterMethod($atts['setter']);
        }
        
        return $def;
    }
    
    /**
     * End Element handler
     * 
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack =& $this->defParser->getDefStack();
        $cdataDef  = array_pop($defStack);
        $parentDef = end($defStack);
        try {
            $parentDef->addChildDefinition($cdataDef);
        } catch (Exception $e) {
            throw new InvalidTagDefinitionException('Could not register CData handling: ' . $e->getMessage());
        }
    }
}
?><?php
/**
 * Creates a ChildDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::ChildDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a ChildDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class ChildDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;
    
    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }
    
    /**
     * Start Element handler
     * 
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        // ensure that the name has been set
        if (isset($atts['name']) == false) {
        	throw new InvalidTagDefinitionException('The <child> tag is missing the name attribute.');
        }
        
        $def = new ChildDefinition($atts['name']);
        return $def;
    }
    
    /**
     * End Element handler
     * 
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack =& $this->defParser->getDefStack();
        $cdataDef  = array_pop($defStack);
        $parentDef = end($defStack);
        try {
            $parentDef->addChildDefinition($cdataDef);
        } catch (Exception $e) {
            throw new InvalidTagDefinitionException('Could not handle child definition: ' . $e->getMessage());
        }
    }
}
?><?php
/**
 * Creates a ConcreteTagDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::ConcreteTagDefinition',
                   'definitions::NamespaceDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a ConcreteTagDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class ConcreteTagDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;

    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }

    /**
     * Start Element handler
     *
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  InvalidTagDefinitionException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        // ensure that the name has been set
        if (isset($atts['name']) == false) {
            throw new InvalidTagDefinitionException('The <tag> tag is missing the name attribute.');
        }

        //
        if (isset($atts['type']) == false) {
            $atts['type'] = $atts['primitive'];
        }

        // The definition extends another definition
        if (isset($atts['extends']) != false) {
            $nsDef = $this->defParser->getNamespaceDefinitions()->getNamespaceDefinition($this->defParser->getCurrentNamespace());
            $extendedDef = $nsDef->getDefinition($atts['extends']);
            if ($extendedDef instanceof ConcreteTagDefinition) {
                $def = clone $extendedDef;
                $def->setName($atts['name']);
                $def->setTagName($atts['name']);
                if (null == $atts['type']) {
                    $def->setType($atts['type']);
                }
            } else {
                $def = new ConcreteTagDefinition($atts['name'], $atts['type']);
                $def->extend($extendedDef);
            }
        } else {
            // Create a definition from scratch
            if (null == $atts['type']) {
                $atts['type'] = 'string';
            }

            $def = new ConcreteTagDefinition($atts['name'], $atts['type']);
        }

        // key attribute
        if (isset($atts['keyAttribute']) == true) {
            $def->setKeyAttribute($atts['keyAttribute']);
        } elseif (isset($atts['key']) == true) {
            $def->setName($atts['key']);
        }

        // setter
        if (isset($atts['setter']) == true) {
            $def->setSetterMethod($atts['setter']);
        }

        // static
        if (isset($atts['static']) == true && strtolower($atts['static']) == 'true') {
            $def->setStatic(true);
        }

        // give definition the correct class loader
        if ($this->defParser->hasClassLoader($this->defParser->getCurrentNamespace()) == true) {
            $def->setClassLoader($this->defParser->getClassLoader($this->defParser->getCurrentNamespace()));
        }

        return $def;
    }

    /**
     * End Element handler
     *
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack =& $this->defParser->getDefStack();
        $def      = array_pop($defStack);

        $nsDefs = $this->defParser->getNamespaceDefinitions();

        if ($nsDefs->isNamespaceDefined($this->defParser->getCurrentNamespace()) == false) {
            $nsDefs->addNamespaceDefinition($this->defParser->getCurrentNamespace(), new NamespaceDefinition($this->defParser->getCurrentNamespace()));
        }

        $nsDef = $nsDefs->getNamespaceDefinition($this->defParser->getCurrentNamespace());
        $nsDef->addTagDefinition($def);
    }
}
?><?php
/**
 * Creates a ConstructorDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::ConstructorDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a ConstructorDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class ConstructorDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;
    
    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }
    
    /**
     * Start Element handler
     * 
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        $def = new ConstructorDefinition();
        return $def;
    }
    
    /**
     * End Element handler
     * 
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  InvalidTagDefinitionException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack       =& $this->defParser->getDefStack();
        $constructorDef = array_pop($defStack);
        $tagDef         = end($defStack);
        try {
            $tagDef->addChildDefinition($constructorDef);
        } catch (Exception $e) {
            throw new InvalidTagDefinitionException('Could not register the constructor: ' . $e->getMessage());
        }
    }
}
?><?php
/**
 * Interface to handle definition tags.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
/**
 * Interface to handle definition tags.
 *
 * A DefinitionHandler can handle xml elements and create definitions out of
 * them using the appropriate Definition class.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
interface DefinitionHandler
{
    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser);
    
    /**
     * Start Element handler
     * 
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts);
    
    /**
     * End Element handler
     * 
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName);
}
?><?php
/**
 * Factory to create a definition handler of a given type.
 *
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
/**
 * Factory to create a definition handler of a given type.
 *
 * If the given type maps to an unknown definition handler it will create
 * an EmptyDefinitionHandler instead.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class DefinitionHandlerFactory
{
    /**
     * create a DefinitionHandler
     *
     * @param   string            $type       type of DefinitionHandler to create
     * @param   DefinitionParser  $defParser
     * @return  InvalidTagDefinitionException
     */
    public static function create($type, DefinitionParser $defParser)
    {
        $className = ucfirst($type) . 'DefinitionHandler';
        if (XJConfLoader::classFileExists('definitions::handler::' . $className) == false) {
            $className = 'EmptyDefinitionHandler';
        }
        
        XJConfLoader::load('definitions::handler::' . $className);
        
        $defHandler = new $className();
        $defHandler->init($defParser);
        return $defHandler;
    }
}
?><?php
/**
 * DefinitionHandler for xml elements that do not define anything.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler');
/**
 * DefinitionHandler for xml elements that do not define anything.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class EmptyDefinitionHandler implements DefinitionHandler
{
    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        // nothing to do
    }
    
    /**
     * Start Element handler
     * 
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        return null;
    }
    
    /**
     * End Element handler
     * 
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        // nothing to do
    }
}
?><?php
/**
 * Creates a FactoryMethodDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::FactoryMethodDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a FactoryMethodDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class FactoryMethodDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;
    
    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }
    
    /**
     * Start Element handler
     * 
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        // ensure that the name has been set
        if (isset($atts['name']) == false) {
        	throw new InvalidTagDefinitionException('The <factoryMethod> tag is missing the name attribute.');
        }
        	
        // TODO: check, whether name has been specified
        $def = new FactoryMethodDefinition($atts['name']);
        return $def;
    }
    
    /**
     * End Element handler
     * 
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack   =& $this->defParser->getDefStack();
        $factoryDef = array_pop($defStack);
        $tagDef     = end($defStack);
        try {
            $tagDef->addChildDefinition($factoryDef);
        } catch (Exception $e) {
            throw new InvalidTagDefinitionException('Could not register the factory method: ' . $e->getMessage());
        }
    }
}
?><?php
/**
 * Creates a FactoryMethodDefinition from given xml data.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::DefinitionHandler',
                   'definitions::FactoryMethodDefinition',
                   'exceptions::InvalidTagDefinitionException'
);
/**
 * Creates a FactoryMethodDefinition from given xml data.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class MethodCallTagDefinitionHandler implements DefinitionHandler
{
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    private $defParser;

    /**
     * init the handler
     *
     * @param  DefinitionParser  $defParser
     */
    public function init(DefinitionParser $defParser)
    {
        $this->defParser = $defParser;
    }

    /**
     * Start Element handler
     *
     * @param   string      $namespaceURI  namespace of start tag
     * @param   string      $sName         name of start tag
     * @param   array       $atts          attributes of tag
     * @return  Definition
     * @throws  XJConfException
     */
    public function startElement($namespaceURI, $sName, $atts)
    {
        // ensure that the name has been set
        if (isset($atts['name']) == false) {
        	throw new InvalidTagDefinitionException('The <method> tag is missing the name attribute.');
        }
        if (isset($atts['method']) == false) {
        	throw new InvalidTagDefinitionException('The <method> tag is missing the method attribute.');
        }
        $def = new MethodCallTagDefinition($atts['name'], $atts['method']);
        return $def;
    }

    /**
     * End Element handler
     *
     * @param   string  $namespaceURI  namespace of end tag
     * @param   string  $sName         name of end tag
     * @throws  XJConfException
     */
    public function endElement($namespaceURI, $sName)
    {
        $defStack   =& $this->defParser->getDefStack();
        $methodDef = array_pop($defStack);
        $tagDef     = end($defStack);
        try {
            $tagDef->addChildDefinition($methodDef);
        } catch (Exception $e) {
            throw new InvalidTagDefinitionException('Could not register the method: ' . $e->getMessage());
        }
    }
}
?><?php
/**
 * Creates a ConcreteTagDefinition from given xml data.
 * 
 * Used for backward compatibility and usage of <tag> in definition files 
 * instead of <concreteTag>.
 *
 * @author  Frank Kleine <frank@kl-s.com>
 */
XJConfLoader::load('definitions::handler::ConcreteTagDefinitionHandler');
/**
 * Creates a ConcreteTagDefinition from given xml data.
 * 
 * Used for backward compatibility and usage of <tag> in definition files 
 * instead of <concreteTag>.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
class TagDefinitionHandler extends ConcreteTagDefinitionHandler
{
    
}
?><?php
XJConfLoader::load('definitions::Definition',
                   'definitions::ValueModifier',
                   'exceptions::ValueConversionException',
                   'exceptions::XJConfException'
);
/**
 * Definition to access the child of the tag.
 *
 * This can be used to pass the child to the constructor.
 *
 * @author Stephan Schmidt <stephan.schmidt@schlund.de>
 */
class MethodCallTagDefinition implements Definition, ValueModifier {

    /**
     * Name of the method to call
     *
     * @var  string
     */
	private $name = null;

    /**
     * Parameters of the method call
     *
     * @var array
     */
	private $params = array();

    /**
     * Create a new child definition
     *
     * @param   string  $name  name of child
     * @throws  XJConfException
     */
    public function __construct($name, $method)
    {
        if (null == $name || strlen($name) == 0) {
			throw new XJConfException('MethodDefinition needs a name.');
		}
		$this->name = $name;
		$this->method = $method;
    }

    /**
	 * Get the name of the child.
	 *
	 * @return  string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * get the type of the child
	 *
	 * @return  string
	 */
	public function getType()
	{
		return null;
	}

    /**
     * Convert the value
     *
     * @param   Tag    $tag
	 * @return  mixed  concerted value
	 * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * Get the type of the child
     *
     * @param   Tag     $tag
     * @return  string
     * @throws  ValueConversionException
     */
    public function getValueType(Tag $tag)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * This does not provide a setter method.
     *
     * @return  null
     */
    public function getSetterMethod(Tag $tag)
    {
        return null;
    }

    /**
     * It's not possible to add a new child.
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        $this->params[] = $def;
    }

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        return null;
    }

    /**
     * Return all child definitions.
     *
     * Currently, it is not possible to add any child
     * definitions to a child
     *
     * @return  array
     */
    public function getChildDefinitions() {
        return $this->params;
    }


    /**
     * Call the method on the parent object
     *
     * @param mixed $value
     * @param Tag $tag
     * @return mixed the modified value
     */
    public function modifyValue($value, Tag $tag) {
        if (!is_object($value)) {
            throw new XJConfException('Methods can only be called on objects.');
        }
        $clazz = new ReflectionClass(get_class($value));
        $method = $clazz->getMethod($this->method);

        $values = array();
        foreach ($this->params as $paramDefinition) {
            $values[] = $paramDefinition->convertValue($tag);
        }
        $method->invokeArgs($value, $values);
    }

    /**
     * This definition does not support named child definitions
     *
     * @param string $name
     * @return Definition
     */
    public function getChildDefinitionByTagName($name) {
        return null;
    }
}
?><?php
/**
 * Container for all tag definitions in one namespace
 * 
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <mikey@xjconf.net>
 */
XJConfLoader::load('definitions::TagDefinition');
/**
 * Container for all tag definitions in one namespace
 * 
 * @package     XJConf
 * @subpackage  definitions
 */
class NamespaceDefinition
{
    /**
     * list of tag definitions
     * 
     * @var  array<String,TagDefinition>
     */
    private $tagDefinitions = array();
    /**
     * URI of this namespace
     * 
     * @var  string
     */
    private $namespaceURI   = null;

    /**    
     * Constructor for a namespace definition
     * 
     * @param  string  namespaceURI  URI of the new namespace
     */
    public function __construct($namespaceURI)
    {
        $this->namespaceURI = $namespaceURI;
    }

    /**
     * Add a new tag definition
     * 
     * @param  TagDefinition  $tagDefinition
     */
    public function addTagDefinition(TagDefinition $tagDefinition)
    {
        $this->tagDefinitions[$tagDefinition->getTagName()] = $tagDefinition;
    }

    /**
     * Count the number of defined tags
     * 
     * @return  int  number of defined tags
     */
    public function countTagDefinitions()
    {
        return count($this->tagDefinitions);
    }

    /**
     * Check, whether a tag has been defined
     * 
     * @param   string   $tagName  name of the tag
     * @return  boolean  true, if the tag has been defined, false otherwise
     */
    public function isDefined($tagName)
    {
        return isset($this->tagDefinitions[$tagName]);
    }

    /**
     * Get the definition of a tag
     * 
     * @param   tagName name of the tag
     * @return  TagDefinition
     */
    public function getDefinition($tagName)
    {
        if ($this->isDefined($tagName) == true) {
            return $this->tagDefinitions[$tagName];
        }
        
        return null;
    }

    /**
     * Get the URI for this namespace
     * 
     * @return  string
     */
    public function getNamespaceURI()
    {
        return $this->namespaceURI;
    }
}
?><?php
XJConfLoader::load('definitions::NamespaceDefinition');
/**
 * Stores definitions of several namespaces.
 *  
 * @author Stephan Schmidt <stephan.schmidt@schlund.de>
 */
class NamespaceDefinitions
{
    /**
     * list of namespace definitions
     * 
     * @var  array<String,NamespaceDefinition>
     */
	private $namespaces = array();
	
    /**
     * Add the definition for a namespace
     * 
     * @param  string              $namespace  namespace URI  
     * @param  NamespaceDefinition $def        namespace definition object
     */
    public function addNamespaceDefinition($namespace, NamespaceDefinition $def)
    {
        $this->namespaces[$namespace] = $def;
    }

    /**
     * Get a namespace defintition.
     * 
     * @param   string              $namespace  namespace URI  
     * @return  NamespaceDefinition 
     */
    public function getNamespaceDefinition($namespace)
    {
        if ($this->isNamespaceDefined($namespace) == true) {
            return $this->namespaces[$namespace];
        }
        
        return null;
    }
    
    /**    
     * Check, whether a namespace has been defined
     * 
     * @param namespace      namespace URI
     * @return  boolean             true, if the namespace has been defined, false otherwise
     */
    public function isNamespaceDefined($namespace)
    {
        return isset($this->namespaces[$namespace]);
    }

    /**
     * Get the defined namespaces.
     * 
     * @return  array  list of all namespace URIs that have been defined
     */
     public function getDefinedNamespaces()
     {
         return $this->namespaces;
     }
    
    /**
     * Check, whether a tag has been defined
     * 
     * @param   string   $namespace  namespace URI
     * @param   string   $tagName    local tag name
     * @return  boolean  true, if the tag has been defined, false otherwise
     */
    public function isTagDefined($namespace, $tagName)
    {
        if ($this->isNamespaceDefined($namespace) == false) {
            return false;
        }
        
        return $this->getNamespaceDefinition($namespace)->isDefined($tagName);
    }
    
    /**
     * Get the definition of a single tag
     * 
     * @param   string         $namespace  namespace URI
     * @param   string         $tagName    local tag name
     * @return  TagDefinition
     */
    public function getTagDefinition($namespace, $tagName)
    {
        if ($this->isNamespaceDefined($namespace) == false) {
            return null;
        }
        
        return $this->getNamespaceDefinition($namespace)->getDefinition($tagName);
    }

   /**
    * Get the total amount of defined tags in all namespaces
    * 
    * @return  int  total amount of defined tags
    */
    public function countTagDefinitions()
    {
        $amount = 0;
        foreach ($this->namespaces as $namespace) {
            $amount += $namespace->countTagDefinitions();
        }
        
        return $amount;
    }
    
    /**
     * Append more namespace definitions to the current
     * definitions. Can be used if namespace definitions are read from
     * more than one file.
     * 
     * @param  NamespaceDefinitions  namespaceDefs
     */
    public function appendNamespaceDefinitions(NamespaceDefinitions $nsDefs)
    {
        foreach ($nsDefs->getDefinedNamespaces() as $namespace => $nsDef) {
             $this->addNamespaceDefinition($namespace, $nsDef);
        }
    }
}
?><?php
/**
 * Definition of an XML tag.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('converters::factories::ValueConverterFactoryChain',
                   'definitions::AttributeDefinition',
                   'definitions::CDataDefinition',
                   'definitions::ConstructorDefinition',
                   'definitions::FactoryMethodDefinition',
                   'definitions::Definition',
                   'exceptions::ValueConversionException',
                   'exceptions::XJConfException',
                   'XJConfClassLoader'
);
/**
 * Definition of an XML tag.
 *
 * @package     XJConf
 * @subpackage  definitions
 */
abstract class TagDefinition implements Definition
{
    /**
     * the name
     *
     * @var  string
     */
    protected $name           = null;
    /**
     * the name of the tag
     *
     * @var  string
     */
    protected $tagName        = null;
    /**
     * type of the tag
     *
     * @var  string
     */
    protected $type           = null;
    /**
     * list of attribute definitions
     *
     * @var  array<AttributeDefinition>
     */
    protected $atts           = array();
    /**
     * name of the setter
     *
     * @var  string
     */
    protected $setter         = null;
    /**
     * name of attribute that contains the name
     *
     * @var  string
     */
    protected $nameAttribute  = null;
    /**
     * definition of how to construct the object
     *
     * @var  ConstructorDefinition
     */
    protected $constructor    = null;
    /**
     * definition of factory that is able to construct the object
     *
     * @var  FactoryMethodDefinition
     */
    protected $factoryMethod  = null;
    /**
     * converts the value
     *
     * @var  ValueConverter
     */
    protected $valueConverter;

    /**
     * Methods to call on this tag
     *
     * @var array
     */
    protected $methods = array();

    protected $childDefs = array();

    /**
     * definition of tag content
     *
     * @var   CDataDefinition
     * @todo  Eventually call the setter method for the cdata
     */
    protected $cdata          = null;
    /**
     * the class loader to use
     *
     * @var  XJConfClassLoader
     */
    protected $classLoader    = null;
    /**
     * list of simple types where the class loader can not be applied to
     *
     * @var  array<string>
     */
    protected $simpleTypes    = array('boolean', 'bool', 'integer', 'int', 'double', 'float', 'string', 'array');
    /**
     * Whether the calls should be made statically or not
     *
     * @var  boolean
     */
    protected $static = false;

    /**
     * set the name of the value
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * get the name of the value
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the type of the tag
     *
     * @param  string  $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * get the type of the tag
     *
     * @return  string
     */
    public function getType()
    {
        if (null != $this->classLoader) {
            return $this->classLoader->getType($this->type);
        }

        return $this->type;
    }

    /**
     * Set whether calls should be made statically (true)
     * or an instance of the class should be created (false)
     *
     * @param boolean $static
     */
    public function setStatic($static) {
        $this->static = $static;
    }

    /**
     * get the type of the tag
     *
     * @return  string
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * Convert the value of the tag.
     *
     * @param   Tag    $tag  tag that will be converted
     * @return  mixed  converted value
     * @throws  ValueConversionException
     */
    public function convertValue(Tag $tag)
    {
        // get the data
        $data = $tag->getContent();
        if (null == $data) {
            $data = '';
        }

        // no constructor definition has been set,
        // create a new one
        if (null == $this->constructor && null == $this->factoryMethod) {
            $this->constructor = new ConstructorDefinition();
            $this->constructor->addChildDefinition(new CDataDefinition());
        }

        $instance = $this->getValueConverter()->convertValue($tag, $this);
        return $instance;
    }

    /**
     * Get the type of the tag
     *
     * @return  string
     */
    public function getValueType(Tag $tag)
    {
        return $this->getValueConverter()->getType();
    }

    /**
     * Set the setter method
     *
     * @param  string  $setter  name of the setter method
     */
    public function setSetterMethod($setter)
    {
        $this->setter = $setter;
    }

    /**
     * Get the name of the setter method that should be used
     *
     * @return  string
     */
    public function getSetterMethod(Tag $tag)
    {
        if (null != $this->setter) {
            return $this->setter;
        }

        // no name, the parent should be a collection
        if ('__none' == $this->name) {
            return null;
        }

        return 'set' . ucfirst($this->getKey($tag));
    }

    /**
     * Add a new child definition
     *
     * Possible definitions are:
     * - AttributeDefinition
     * - ConstructorDefinition
     * - FactoryMethodDefinition
     * - CDataDefinition
     *
     * @param  Definition  $def
     */
    public function addChildDefinition(Definition $def)
    {
        if ($def instanceof AttributeDefinition) {
            $this->addAttribute($def);
            return;
        }

        if ($def instanceof FactoryMethodDefinition) {
            if ($this->isStatic()) {
                throw new InvalidTagDefinitionException('Static classes may not have a factory method defined.');
            }
            $this->factoryMethod = $def;
            return;
        }

        if ($def instanceof ConstructorDefinition) {
            if ($this->isStatic()) {
                throw new InvalidTagDefinitionException('Static classes may not have a constructor defined.');
            }
            $this->constructor = $def;
            return;
        }

        if ($def instanceof CDataDefinition) {
            $this->cdata = $def;
            return;
        }
        $this->childDefs[] = $def;
    }

    /**
     * Checks whether this definition has a specific child condition
     *
     * @param   string   $def
     * @return  boolean  true if definition has a specific child condition, else false
     */
    public function hasChildDefinition($def)
    {
        $children = $this->getChildDefinitions();
        foreach ($children as $child) {
            if (get_class($child) == $def) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the first found definition of type $def
     *
     * @param   string   $def
     * @return  Definition
     */
    public function getChildDefinition($def)
    {
        $children = $this->getChildDefinitions();
        foreach ($children as $child) {
            if (get_class($child) == $def) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Return all child definitions.
     *
     * @return  array
     */
    public function getChildDefinitions()
    {
        $children = $this->atts;
        if ($this->factoryMethod instanceof Definition) {
            $children[] = $this->factoryMethod;
        }

        if ($this->constructor instanceof Definition) {
            $children[] = $this->constructor;
        }

        if ($this->cdata instanceof Definition) {
            $children[] = $this->cdata;
        }

        return $children;
    }

    /**
     * Add an attribute to the tag
     *
     * @param  AttributeDefinition  $att
     */
    public function addAttribute(AttributeDefinition $att)
    {
        array_push($this->atts, $att);
    }

    /**
     * Return list of attributes for this tag
     *
     * @return  array
     */
    public function getAttributes()
    {
        return $this->atts;
    }

    /**
     * Set the name of the tag
     *
     * @param name
     */
    public function setTagName($name)
    {
        $this->tagName = $name;
    }

    /**
     * Set the attribute that will be used as key.
     *
     * @return   name of the value
     */
    public function setKeyAttribute($att)
    {
        $this->name          = '__attribute';
        $this->nameAttribute = $att;
    }

    /**
     * get the name of the tag
     *
     * @return  string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * get the name of the tag
     *
     * @return  string
     */
    public function getKey(DefinedTag $tag)
    {
        if ('__attribute' == $this->name) {
            return $tag->getAttribute($this->nameAttribute);
        }

        return $this->name;
    }

    /**
     * Check, whether the value supports indexed children
     *
     * @return  boolean
     */
    public function supportsIndexedChildren()
    {
        if ($this->getType() == 'array') {
            return true;
        }

        return false;
    }

    /**
     * set the class loader for this tag
     *
     * @param  XJConfClassLoader  $classLoader
     */
    public function setClassLoader(XJConfClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
    }

    /**
     * extends itsself from another tag definition
     *
     * @param  TagDefinition  $tagDefinition  the tag definition to extend from
     */
    public function extend(self $tagDefinition)
    {
        $this->atts = $tagDefinition->getAttributes();
        $childDefs  = $tagDefinition->getChildDefinitions();
        foreach ($childDefs as $childDef) {
            $this->addChildDefinition($childDef);
        }
        
        $this->setter = $tagDefinition->setter;
    }

    /**
     * Get the methods
     *
     * @return array
     */
    public function getMethods() {
        return $this->methods;
    }

    /**
     * Get the value converter for this tag
     *
     * @return  ValueConverter
     */
    protected function getValueConverter()
    {
        if (null == $this->type) {
            throw new ValueConversionException('No type set. Can not create ValueConverter.');
        }

        if (null != $this->classLoader && in_array($this->type, $this->simpleTypes) == false) {
            $this->classLoader->loadClass($this->type);
        }

        if (null == $this->valueConverter) {
            $this->valueConverter = ValueConverterFactoryChain::getFactory($this)->createValueConverter($this);
        }

        return $this->valueConverter;
    }

    public function getChildDefinitionByTagName($name) {
        foreach ($this->childDefs as $childDef) {
            if ($childDef->getName() == $name) {
                return $childDef;
            }
        }
        return null;
    }
}
?><?php
/**
 * Interface that Definitions have to implement
 * that want to modify a value, after it has been conbverted.
 *
 * @package XJConf
 * @author  Stephan Schmidt <schst@php-tools.net>
 */

/**
 * Interface that Definitions have to implement
 * that want to modify a value, after it has been conbverted.
 *
 * @package XJConf
 */
interface ValueModifier {

    /**
     * Modify the converted value
     *
     * @param mixed $value
     * @param Tag $tag
     * @return mixed the modified value
     */
    public function modifyValue($value, Tag $tag);
}
?><?php
/**
 * Exception to be thrown in case that an invalid namespace definition was detected.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that an invalid namespace definition was detected.
 *
 * @package  XJConf
 */
class InvalidNamespaceDefinitionException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Exception to be thrown in case that an invalid tag definition was detected.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that an invalid tag definition was detected.
 *
 * @package  XJConf
 */
class InvalidTagDefinitionException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Exception to be thrown in case that an required attribute is missing.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that an required attribute is missing.
 *
 * @package  XJConf
 */
class MissingAttributeException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Exception to be thrown in case that an unknown namespace definition was detected.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that an unknown namespace definition was detected.
 *
 * @package  XJConf
 */
class UnknownNamespaceException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Exception to be thrown in case that an unknown tag definition was detected.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that an unknown tag definition was detected.
 *
 * @package  XJConf
 */
class UnknownTagException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Exception to be thrown in case that a method was called that should not be called.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that a method was called that should not be called.
 *
 * @package  XJConf
 */
class UnsupportedOperationException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Exception to be thrown in case that a value conversion failed.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case that a value conversion failed.
 * 
 * @package  XJConf
 */
class ValueConversionException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Base esception for all other XJConf exceptions.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
/**
 * Base esception for all other XJConf exceptions.
 *
 * @package  XJConf
 */
class XJConfException extends Exception
{
    // nothing to do here
}
?><?php
/**
 * Interface for XJConf Extensions
 * 
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
/**
 * Interface for XJConf Extensions
 * 
 * @package     XJConf
 * @subpackage  ext
 */
interface Extension
{
	/**
	 * Get the namespace URI used by the extension
	 * 
	 * @return  string
	 */
    public function getNamespace();
    
    /**
     * Process a start element
     * 
     * @param  XmlParser  $parser
     * @param  Tag        $tag
     * @throws XJConfException
     */
    public function startElement(XmlParser $parser, Tag $tag);

    /**
     * Process the end element
     * 
     * @param   XmlParser  $parser
     * @param   Tag        $tag
     * @throws  XJConfException
     */
    public function endElement(XmlParser $parser, Tag $tag);
}
?><?php
/**
 * Very basic xInclude mechanism
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('ext::Extension',
                   'exceptions::UnknownTagException',
                   'ext::xinc::XIncludeException'
);
/**
 * Very basic xInclude mechanism
 *
 * @package     XJConf
 * @subpackage  ext_xinc
 */
class XInclude implements Extension
{
    /**
     * name of tag
     */
    const TAG_NAME     = 'include';
    /**
     * Namespace of the extension
     *
     * @var  string
     */
    private $namespace = "http://www.w3.org/2001/XInclude";

    /**
     * Get the namspace URI
     *
     * @return  string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Handle an opening tag.
     *
     * Currently this does not do anything.
     *
     * Future versions should check, whether the file exists and skip all
     * child elements.
     *
     * @param  XmlParser  $parser
     * @param  Tag        $tag
     */
    public function startElement(XmlParser $reader, Tag $tag)
    {
        // nothing to do here
    }

    /**
     * Handle a closing tag.
     *
     * Does the actual x-include.
     *
     * @param   XmlParser  $parser
     * @param   Tag        $tag
     * @return  Tag
     * @throws  XIncludeException
     * @throws  UnknownTagException
     */
    public function endElement(XmlParser $reader, Tag $tag)
    {
    	if ($tag->getName() != self::TAG_NAME) {
    	    throw new UnknownTagException('Unknown tag <' + $tag->getName() . '> in XInclude namespace.');
    	}

        $href = $tag->getAttribute('href');
        if (null == $href) {
            return null;
        }

        if (substr($href, 0, 1) != '/') {
            $href = dirname($reader->getCurrentFile()) . '/' . $href;
        }

        try {
            $reader->parse($href);
            return null;
        } catch (Exception $e) {
            throw new XIncludeException('Could not xInclude ' . $href . ': ' . $e->getMessage());
        }
    }
}
?><?php
/**
 * Exception to be thrown in case XInclude failed.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 */
XJConfLoader::load('exceptions::XJConfException');
/**
 * Exception to be thrown in case XInclude failed.
 *
 * @package     XJConf
 * @subpackage  ext_xinc
 */
class XIncludeException extends XJConfException
{
    // nothing to do here
}
?><?php
/**
 * Generic Tag wrapper that can be used by extensions to dynamically add
 * children to other tags.
 *
 * @author Stephan Schmidt <me@schst.net>
 */
XJConfLoader::load('Tag');
/**
 * Generic Tag wrapper that can be used by extensions to dynamically add
 * children to other tags.
 *
 * @package  XJConf
 */
class GenericTag implements Tag
{
	/**
	 * name of the tag
	 *
	 * @var  string
	 */
	private $name     = null;
	/**
	 * character data
	 *
	 * @var  string
	 */
	private $data     = null;
	/**
	 * content of the tag (overrides data)
	 *
	 * @var  mixed
	 */
	private $content  = null;
	/**
	 * attributes of the tag
	 *
	 * @var  array
	 */
	private $atts     = array();
	/**
	 * Children of the tag
	 *
	 * @var  array
	 */
	private $children = array();
	/**
	 * value of the tag
	 *
	 * @var  mixed
	 */
	private $value    = null;
	/**
	 * Key of the tag
	 *
	 * @var  string
	 */
	private $key      = null;

	/**
	 * Create a new tag with or without attributes
	 *
	 * @param  string $name  name of the tag
	 * @param  array  $atts  optional  list of attributes
	 */
	public function __construct($name, $atts = array())
	{
		$this->name = $name;
		$this->atts = $atts;
	}

	/**
	 * Get the name of the tag
	 *
	 * @return  string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the key
	 *
	 * @param  string  $key
	 */
	public function setKey($key)
	{
		$this->key = $key;
	}

	/**
	 * Get the key under which the value will be stored
	 *
	 * @return  string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Add text data
	 *
	 * @param   string  $buf
	 * @return  int     new length of data
	 */
	public function addData($buf)
	{
		$this->data .= $buf;
		return strlen($this->data);
	}

	/**
	 * Get the character data of the tag
	 *
	 * @return  string
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Check, whether the tag has a certain attribute
	 *
	 * @param   string   $name
	 * @return  boolean
	 */
	public function hasAttribute($name)
	{
		return isset($this->atts[$name]);
	}

	/**
	 * get an attribute
	 *
	 * @param   string  $name  name of the attribute
	 * @return  string  value of the attribute
	 */
	public function getAttribute($name)
	{
		if ($this->hasAttribute($name) == true) {
		    return $this->atts[$name];
		}

		return null;
	}

	/**
	 * get all attributes
	 *
	 * @return  array
	 */
	public function getAttributes()
	{
		return $this->atts;
	}

	/**
	 * Add a new child to this tag.
	 *
	 * @param   Tag  $child  child to add
	 * @return  int  number of childs added
	 */
	public function addChild(Tag $child)
	{
		array_push($this->children, $child);
		return count($this->children);
	}

	/**
	 * Get the child with a specific name
	 *
	 * @param   string  $name
	 * @return  Tag
	 */
	public function getChild($name)
	{
	    foreach ($this->children as $child) {
		    if ($child->getName() == $name) {
				return $child;
			}
		}

		return null;
	}

	/**
	 * Get all children of the tag
	 *
	 * @return  array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
     * Set the content (overrides the character data)
     *
     * @param  mixed  $content
     */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Get the content
	 *
	 * @return  mixed
	 */
	public function getContent()
	{
		if (null != $this->content) {
			return $this->content;
		}

		return $this->getData();
	}

	/**
	 * Fetch the value
	 *
	 * @return	mixed  the value of the tag
	 */
	public function getConvertedValue()
	{
		return $this->value;
	}

	/**
	 * Get the type of the value
	 *
	 * @return  string
	 */
	public function getValueType(Tag $tag)
	{
		if (null == $this->value) {
			return null;
		}

		if (is_object($this->value) == true) {
            return get_class($this->value);
		}

		return gettype($this->value);
	}

	/**
	 * Get the setter method
	 */
    public function getSetterMethod()
    {
    	if (null == $this->key) {
    		return null;
    	}
    	return 'set' . ucfirst($this->key);
    }

	/**
	 * Checks, whether the tag supports indexed children
	 *
	 * @return  boolean
	 */
	public function supportsIndexedChildren()
	{
		return true;
	}

	/**
	 * Set the value of the tag
	 *
	 * @param  mixed
	 */
	public function setValue($value)
	 {
		$this->value = $value;
	}

	public function getDefinition() {
	    return null;
	}
}
?><?php
/**
 * Interface for holding tag data.
 *
 * @author  Stephan Schmidt <me@schst.net>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
/**
 * Interface for holding tag data.
 *
 * @package  XJConf
 */
interface Tag
{
    /**
	 * Get the name of the tag
	 *
	 * @return  name of the tag
	 */
	public function getName();

	/**
	 * Get the key under which the value will be stored
	 *
	 * @return  string
	 */
	public function getKey();

	/**
	 * Add text data
	 *
	 * @param   string  $buf
	 * @return  int     new length of data
	 */
	public function addData($buf);

	/**
	 * Get the character data of the tag
	 *
	 * @return   character data
	 */
	public function getData();

	/**
	 * Check, whether the tag has a certain attribute
	 *
	 * @param   string   $name
	 * @return  boolean
	 */
	public function hasAttribute($name);

	/**
	 * get an attribute
	 *
	 * @param   string  $name  name of the attribute
	 * @return  string  value of the attribute
	 */
	public function getAttribute($name);

	/**
	 * get all attributes
	 *
	 * @return  array
	 */
	public function getAttributes();

	/**
     * Add a new child to this tag.
     *
     * @param child  child to add
     * @return   int    number of childs added
     */
	public function addChild(Tag $child);

	/**
	 * Get the child with a specific name
	 *
	 * @param   string  $name
	 * @return  Tag
	 */
	public function getChild($name);

	/**
	 * Get all children of the tag
	 *
	 * @return  array
	 */
	public function getChildren();

	/**
     * Set the content (overrides the character data)
     *
     * @param  mixed  $content
     */
	public function setContent($content);

	/**
	 * Get the content
	 *
	 * @return  mixed
	 */
	public function getContent();

	/**
	 * Fetch the value
	 *
	 * @return	mixed  the value of the tag
	 */
	public function getConvertedValue();

	/**
	 * Get the type of the value
	 *
	 * @param   Tag     $tag
	 * @return  string
	 */
	public function getValueType(Tag $tag);

	/**
	 * Get the setter method
	 *
	 * @return  string
	 */
	public function getSetterMethod();

	public function getDefinition();

	/**
	 * Checks, whether the tag supports indexed children
	 *
	 * @return  boolean
	 */
	public function supportsIndexedChildren();
}
?><?php
/**
 * Interface for class loaders that can be used to load classes at runtime.
 *
 * @author  Frank Kleine <mikey@xjconf.net>
 */
/**
 * Interface for class loaders that can be used to load classes at runtime.
 *
 * @package  XJConf
 */
interface XJConfClassLoader
{
    /**
     * load the file with the given class
     *
     * @param  string  $fqClassName  the full qualified class name
     */
    public function loadClass($fqClassName);
    
    /**
     * returns short class name
     *
     * @param  string  $fqClassName  the full qualified class name
     */
    public function getType($fqClassName);
}
?><?php
/**
 * Facade for XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     XJConf
 */
XJConfLoader::load('DefinitionParser',
                   'XmlParser',
                   'ext::Extension',
                   'definitions::NamespaceDefinitions'
);
/**
 * Facade for XJConf.
 *
 * @package     XJConf
 */
class XJConfFacade
{
    /**
     * list of class loaders to use
     *
     * @var  array<string,XJConfClassLoader>
     */
    protected $classLoaders         = array();
    /**
     * list of extensions to use
     *
     * @var  array<string,Extension>
     */
    protected $extensions           = array();
    /**
     * list of namespace definitions to merge with default one
     *
     * @var  array<NamespaceDefinition>
     */
    protected $namespaceDefinitions = array();
    /**
     * the definition parser
     *
     * @var  DefinitionParser
     */
    protected $definitionParser;
    /**
     * the xml parser
     *
     * @var  XmlParser
     */
    protected $xmlParser;
    
    /**
     * construct the facade
     *
     * @param  array  $classLoaders<string,XJConfClassLoader>  optional  list of class loaders for given namespaces
     */
    public function __construct(array $classLoaders = array())
    {
        $this->classLoaders = $classLoaders;
    }

    /**
     * add an extension that handles all tags in given namespace
     *
     * @param  Extension  $ext        use this extension to handle all tags in given namespace
     * @param  string     $namespace  optional  handle all tags in this namespace with given extension
     */
    public function addExtension(Extension $ext, $namespace = null)
    {
        if (null == $namespace) {
            $namespace = $ext->getNamespace();
        }
        
        $this->extensions[$namespace] = $ext;
    }
    
    /**
     * enables xinclude
     */
    public function enableXIncludes()
    {
        XJConfLoader::load('ext::xinc::XInclude');
        $xincludeExtension = new XInclude();
        $this->addExtension($xincludeExtension);
    }
    
    /**
     * add a namespace definition
     *
     * @param  NamespaceDefinition  $namespaceDefinition
     */
    public function addNamespaceDefinition(NamespaceDefinition $namespaceDefinition)
    {
        $this->namespaceDefinitions[$namespaceDefinition->getNamespaceURI()] = $namespaceDefinition;
    }
    
    /**
     * add a namespace definition
     *
     * @param  NamespaceDefinitions  $namespaceDefinitions
     */
    public function addNamespaceDefinitions(NamespaceDefinitions $namespaceDefinitions)
    {
        $this->namespaceDefinitions = array_merge($this->namespaceDefinitions, $namespaceDefinitions->getDefinedNamespaces());
    }
    
    /**
     * parses a definition and returns the namespace definitions
     *
     * @param   string                $definitionFile
     * @return  NamespaceDefinitions
     */
    public function parseDefinition($definitionFile)
    {
        if (null == $this->definitionParser) {
            $this->definitionParser = new DefinitionParser($this->classLoaders);
        }
        
        return $this->definitionParser->parse($definitionFile);
    }
    
    /**
     * parses a definition file and adds its definitions
     *
     * @param  string  $definitionFile
     */
    public function addDefinition($definitionFile)
    {
        $this->addNamespaceDefinitions($this->parseDefinition($definitionFile));
    }
    
    /**
     * parses a definition file and adds its definitions
     *
     * @param  array  $definitions
     */
    public function addDefinitions(array $definitions)
    {
        foreach ($definitions as $definition) {
        	$this->addNamespaceDefinitions($this->parseDefinition($definition));
        }
    }
    
    /**
     * parses a given file and creates the data structure described in this file
     *
     * @param   string               $filename
     * @throws  stubXJConfException
     */
    public function parse($filename)
    {
        if (null == $this->xmlParser) {
            $this->xmlParser = new XmlParser();
        }
        
        $namespaceDefinitions = new NamespaceDefinitions();
        foreach ($this->namespaceDefinitions as $namespaceURI => $namespaceDefintion) {
            $namespaceDefinitions->addNamespaceDefinition($namespaceURI, $namespaceDefintion);
        }
        $this->xmlParser->setTagDefinitions($namespaceDefinitions);
        foreach ($this->extensions as $namespace => $extension) {
            $this->xmlParser->addExtension($extension, $namespace);
        }
        
        $this->xmlParser->parse($filename);
    }
    
    /**
     * checks whether a data structure associated with this name exists
     *
     * @param   string               $name
     * @return  bool
     * @throws  XJConfException
     */
    public function hasConfigValue($name)
    {
        if (null == $this->xmlParser) {
            throw new XJConfException('Invalid state: needs to parse first.');
        }
        
        return $this->xmlParser->hasConfigValue($name);
    }
    
    /**
     * returns the data structure associated with this name
     *
     * @param   string               $name
     * @return  mixed
     * @throws  XJConfException
     */
    public function getConfigValue($name)
    {
        if (null == $this->xmlParser) {
            throw new XJConfException('Invalid state: needs to parse first.');
        }
        
        return $this->xmlParser->getConfigValue($name);
    }
    
    /**
     * returns a list of all data structures
     *
     * @return  mixed
     * @throws  XJConfException
     */
    public function getConfigValues()
    {
        if (null == $this->xmlParser) {
            throw new XJConfException('Invalid state: needs to parse first.');
        }
        
        return $this->xmlParser->getConfigValues();
    }

    /**
     * clears parsed config values
     */
    public function clearConfigValues()
    {
        if (null == $this->xmlParser) {
            throw new XJConfException('Invalid state: needs to parse first.');
        }
        
        $this->xmlParser->clearConfigValues();
    }
}
?><?php
/**
 * Class loader for all XJConf classes.
 *
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
/**
 * Class loader for all XJConf classes.
 *
 * The class loader takes care that all class files are only loaded once. It
 * allows all classes to include the required files without knowing where they
 * reside or if they have been loaded before.
 *
 * @package  XJConf
 */
class XJConfLoader
{
    /**
     * list of loaded files
     *
     * @var  array<String>
     */
    private static $loadedClasses = array();

    /**
     * method to load files from source path
     *
     * Usage: XJConfLoader::load('path::to::Classfile');
     * or load more than one at once:
     * XJConfLoader::load('path::to::first::Class',
                          'path::to::second::Class'
       );
     * You may name as many files as you like, there is no restriction
     * on the number of arguments.
     *
     * @param   string  list of file names to load
     */
    public static function load()
    {
        $classes = func_get_args();
        if (count($classes) == 0) {
            // its ok to call this without any arguments, this won't cause any harm
            return;
        }

        $realFiles = array();
        foreach ($classes as $className) {
            if (in_array(str_replace('net::xjconf::', '', $className), self::$loadedClasses) == TRUE) {
                continue; // step to next file if file is already loaded
            }

            $uri = null;
            array_push(self::$loadedClasses, str_replace('net::xjconf::', '', $className));
            if (class_exists('StarClassRegistry', false) === true) {
                if (substr($className, 0, 13) != 'net::xjconf::') {
                    $uri = StarClassRegistry::getUriForClass('net::xjconf::' . $className);
                } else {
                    $uri = StarClassRegistry::getUriForClass($className);
                }
            }
            if ($uri === null) {
                $uri = dirname(__FILE__) . '/' . self::mapClassname($className);
            }
            
            require $uri;
        }
    }

    /**
     * checks whether a file with the given class exists
     *
     * @param   string  $fqClassName
     * @return  bool
     */
    public static function classFileExists($className)
    {
        if (class_exists('StarClassRegistry') == false) {
            return file_exists(dirname(__FILE__) . '/' . self::mapClassname($className));
        }

        if (substr($className, 0, 13) != 'net::xjconf::') {
            $fqClassName = 'net::xjconf::' . $className;
        } else {
            $fqClassName = $className;
        }
        
        if (StarClassRegistry::getFileForClass($fqClassName) != null) {
            return true;
        }
        if (substr(dirname(__FILE__), 0, 7) == 'star://') {
            return false;
        }
        
        return file_exists(dirname(__FILE__) . '/' . self::mapClassname($className));
    }

    /**
     * maps classnames given to loadClass() into required ones for load()
     *
     * @param  string  $classname  name of class given to loadClass()
     * @return string  name of class required for load()
     */
    private static function mapClassname($classname)
    {
        return str_replace('::', '/', $classname) . '.php';
    }
}
?><?php
/**
 * Parser that reads xml files and generates the data structure.
 *
 * @author  Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author  Frank Kleine <frank.kleine@schlund.de>
 */
XJConfLoader::load('DefinedTag',
                   'GenericTag',
                   'definitions::NamespaceDefinition',
                   'definitions::NamespaceDefinitions',
                   'exceptions::UnknownNamespaceException',
                   'exceptions::UnknownTagException',
                   'exceptions::XJConfException'
);
/**
 * Parser that reads xml files and generates the data structure.
 *
 * This parser reads xml files using the tag definitions and
 * created the data structure and objects described by tag
 * definitions and the xml file.
 *
 * @package  XJConf
 */
class XmlParser
{
    /**
     * the list of tags that have to be processed
     *
     * @var  array<Tag>
     */
    private $tagStack    = array();
    /**
     * hashmap of generated data types
     *
     * @var  array<String, mixed>
     */
    private $config      = array();
    /**
     * a listof defined namespaces
     *
     * @var  NamespaceDefinitions
     */
    private $tagDefs;
    /**
     * current depth within the parsed document
     *
     * @var  int
     */
    private $depth       = 0;
    /**
     * list of extensions to use for the namespace
     *
     * @var  array<String, Extension>
     */
    private $extensions  = array();
    /**
     * the default namespace if none is set
     *
     * @var  string
     */
    private $myNamespace = 'http://xjconf.net/XJConf';
    /**
     * stack of currently opened files
     *
     * @var  array<String>
     */
    private $openFiles   = array();

    /**
     * list of node types, used for compatibility between PHP 5.0 and 5.1
     *
     * @var  array
     */
    private $nodeTypes   = array();

    /**
     * constructor
     *
     * Sets the node types depending on your PHP version using the constants
     * defined by the XMLReader PHP extension.
     */
    public function __construct()
    {
        if (!defined('XMLREADER_ELEMENT')) {
            $this->nodeTypes = array('startTag' => XMLReader::ELEMENT,
                                     'text'     => XMLReader::TEXT,
                                     'endTag'   => XMLReader::END_ELEMENT
                               );
        } else {
            $this->nodeTypes = array('startTag' => XMLREADER_ELEMENT,
                                     'text'     => XMLREADER_TEXT,
                                     'endTag'   => XMLREADER_END_ELEMENT
                               );
        }
    }

    /**
     * set the list of namespace defintions
     *
     * @param  NamespaceDefinitions  $tagDefs
     */
    public function setTagDefinitions(NamespaceDefinitions $tagDefs)
    {
        $this->tagDefs = $tagDefs;
    }

    /**
     * add some more namespace definitions
     *
     * @param  NamespaceDefinitions  $tagDefs
     */
    public function addTagDefinitions(NamespaceDefinitions $tagDefs)
    {
        if (null == $this->tagDefs) {
            $this->setTagDefinitions($tagDefs);
            return;
        }

        $this->tagDefs->appendNamespaceDefinitions($tagDefs);
    }

    /**
     * add an extension that handles all tags in given namespace
     *
     * @param  string     $namespace  handle all tags in this namespace with given extension
     * @param  Extension  $ext        use this extension to handle all tags in given namespace
     */
    public function addExtension(Extension $ext, $namespace = null)
    {
        if ($namespace == null) {
            $namespace = $ext->getNamespace();
        }
        $this->extensions[$namespace] = $ext;
    }

    /**
     * parses a given file and creates the data structure described in this file
     *
     * @param   string  $filename
     * @throws  XJConfException
     */
    public function parse($filename)
    {
        $reader = $this->initParser();
        array_push($this->openFiles, $filename);
        if (@$reader->open($filename) === false) {
            throw new XJConfException('Can not open file ' . $filename);
        }
        
        while ($reader->read()) {
            switch ($reader->nodeType) {
                case $this->nodeTypes['startTag']:
                    $empty = $reader->isEmptyElement;
                    $nameSpaceURI = $reader->namespaceURI;
                    $elementName  = $reader->localName;
                    $attributes   = array();
                    if (TRUE == $reader->hasAttributes) {
                        // go to first attribute
                        $attribute = $reader->moveToFirstAttribute();
                        // save data of all attributes
                        while (TRUE == $attribute) {
                            $attributes[$reader->localName] = $reader->value;
                            $attribute = $reader->moveToNextAttribute();
                        }
                    }

                    $this->startElement($nameSpaceURI, $elementName, $attributes);
                    if (true === $empty) {
                        $this->endElement($nameSpaceURI, $elementName);
                    }
                    break;

                case $this->nodeTypes['text']:
                    $this->characters($reader->value);
                    break;

                case $this->nodeTypes['endTag']:
                    $this->endElement($reader->namespaceURI, $reader->localName);
                    break;
            }
        }

        $reader->close($filename);
        array_pop($this->openFiles);

    }
    
    /**
     * checks whether a config value exists or not
     *
     * @return  bool
     */
    public function hasConfigValue($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * returns the data structure associated with this name
     *
     * @param   string  $name
     * @return  mixed
     */
    public function getConfigValue($name)
    {
        if ($this->hasConfigValue($name) == true) {
            return $this->config[$name];
        }
        
        return null;
    }
    
    /**
     * returns all config values as array
     *
     * @return  array
     */
    public function getConfigValues()
    {
        return $this->config;
    }

    /**
     * clears parsed config values
     */
    public function clearConfigValues()
    {
        $this->config = array();
    }

    /**
     * returns the name of the file that is currently parsed
     *
     * @return  string
     */
    public function getCurrentFile()
    {
        return end($this->openFiles);
    }

    /**
     * initializes the parser
     */
    private function initParser()
    {
        $reader = new XMLReader();
        return $reader;
    }

    /**
     * handles the start element
     *
     * Creates a new Tag object and pushes it
     * onto the stack.
     *
     * @param  string  $namespaceURI  namespace of start tag
     * @param  string  $sName         name of start tag
     * @param  array   $atts          attributes of tag
     */
    private function startElement($namespaceURI, $sName, $atts)
    {
        // do not handle stuff in our own namespace
        if ($this->myNamespace == $namespaceURI && 0 < $this->depth) {
            return;
        }
        $this->depth++;

        // no namespace defined, use the default namespace
        if (strlen($namespaceURI) == 0) {
            $namespaceURI = '__default';
        }

        // ignore the root tag
        if (1 == $this->depth) {
            return;
        }

        // This tag needs to be handled by an extension
        if (isset($this->extensions[$namespaceURI]) == true) {
            $tag = new GenericTag($sName, $atts);
            $this->extensions[$namespaceURI]->startElement($this, $tag);
        // This tag has been defined internally
        } else {
            if ($this->tagDefs->isNamespaceDefined($namespaceURI) == false) {
                throw new UnknownNamespaceException('Unknown namespace ' . $namespaceURI . ' in file ' . end($this->openFiles));
            }

            $newDef = null;
            $lastTag = end($this->tagStack);
            if ($lastTag != null) {
                $lastDef = $lastTag->getDefinition();
                if ($lastDef != null) {
                    $newDef = $lastDef->getChildDefinitionByTagName($sName);
                }
            }
            if ($newDef === null) {
                if ($this->tagDefs->isTagDefined($namespaceURI, $sName) == false) {
                    throw new UnknownTagException('Unknown tag ' . $sName . ' in namespace ' . $namespaceURI);
                }
                $newDef = $this->tagDefs->getTagDefinition($namespaceURI, $sName);
            }

            $tag = new DefinedTag($sName, $atts);
            // fetch the defintion for this tag
            $tag->setDefinition($newDef);
        }

        array_push($this->tagStack, $tag);
    }

    /**
     * handles the end element
     *
     * Fetches the current element from the stack and
     * converts it to the correct type.
     *
     * @param  string  $namespaceURI  namespace of end tag
     * @param  string  $sName         name of end tag
     */
    private function endElement($namespaceURI, $sName)
    {
        // do not handle stuff in our own namespace
        if ($this->myNamespace == $namespaceURI && 0 < $this->depth) {
            return;
        }
        $this->depth--;

        // no namespace defined, use the default namespace
        if (strlen($namespaceURI) == 0) {
            $namespaceURI = '__default';
        }

        // ignore the root tag
        if (0 == $this->depth) {
            return;
        }

        // get the last tag from the stack
        $tag = array_pop($this->tagStack);

        // This tag needs to be handled by an extension
        if (isset($this->extensions[$namespaceURI]) == true) {
            $result = $this->extensions[$namespaceURI]->endElement($this, $tag);
            if (null != $result) {
                if (1 == $this->depth) {
                    $this->config[$tag->getKey()] = $result->getConvertedValue();
                } else {
                    $parent = array_pop($this->tagStack);
                    if ($result->getKey() == null && $parent->supportsIndexedChildren() == false) {
                        $parent->setContent($result->getConvertedValue());
                    } else {
                        $parent->addChild($result);
                    }
                    array_push($this->tagStack, $parent);
                }
            }
        // last tag before returning to root
        } elseif (1 == $this->depth) {
            $this->config[$tag->getKey()] = $tag->getConvertedValue();
        // add this tag to the tag before as child
        } else {
            $parent = array_pop($this->tagStack);
            $parent->addChild($tag);
            array_push($this->tagStack, $parent);
        }
    }

    /**
     * Character data handler
     *
     * Fetches the current tag from the stack and
     * appends the data.
     *
     * @param  string  $buf
     */
    private function characters($buf)
    {
        if (count($this->tagStack) == 0) {
            return;
        }

        $tag = end($this->tagStack);
        $tag->addData($buf);
    }
}
?>
title => XJConf for PHP
package => net::xjconf
version => 0.3.0dev
author => XJConf Development Team <http://php.xjconf.net>
copyright => (c) 2007-2008 XJConf Development Team
