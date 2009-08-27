<?php
/**
 * Filter annotation for dates.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 * @version     $Id: stubDateFilterAnnotation.php 1882 2008-10-07 12:38:56Z richi $
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubAbstractFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubDateFilter',
                      'net::stubbles::ipo::request::filter::stubPeriodFilterDecorator',
                      'net::stubbles::lang::types::stubDate',
                      'net::stubbles::reflection::stubBaseReflectionClass',
                      'net::stubbles::reflection::annotations::stubAnnotation'
);
/**
 * Filter annotation for dates.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubDateFilterAnnotation extends stubAbstractFilterAnnotation implements stubAnnotation
{
    /**
     * minimum date
     *
     * @var  stubDate
     */
    protected $minDate;
    /**
     * minimum date provider class
     *
     * @var  stubBaseReflectionClass
     */
    protected $minDateProviderClass;
    /**
     * minimum date provider method
     *
     * @var  string
     */
    protected $minDateProviderMethod = 'getMinDate';
    /**
     * the error id to use in case min date validation fails
     *
     * @var  string
     */
    protected $minDateErrorId;
    /**
     * maximum date
     *
     * @var  stubDate
     */
    protected $maxDate;
    /**
     * maximum date provider class
     *
     * @var  stubBaseReflectionClass
     */
    protected $maxDateProviderClass;
    /**
     * maximum date provider method
     *
     * @var  string
     */
    protected $maxDateProviderMethod = 'getMaxDate';
    /**
     * the error id to use in case max date validation fails
     *
     * @var  string
     */
    protected $maxDateErrorId;
    /**
     * format for dates in error messages
     *
     * @var  string
     */
    protected $dateFormat;

    /**
     * sets the minimum date
     *
     * @param  int|string|stubDate  $minDate
     */
    public function setMinDate($minDate)
    {
        if (($minDate instanceof stubDate) === false) {
            $minDate = new stubDate($minDate);
        }

        $this->minDate = $minDate;
    }

    /**
     * sets the minimum date provider class
     *
     * @param  stubBaseReflectionClass  $minDateProviderClass
     */
    public function setMinDateProviderClass(stubBaseReflectionClass $minDateProviderClass)
    {
        $this->minDateProviderClass = $minDateProviderClass;
    }

    /**
     * sets the minimum date provider method
     *
     * @param  string  $minDateProviderMethod
     */
    public function setMinDateProviderMethod($minDateProviderMethod)
    {
        $this->minDateProviderMethod = $minDateProviderMethod;
    }

    /**
     * sets the error id to use in case min date validation fails
     *
     * @param  string  $minDateErrorId
     */
    public function setMinDateErrorId($minDateErrorId)
    {
        $this->minDateErrorId = $minDateErrorId;
    }

    /**
     * sets the maximum date
     *
     * @param  int|string|stubDate  $maxDate
     */
    public function setMaxDate($maxDate)
    {
        if (($maxDate instanceof stubDate) === false) {
            $maxDate = new stubDate($maxDate);
        }

        $this->maxDate = $maxDate;
    }

    /**
     * sets the maximum date provider class
     *
     * @param  stubBaseReflectionClass  $maxDateProviderClass
     */
    public function setMaxDateProviderClass(stubBaseReflectionClass $maxDateProviderClass)
    {
        $this->maxDateProviderClass = $maxDateProviderClass;
    }

    /**
     * sets the maximum date provider method
     *
     * @param  string  $maxDateProviderMethod
     */
    public function setMaxDateProviderMethod($maxDateProviderMethod)
    {
        $this->maxDateProviderMethod = $maxDateProviderMethod;
    }

    /**
     * sets the error id to use in case max date validation fails
     *
     * @param  string  $maxDateErrorId
     */
    public function setMaxDateErrorId($maxDateErrorId)
    {
        $this->maxDateErrorId = $maxDateErrorId;
    }

    /**
     * sets the format for dates in error messages
     *
     * @param  string  $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected function doGetFilter()
    {
        $filter = new stubDateFilter($this->createRVEFactory());
        if (null == $this->minDate && null !== $this->minDateProviderClass) {
            $this->setMinDate($this->getDateFromProvider($this->minDateProviderClass, $this->minDateProviderMethod));
        }
        if (null == $this->maxDate && null !== $this->maxDateProviderClass) {
            $this->setMaxDate($this->getDateFromProvider($this->maxDateProviderClass, $this->maxDateProviderMethod));
        }

        if (null !== $this->minDate || null !== $this->maxDate) {
            $filter = new stubPeriodFilterDecorator($filter, $this->createRVEFactory());
            if (null !== $this->minDate) {
                $filter->setMinDate($this->minDate, $this->minDateErrorId);
            }

            if (null !== $this->maxDate) {
                $filter->setMaxDate($this->maxDate, $this->maxDateErrorId);
            }

            if (null != $this->dateFormat) {
                $filter->setDateFormat($this->dateFormat);
            }
        }

        return $filter;
    }

    /**
     * retrieves date from provider
     *
     * @param   stubBaseReflectionClass  $providerClass
     * @param   string                   $providerMethod
     * @return  int|string|stubDate
     */
    protected function getDateFromProvider(stubBaseReflectionClass $providerClass, $providerMethod)
    {
        $method = $providerClass->getMethod($providerMethod);
        if ($method->isStatic() === true) {
            return $method->invoke(null);
        }

        return $method->invoke($providerClass->newInstance());
    }
}
?>