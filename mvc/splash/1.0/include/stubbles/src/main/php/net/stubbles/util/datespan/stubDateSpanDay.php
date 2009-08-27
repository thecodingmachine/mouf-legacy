<?php
/**
 * Datespan that represents a single day.
 *
 * @author      Ingo Sobolewski <ingo.sobolewski@1und1.de>
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanCustom');
/**
 * Datespan that represents a single day.
 *
 * @package     stubbles
 * @subpackage  util_datespan
 */
class stubDateSpanDay extends stubDateSpanCustom implements stubDateSpan
{
    /**
     * constructor
     * 
     * @param  string|DateTime  $day  optional  day that the span covers
     */
    public function __construct($day = null)
    {
        if (null === $day) {
            $day = new DateTime();
        } elseif ('yesterday' === $day) {
            $day = new DateTime($day);
        }
        
        parent::__construct($day, $day);
    }
    
    /**
     * returns the spans between the start date and the end date
     * 
     * @return  array<stubDateSpan>
     */
    public function getDateSpans()
    {
        return array($this);
    }
    
    /**
     * returns a string representation of the date object
     * 
     * @return  string
     */
    public function toString()
    {
        return $this->from->format('l, d.m.Y');
    }
}
?>