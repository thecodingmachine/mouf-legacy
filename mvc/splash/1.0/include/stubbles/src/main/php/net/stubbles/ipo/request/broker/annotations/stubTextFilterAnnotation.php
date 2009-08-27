<?php
/**
 * Filter annotation for texts.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
stubClassLoader::load('net::stubbles::ipo::request::filter::stubTextFilter',
                      'net::stubbles::ipo::request::broker::annotations::stubAbstractStringFilterAnnotation'
);
/**
 * Filter annotation for texts.
 *
 * @package     stubbles
 * @subpackage  ipo_request_broker_annotations
 */
class stubTextFilterAnnotation extends stubAbstractStringFilterAnnotation
{
    /**
     * list of allowed tags
     *
     * @var  array<string>
     */
    protected $allowedTags = array();

    /**
     * set the list of allowed tags
     *
     * Use this option very careful. It does not protect you against
     * possible XSS attacks!
     *
     * @param  string  $allowedTags
     */
    public function setAllowedTags($allowedTags)
    {
        $this->allowedTags = array_map('trim', explode(',', $allowedTags));
    }

    /**
     * returns the filter defined by the annotation
     *
     * @return  stubFilter
     */
    protected function doDoGetFilter()
    {
        $textFilter = new stubTextFilter();
        $textFilter->setAllowedTags($this->allowedTags);
        return $textFilter;
    }
}
?>