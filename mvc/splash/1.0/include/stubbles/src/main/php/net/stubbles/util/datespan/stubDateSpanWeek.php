<?php
/**
 * Datespan that represents a week.
 *
 * @author      Ingo Sobolewski <ingo.sobolewski@1und1.de>
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanCustom');
/**
 * Datespan that represents a week.
 *
 * @package     stubbles
 * @subpackage  util_datespan
 */
class stubDateSpanWeek extends stubDateSpanCustom implements stubDateSpan
{
    /**
     * constructor
     * 
     * @param  string|DateTime  $date      start date of the week
     * @param  string           $interval  optional  interval of the span
     */
    public function __construct($date, $interval = stubDateSpan::INTERVAL_DAY)
    {
        if (($date instanceof DateTime) == false) {
            $date = new DateTime($date);
        }
        
        $end = clone $date;
        $end->modify('+ 6 days');
        parent::__construct($date, $end, $interval);
    }
}
?>