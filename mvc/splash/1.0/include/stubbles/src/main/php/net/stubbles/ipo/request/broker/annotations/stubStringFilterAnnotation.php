<?php
/**
 * Filter annotation for strings.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::ipo::request::broker::annotations::stubAbstractStringFilterAnnotation',
                      'net::stubbles::ipo::request::filter::stubValidatorFilterDecorator',
                      'net::stubbles::ipo::request::filter::stubStringFilter',
                      'net::stubbles::ipo::request::validator::stubRegexValidator'
);
/**
 * Filter annotation for strings.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubStringFilterAnnotation extends stubAbstractStringFilterAnnotation
{
    /**
     * regular expression to filter the string
     *
     * @var  string
     */
    protected $regex   = null;
    /**
     * error if to be used
     *
     * @var  string
     */
    protected $errorId = null;

    /**
     * sets the regular expression to filter the string
     *
     * @param  string  $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * set error id to be used in case regular expression fails
     *
     * @param  string  $errorId
     */
    public function setRegexErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected function doDoGetFilter()
    {
        $stringFilter  = new stubStringFilter();
        if (null !== $this->regex) {
            $stringFilter = new stubValidatorFilterDecorator($stringFilter, $this->createRVEFactory(), new stubRegexValidator($this->regex));
            if (null !== $this->errorId) {
                $stringFilter->setErrorId($this->errorId);
            }
        }
        
        return $stringFilter;
    }
}
?>