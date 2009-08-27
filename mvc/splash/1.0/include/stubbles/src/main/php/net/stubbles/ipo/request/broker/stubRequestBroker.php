<?php
/**
 * Broker class to transfer values from the request into an object via annotations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::request::broker::stubRequestBrokerException',
                      'net::stubbles::ipo::request::broker::stubRequestBrokerMethodPropertyMatcher',
                      'net::stubbles::ipo::request::broker::annotations::stubFloatFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubHTTPURLFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubIntegerFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubMailFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubPasswordFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubPreselectFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubStringFilterAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubTextFilterAnnotation',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::reflection::stubReflectionClass'
);
/**
 * Broker class to transfer values from the request into an object via annotations.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker
 */
class stubRequestBroker extends stubBaseObject
{
    /**
     * the matcher to be used for methods and properties
     *
     * @var  stubRequestBrokerMethodPropertyMatcher
     */
    protected static $methodAndPropertyMatcher;

    /**
     * static initializer
     */
    // @codeCoverageIgnoreStart
    public static function __static()
    {
        self::$methodAndPropertyMatcher = new stubRequestBrokerMethodPropertyMatcher();
    }
    // @codeCoverageIgnoreEnd

    /**
     * does the real action
     *
     * @param   stubRequest               $request
     * @param   object                    $object           the object instance to fill with values
     * @param   string                    $prefix           optional  prefix for access to request values
     * @param   array<string,stubFilter>  $overruleFilters  optional  list of filters to overrule annotated filters with
     * @throws  stubIllegalArgumentException
     */
    public function process(stubRequest $request, $object, $prefix = '', array $overruleFilters = array())
    {
        if ($object instanceof stubObject) {
            $refClass = $object->getClass();
        } elseif (is_object($object) === true) {
            $refClass = new stubReflectionClass(get_class($object));
        } else {
            throw new stubIllegalArgumentException('Parameter object must a concrete object instance.');
        }
        
        foreach ($refClass->getPropertiesByMatcher(self::$methodAndPropertyMatcher) as $refProperty) {
            $filterAnnotation = $refProperty->getAnnotation('Filter');
            $fieldName        = $prefix . $filterAnnotation->getFieldName();
            if (isset($overruleFilters[$fieldName]) === true) {
                $filter = $overruleFilters[$fieldName];
            } else {
                $filter = $filterAnnotation->getFilter();
            }
            
            $value = $request->getFilteredValue($filter, $fieldName);
            if ($request->hasValueError($prefix . $filterAnnotation->getFieldName()) === false) {
                $refProperty->setValue($object, $value);
            }
        }
        
        foreach ($refClass->getMethodsByMatcher(self::$methodAndPropertyMatcher) as $refMethod) {
            $filterAnnotation = $refMethod->getAnnotation('Filter');
            $fieldName        = $prefix . $filterAnnotation->getFieldName();
            if (isset($overruleFilters[$fieldName]) === true) {
                $filter = $overruleFilters[$fieldName];
            } else {
                $filter = $filterAnnotation->getFilter();
            }
            
            $value = $request->getFilteredValue($filter, $fieldName);
            if ($request->hasValueError($prefix . $filterAnnotation->getFieldName()) === false) {
                $refMethod->invoke($object, $value);
            }
        }
    }
}
?>