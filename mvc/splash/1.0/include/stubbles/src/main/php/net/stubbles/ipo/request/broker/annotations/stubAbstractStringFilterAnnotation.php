<?php
/**
 * Filter annotation for strings.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubEncodingFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubLengthFilterDecorator',
                      'net::stubbles::ipo::request::validator::stubMaxLengthValidator',
                      'net::stubbles::ipo::request::validator::stubMinLengthValidator',
                      'net::stubbles::php::string::stubStringEncoder'
);
/**
 * Filter annotation for strings.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
abstract class stubAbstractStringFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * minimum length of the string
     *
     * @var  int
     */
    protected $minLength;
    /**
     * the error id to use in case min length validation fails
     *
     * @var  string
     */
    protected $minLengthErrorId;
    /**
     * maximum length of the string
     *
     * @var  int
     */
    protected $maxLength;
    /**
     * the error id to use in case max length validation fails
     *
     * @var  string
     */
    protected $maxLengthErrorId;
    /**
     * the encoder class to be used
     *
     * @var  stubReflectionClass
     */
    protected $encoderClass = null;
    /**
     * the encoding mode to be applied
     *
     * @var  int
     */
    protected $encoderMode  = stubStringEncoder::MODE_DECODE;

    /**
     * checks if the regex property is set and if the encoder mode is correct
     *
     * @throws  ReflectionException
     */
    public function finish()
    {
        if (null !== $this->encoderClass && in_array($this->encoderMode, array(stubStringEncoder::MODE_DECODE, stubStringEncoder::MODE_ENCODE)) === false) {
            throw new ReflectionException('Can not use ' . $this->getClassName() . ' with wrong encoder mode ' . $this->encoderMode);
        }
    }

    /**
     * sets the minimum length of the string
     *
     * @param  int  $minLength
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
    }

    /**
     * sets the error id to use in case min length validation fails
     *
     * @param  string  $minLengthErrorId
     */
    public function setMinLengthErrorId($minLengthErrorId)
    {
        $this->minLengthErrorId = $minLengthErrorId;
    }

    /**
     * sets the maximum length of the string
     *
     * @param  int  $maxLength
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * sets the error id to use in case max length validation fails
     *
     * @param  string  $maxLengthErrorId
     */
    public function setMaxLengthErrorId($maxLengthErrorId)
    {
        $this->maxLengthErrorId = $maxLengthErrorId;
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
     * sets the encoder mode to be used
     *
     * @param  int  $mode
     */
    public function setEncoderMode($mode)
    {
        $this->encoderMode = $mode;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected function doGetFilter()
    {
        $filter = $this->doDoGetFilter();
        if (null !== $this->minLength || null !== $this->maxLength) {
            $filter = new stubLengthFilterDecorator($filter, $this->createRVEFactory());
            if (null !== $this->minLength) {
                $filter->setMinLengthValidator(new stubMinLengthValidator($this->minLength), $this->minLengthErrorId);
            }
            
            if (null !== $this->maxLength) {
                $filter->setMaxLengthValidator(new stubMaxLengthValidator($this->maxLength), $this->maxLengthErrorId);
            }
        }
        
        if (null !== $this->encoderClass) {
            $filter = new stubEncodingFilterDecorator($filter, $this->encoderClass->newInstance(), $this->encoderMode);
        }
        
        return $filter;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected abstract function doDoGetFilter();
}
?>