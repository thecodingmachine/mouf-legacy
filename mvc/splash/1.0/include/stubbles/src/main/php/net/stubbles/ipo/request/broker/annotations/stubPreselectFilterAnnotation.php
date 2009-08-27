<?php
/**
 * Filter annotation for a list of preselected values.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubStringFilter',
                      'net::stubbles::ipo::request::filter::stubValidatorFilterDecorator',
                      'net::stubbles::ipo::request::validator::stubPreSelectValidator',
                      'net::stubbles::reflection::stubReflectionClass'
);
/**
 * Filter annotation for a list of preselected values.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubPreselectFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * source data class
     *
     * @var  stubReflectionClass
     */
    protected $sourceDataClass;
    /**
     * source data method
     *
     * @var  string
     */
    protected $sourceDataMethod = 'getData';
    /**
     * the error id to use in case the validation fails
     *
     * @var  string
     */
    protected $errorId          = 'FIELD_WRONG_VALUE';

    /**
     * sets the source data class
     *
     * @param  stubReflectionClass  $sourceDataClass
     */
    public function setSourceDataClass(stubReflectionClass $sourceDataClass)
    {
        $this->sourceDataClass = $sourceDataClass;
    }

    /**
     * sets the source data method
     *
     * @param  string  $sourceDataMethod
     */
    public function setSourceDataMethod($sourceDataMethod)
    {
        $this->sourceDataMethod = $sourceDataMethod;
    }

    /**
     * sets the error id to be used
     *
     * @param  string  $errorId
     */
    public function setErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubValidatorFilterDecorator
     */
    protected function doGetFilter()
    {
        $preselectData            = $this->sourceDataClass->getMethod($this->sourceDataMethod)->invoke(null);
        $validatorFilterDecorator =  new stubValidatorFilterDecorator(new stubStringFilter(),
                                                                      $this->createRVEFactory(),
                                                                      new stubPreSelectValidator($preselectData)
                                     );
        $validatorFilterDecorator->setErrorId($this->errorId);
        return $validatorFilterDecorator;
    }
}
?>