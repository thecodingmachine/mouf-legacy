<?php
/**
 * Datespan with a custom start and end date.
 *
 * @author      Ingo Sobolewski <ingo.sobolewski@1und1.de>
 * @author      Frank Kleine <frank.kleine@1und1.de>
 * @package     stubbles
 * @subpackage  util_datespan
 */
stubClassLoader::load('net::stubbles::util::datespan::stubDateSpan');
/**
 * Datespan with a custom start and end date.
 *
 * @package     stubbles
 * @subpackage  util_datespan
 */
class stubDateSpanCustom extends stubSerializableObject implements stubDateSpan
{
    /**
     * start date of the span
     * 
     * @var  DateTime
     * @see  http://php.net/manual/en/function.date-create.php
     */
    protected $from;
    /**
     * end date of the span
     * 
     * @var  DateTime
     * @see  http://php.net/manual/en/function.date-create.php
     */
    protected $to;
    /**
     * The interval of the span (e.g. day, week, month)
     * 
     * @var  string
     * @see  DateSpan::INTERVAL_*
     */
    protected $interval;

    /**
     * constructor
     * 
     * @param  string|DateTime  $from      start date of the span
     * @param  string|DateTime  $to        end date of the span
     * @param  string           $interval  optional  interval of the span
     */
    public function __construct($from, $to, $interval = stubDateSpan::INTERVAL_DAY)
    {
        if (($from instanceof DateTime) == false) {
            $from = new DateTime($from);
        }
        
        if (($to instanceof DateTime) == false) {
            $to = new DateTime($to);
        }
        
        $this->from     = $from;
        $this->to       = $to;
        $this->interval = $interval;
    }

    /**
     * returns the start date
     * 
     * @return  DateTime
     * @see     http://php.net/manual/en/function.date-create.php
     */
    public function getStartDate()
    {
        return $this->from;
    }

    /**
     * returns the end date
     * 
     * @return  DateTime
     * @see     http://php.net/manual/en/function.date-create.php
     */
    public function getEndDate()
    {
        return $this->to;
    }

    /**
     * returns the spans between the start date and the end date
     * 
     * @return  array<stubDateSpan>
     */
    public function getDateSpans()
    {
        $spans = array();
        switch ($this->interval) {
            case stubDateSpan::INTERVAL_DAY:
                stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanDay');
                $day   = clone $this->from;
                $end   = $this->to->format('U');
                while ($day->format('U') <= $end) {
                    $spans[] = new stubDateSpanDay(clone $day);
                    $day->modify('+1 day');
                }
                break;
        
            case stubDateSpan::INTERVAL_WEEK:
                stubClassLoader::load('net::stubbles::util::datespan::stubDateSpanWeek');
                $day   = clone $this->from;
                $end   = $this->to->format('U');
                while ($day->format('U') <= $end) {
                    $spans[] = new stubDateSpanWeek(clone $day);
                    $day->modify('+7 days');
                }
                break;
                
            default:
                // intentionally empty
        }
        
        return $spans;
    }

    /**
     * returns a string representation of the date object
     * 
     * @return  string
     */
    public function toString()
    {
        return $this->from->format('d.m.Y') . ' bis ' . $this->to->format('d.m.Y');
    }

    /**
     * checks whether the DateSpan is in the future
     * 
     * @return  bool
     */
    public function isFuture()
    {
        $today = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        if ($this->from->format('U') > $today) {
            return true;
        }
        
        return false;
    }

    /**
     * takes care of serializing the value
     *
     * @param  array   &$propertiesToSerialize  list of properties to serialize
     * @param  string  $name                    name of the property to serialize
     * @param  mixed   $value                   value to serialize
     */
    protected function __doSerialize(&$propertiesToSerialize, $name, $value)
    {
        if ('from' == $name || 'to' == $name) {
            $this->_serializedProperties[$name] = $value->format('c');
            return;
        }
        
        parent::__doSerialize($propertiesToSerialize, $name, $value);
    }

    /**
     * takes care of unserializing the value
     *
     * @param  string  $name             name of the property
     * @param  mixed   $serializedValue  value of the property
     */
    protected function __doUnserialize($name, $serializedValue)
    {
        if ('from' == $name || 'to' == $name) {
            $this->$name = new DateTime($serializedValue);
            return;
        }
        
        parent::__doUnserialize($name, $serializedValue);
    }
}
?>