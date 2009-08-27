<?php
/**
 * Interface for the date span classes.
 * 
 * @author      Ingo Sobolewski <ingo.sobolewski@1und1.de>
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan
 */
/**
 * Interface for the date span classes.
 * 
 * @package     stubbles
 * @subpackage  util_datespan
 */
interface stubDateSpan extends stubObject, stubSerializable
{
    /**
     * datespan interval: day
     */
    const INTERVAL_DAY   = 'day';
    /**
     * datespan interval: week
     */
    const INTERVAL_WEEK  = 'week';
    /**
     * datespan interval: month
     */
    const INTERVAL_MONTH = 'month';

    /**
     * returns the start date
     * 
     * @return  DateTime
     * @see     http://php.net/manual/en/function.date-create.php
     */
    public function getStartDate();

    /**
     * returns the end date
     * 
     * @return  DateTime
     * @see     http://php.net/manual/en/function.date-create.php
     */
    public function getEndDate();

    /**
     * returns the spans between the start date and the end date
     * 
     * @return  array<stubDateSpan>
     */
    public function getDateSpans();

    /**
     * returns a string representation of the date object
     * 
     * @return  string
     */
    public function toString();

    /**
     * checks whether the DateSpan is in the future
     * 
     * @return  bool
     */
    public function isFuture();
}
?>