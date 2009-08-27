<?php
/**
 * Filter annotation for passwords.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubEncodingFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubLengthFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubPasswordFilter',
                      'net::stubbles::ipo::request::validator::stubMinLengthValidator',
                      'net::stubbles::reflection::annotations::stubAnnotation'
);
/**
 * Filter annotation for passwords.
 * 
 * The default minimum length of the password will be 6 characters if the
 * property is not set within the annotation.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubPasswordFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * minimum length of the password
     *
     * @var  int
     */
    protected $minLength    = 6;
    /**
     * the encoder class to be used
     *
     * @var  stubReflectionClass
     */
    protected $encoderClass = null;

    /**
     * sets the minimum length of the password
     *
     * @param  int  $minLength
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * sets the encoder class to be used
     *
     * @param  stubReflectionClass  $encoderClass
     */
    public function setEncoder(stubReflectionClass $encoderClass)
    {
        $this->encoderClass = $encoderClass;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected function doGetFilter()
    {
        $rveFactory = $this->createRVEFactory();
        $filter     = new stubLengthFilterDecorator(new stubPasswordFilter($rveFactory), $rveFactory);
        $filter->setMinLengthValidator(new stubMinLengthValidator($this->minLength));
        if (null !== $this->encoderClass) {
            $filter = new stubEncodingFilterDecorator($filter, $this->encoderClass->newInstance(), stubStringEncoder::MODE_ENCODE);
        }
        
        return $filter;
    }
}
?>