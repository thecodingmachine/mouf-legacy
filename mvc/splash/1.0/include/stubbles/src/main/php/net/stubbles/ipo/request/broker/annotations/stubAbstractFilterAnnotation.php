<?php
/**
 * Abstract base class for filter annotations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::stubRequestBrokerException',
                      'net::stubbles::ipo::request::broker::annotations::stubFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubDefaultValueFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubRequiredFilterDecorator',
                      'net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation',
                      'net::stubbles::reflection::stubReflectionClass'
);
/**
 * Abstract base class for filter annotations.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
abstract class stubAbstractFilterAnnotation extends stubAbstractAnnotation implements stubFilterAnnotation
{
    /**
     * the name of the request variable
     *
     * @var  string
     */
    protected $fieldName;
    /**
     * reflection instance of request value error factory to use
     * 
     * @var  stubReflectionClass
     */
    protected $rveFactoryClass;
    /**
     * the created rve factory
     *
     * @var  stubRequestValueErrorFactory
     */
    protected $rveFactory      = null;
    /**
     * switch whether the value is required or not
     *
     * @var  bool
     */
    protected $isRequired      = true;
    /**
     * the default value in case given value not set
     *
     * @var  mixed
     */
    protected $defaultValue    = null;

    /**
     * the name of the request variable
     *
     * @param  string  $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * returns the name of the request variable
     *
     * @return  string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * sets the reflection instance of request value error factory to use
     *
     * @param  stubReflectionClass  $rveFactoryClass
     */
    public function setRVEFactoryClass(stubReflectionClass $rveFactoryClass)
    {
        $this->rveFactoryClass = $rveFactoryClass;
    }

    /**
     * sets whether the value is required or not
     *
     * @param  bool  $isRequired
     */
    public function setRequired($isRequired)
    {
        $this->isRequired = $isRequired;
    }

    /**
     * set a default value in case the value to filter is not set
     *
     * @param  mixed  $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     * @throws  stubRequestBrokerException
     */
    public function getFilter()
    {
        $filter = $this->doGetFilter();
        if (true === $this->isRequired) {
            $filter = new stubRequiredFilterDecorator($filter, $this->createRVEFactory());
        }
        
        if (null !== $this->defaultValue) {
            $filter = new stubDefaultValueFilterDecorator($filter, $this->defaultValue);
        }
        
        return $filter;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     * @throws  stubException
     */
    protected abstract function doGetFilter();

    /**
     * helper method to create a new instance of the request value error factory to use
     *
     * @return  stubRequestValueErrorFactory
     * @throws  stubRequestBrokerException
     */
    protected function createRVEFactory()
    {
        if (null !== $this->rveFactory) {
            return $this->rveFactory;
        }
        
        if (null != $this->rveFactoryClass) {
            $rveFactory = $this->rveFactoryClass->newInstance();
            if (($rveFactory instanceof stubRequestValueErrorFactory) === false) {
                throw new stubRequestBrokerException('Created request value error factory is not an instance of stubRequestValueErrorFactory');
            }
            
            $this->rveFactory = $rveFactory;
        } else {
            stubClassLoader::load('net::stubbles::ipo::request::stubRequestValueErrorXJConfFactory');
            $this->rveFactory = new stubRequestValueErrorXJConfFactory();
        }
        
        return $this->rveFactory;
    }

    /**
     * returns the target of the annotation as bitmap
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD + stubAnnotation::TARGET_PROPERTY;
    }
}
?>