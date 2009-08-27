<?php
/**
 * Filter annotation for integers.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubIntegerFilter',
                      'net::stubbles::ipo::request::filter::stubRangeFilterDecorator',
                      'net::stubbles::ipo::request::validator::stubMaxNumberValidator',
                      'net::stubbles::ipo::request::validator::stubMinNumberValidator'
);
/**
 * Filter annotation for integers.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubIntegerFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * minimum value of the integer
     *
     * @var  int
     */
    protected $minValue;
    /**
     * the error id to use in case min validation fails
     *
     * @var  string
     */
    protected $minErrorId;
    /**
     * maximum value of the integer
     *
     * @var  int
     */
    protected $maxValue;
    /**
     * the error id to use in case max validation fails
     *
     * @var  string
     */
    protected $maxErrorId;

    /**
     * sets the minimum value of the integer
     *
     * @param  int  $minValue
     */
    public function setMinValue($minValue)
    {
        $this->minValue = $minValue;
    }

    /**
     * sets the error id to use in case max validation fails
     *
     * @param  string  $minErrorId
     */
    public function setMinErrorId($minErrorId)
    {
        $this->minErrorId = $minErrorId;
    }

    /**
     * sets the maximum value of the integer
     *
     * @param  int  $maxValue
     */
    public function setMaxValue($maxValue)
    {
        $this->maxValue = $maxValue;
    }

    /**
     * sets the error id to use in case max validation fails
     *
     * @param  string  $maxErrorId
     */
    public function setMaxErrorId($maxErrorId)
    {
        $this->maxErrorId = $maxErrorId;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected function doGetFilter()
    {
        $filter = new stubIntegerFilter();
        if (null !== $this->minValue || null !== $this->maxValue) {
            $filter = new stubRangeFilterDecorator($filter, $this->createRVEFactory());
            if (null !== $this->minValue) {
                $filter->setMinValidator(new stubMinNumberValidator($this->minValue), $this->minErrorId);
            }
            
            if (null !== $this->maxValue) {
                $filter->setMaxValidator(new stubMaxNumberValidator($this->maxValue), $this->maxErrorId);
            }
        }
        
        return $filter;
    }
}
?>