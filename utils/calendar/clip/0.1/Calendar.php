<?php 
require_once("EventInterface.php");
require_once("CalendarDataSourceInterface.php");
require_once("CategoryInterface.php");

/**
 * 
 * @author Marc Teyssier
 * @version 0.1 Beta
 *
 */
class Calendar {
	
	private $type;
	private $year;
	private $month;
	private $day;
	private $yearWeek;
	private $monthWeek;
	private $dayWeek;
	private $yearRequest;
	private $monthRequest;
	private $dayRequest;
	private $events = array();
	private $categories = array();
	private $functionFormatDate;
	private $functionFormatDateTime;
	private $functionFormatHalfDate;
	private $months = array("", "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
	private $days = array("", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
	private $url;
	private $functionTranslate = null;
	private $language = "en";
	private $calendarLanguage = array();
	private $encodeutf8 = true;
	private $width;
	private $height;
	private $typeLink = true;
	
	/**
	* Constructor
	*/
	function __construct() {
		// Retrieve calendar where calendar is displayed (url)
		$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		if($pos = strpos($url, "?"))
			$url = substr($url, 0, $pos);
		$this->setUrl($url);
		
		// Get the time now
		$this->setDate(mktime());
		
		// Retrieve data in parameter
		if(isset($_REQUEST["year"]))
			$this->setYear($_REQUEST["year"]);
		if(isset($_REQUEST["month"]))
			$this->setMonth($_REQUEST["month"]);
		if(isset($_REQUEST["week"]))
			$this->setWeek($_REQUEST["week"]);
			
		if(isset($_REQUEST["type"]))
			$this->setType($_REQUEST["type"]);
		else
			$this->setType(null);
		
		// Height by default
		$this->setHeight(600);
		// Width by default
		$this->setWidth(null);
		
		//Language by default
		$this->setLanguage("en");
	}
	
	/**
	 * Set url of calendar
	 * @param url string url of calendar
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * Display the link to change type (year, month,or day)
	 * @param $typeLink boolean true to display link or false
	 */
	public function setTypeLink($typeLink) {
		$this->typeLink = $typeLink;
	}
	
	/**
	 * Set the encodage in utf8 by default. Else the translate function use the utf8decodefunction.
	 * @param $encodeutf8 boolean true if you would use utf8 else false
	 */
	public function setEncodeutf8($encodeutf8) {
		$this->encodeutf8 = $encodeutf8;
	}
	
	/**
	 * Set a function to translate text
	 * @param function string name of your translate function
	 */
	public function setTranslateFunction($function) {
		$this->translate_function = $function;
	}

	/**
	 * Set the calendar type
	 * @param type string value of type ("year", "month" or "week"). By Default is "year"
	 */
	public function setType($type) {
		if(is_null($type)) {
			if(isset($_REQUEST["type"])) {
				$value = $_REQUEST["type"];
			}
			else
				$value = "year";
		}
		else
			$value = $type;

		if($value == "year" || $value == "month" || $value == "week")
			$this->type = $value;
		else
			$this->type = "year";
		
	}

	/**
	 * Set a date to display calendar with it
	 * @param date mixed It can be timestamp or string format (YYYY-mm-dd hh:mm:ss)
	 */
	public function setDate($date) {
		if(is_numeric($date)) {
			$timestamp = $date;
			$year = date("Y", $date);
			$month = date("n", $date);
			$day = date("j", $date);
		}
		else {
			list($date, $time) = explode(" ", $date);
			list($year, $month, $day) = explode("-", $date);
			$timestamp = strtotime($date);
		}
		
		$this->setYear($year);
		$this->setMonth($month);
		$this->setDay($day);
		
		$this->setWeek(date("W", $timestamp));
	}
	
	/**
	 * Set a year to display calendar
	 * @param $year int Set year
	 */
	public function setYear($year) {
		if($year >= 1970)
			$this->year = $year;
		else
			$this->year = 1970;
	}
	
	/**
	 * Set a month to display calendar
	 * @param $month int Set month
	 */
	public function setMonth($month) {
		if($month >= 1 && $month <= 12)
			$this->month = $month;
		else
			$this->month = 1;
	}

	/**
	 * Set a day to display calendar
	 * @param $day int Set day
	 */
	public function setDay($day) {
		if($month >= 1 && $month <= 31)
			$this->day = $day;
		else
			$this->day = 1;
	}

	/**
	 * Set a week to display calendar the year must be set before
	 * @param $week int Set week
	 */
	public function setWeek($week) {
		$timestamp = $this->mondayInWeek($week, $this->year);
		$this->yearWeek = date("Y", $timestamp);
		$this->monthWeek = date("n", $timestamp);
		$this->dayWeek = date("j", $timestamp);
	}

	/**
	 * Set the width of calendar
	 * @param $width mixed Set width, it can int or null
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * Set the height of calendar
	 * @param $height int Set height
	 */
	public function setHeight($height) {
		if($height)
			$this->height = $height;
	}
	
	/**
	* Set a call back function to display the date in your format (year, month and day)
	* @param function string function name
	*/
	public function setFormatDateFunction($function) {
		$this->functionFormatDate = $function;
	}

	/**
	* Set a call back function to display the date and time in your format (year, month, day, hour, minute and second)
	* @param function string function name
	*/
	public function setFormatDateTimeFunction($function) {
		$this->functionFormatDateTime = $function;
	}

	/**
	* Set a call back function to display a part of date in your format (only month and day)
	* @param function string function name
	*/
	public function setFormatHalfDateFunction($function) {
		$this->functionFormatHalfDate = $function;
	}

	/**
	* Retrieve the translation of calendar. This function inserts the associate file to add translation.
	* @param language string Give the language.
	*/
	public function setLanguage($language) {
		if($language) {
			$file = dirname(__FILE__)."/translation_".$language.".php";
			if(file_exists($file)) {
				require_once($file);
			}
			$this->language = $language;
		}
	}

	/**
	* Set a call back function to display the translation of calendar.
	* @param function string function name
	*/
	public function setCallBackTranslate($function) {
		if(!$function)
			return false;
		$this->callBackTranslate = $function;
		return true;
	}
	
	/**
	 * Set the calendar data source
	 * @param $dataSource <CalendarDataSourceInterface> Set data source
	 */
	public function setCalendarDataSource($dataSource) {
		$this->categories = $dataSource->getCategories();
		foreach ($dataSource->getEvents() as $event)
			$this->addEvent($event);
	}
	
	/**
	 * Get calendar width
	 * @return string return 100% or a string like [number]px
	 */
	public function getWidth() {
		if(is_null($this->width))
			return "100%";
		else
			return $width."px";
	}

	/**
	 * Get calendar height
	 * @return string return a string like [number]px
	 */
	public function getHeight() {
		return $this->height."px";
	}
	

	/**
	 * Get the information of the link for type is displayed
	 * @return boolean true if the link is displayed, else false
	 */
	public function getTypeLink() {
		return $this->typeLink;
	}
	
	/**
	 * Get the information of used encodage. True if is utf8, else false
	 * @return boolean true if utf8, else false
	 */
	public function getEncodeutf8() {
		return $this->encodeutf8;
	}
	
	/**
	 * Return the first date display in calendar (it can be used to make a data base request
	 * @param $date bool If you want a string set date at true (YYYY-mm-dd hh:ii:ss), else set false (by default) to return a timastamp
	 * @return mixed return a date. In timestamp if $date is false (by default) or string (YYYY-mm-dd hh:ii:ss)
	 */
	public function getStartDate($date = false) {
		if($this->type == "year")
			$timestamp = mktime(0, 0, 0, 1, 1, $this->year);
		elseif($this->type == "month")
			$timestamp = mktime(0, 0, 0, $this->month, 1, $this->year);
		else
			$timestamp = mktime(0, 0, 0, $this->monthWeek, $this->dayWeek, $this->yearWeek);
		
		if($date)
			return date("Y-m-d H:i:s", $timestamp);
		else
			return $timestamp;
	}

	/**
	 * Return the end date display in calendar (it can be used to make a data base request
	 * @param $date bool If you want a string set date at true (YYYY-mm-dd hh:ii:ss), else set false (by default) to return a timastamp
	 * @return mixed return a date. In timestamp if $date is false (by default) or string (YYYY-mm-dd hh:ii:ss)
	 */
	public function getEndDate($date = false) {
		if($this->type == "year")
			$timestamp = mktime(23, 59, 59, 12, 31, $this->year);
		elseif($this->type == "month")
			$timestamp = mktime(23, 59, 59, $this->month + 1, 0, $this->year);
		else
			$timestamp = mktime(23, 59, 59, $this->monthWeek, $this->dayWeek + 7, $this->yearWeek);
		
		if($date)
			return date("Y-m-d H:i:s", $timestamp);
		else
			return $timestamp;
	}
	
	/**
	* Add an event on the calendar.
	* @param event <EventInterface> Set an event
	*/
	public function addEvent($event) {
		if(($date_start = $event->getDateStart()) && ($date_end = $event->getDateEnd())) {
			list($date_start, $time_start, $init_start) = $this->getDateTimeOfData($date_start);
			list($date_end, $time_end, $init_end) = $this->getDateTimeOfData($date_end);
			$timeStart = $this->getTimeOfTimestamp($time_start);
			$timeEnd = $this->getTimeOfTimestamp($time_end);
			if($timeStart == $timeEnd && $timeStart == "00:00")
				$time_end = mktime(23, 59, 59, date("n", $date_end), date("j", $date_end), date("Y", $date_end));
		}
		else {
			list($date_start, $time_start, $init_start) = $this->getDateTimeOfData($date_start);
			$time_start = mktime(0, 0, 0, date("n", $date_start), date("j", $date_start), date("Y", $date_start));
			$date_end = $date_start;
			$time_end = mktime(23, 59, 59, date("n", $date_start), date("j", $date_start), date("Y", $date_start));
		}
		if($date_start < $date_end || ($date_start == $date_end && $time_start <= $time_end)) {
			if(isset($this->events[$date_start][$time_start]["main"]))
				$count = count($this->events[$date_start][$time_start]["main"]);
			else
				$count = 0;
			$this->events[$date_start][$time_start]["main"][$count] = $event;

			$this->fillEventOnPeriod($date_start, $time_start, $date_end, $time_end, $count, $event);
		}
	}

	/**
	 * Display legend, category name and color
	 * @return string HTML code to display 
	 */
	public function displayLegend() {
		$return = "<table>";
		foreach ($this->categories as $category) {
			$return .= '<tr>
							<td style="width: 30px;'.$category->getStyleCategory().'">&nbsp;</td>
							<td>'.$category->getName().'</td>
						</tr>';
		}
		$return .= '</table>';
		return $return;
	}
	
	/**
	 * Set the event on each day on its period
	 * @param $date_start timestamp first second of the start date
	 * @param $time_start timestamp time of start date
	 * @param $date_end timestamp last second of the end date
	 * @param $time_end timestamp time of end date
	 * @param $position int position for its element. It is used to display many event to the same day
	 * @param $event <EventInterface> The event object
	 */
	private function fillEventOnPeriod($date_start, $time_start, $date_end, $time_end, $position, $event) {
		$date = $this->addOneDay($date_start);
		while($date <= $date_end) {
			if(isset($this->events[$date][$time_start]["parent"]))
				$count = count($this->events[$date][$time_start]["parent"]);
			else
				$count = 0;
			$this->events[$date][$time_start]["parent"][$count] = $event;
			$date = $this->addOneDay($date);
		}
	}

	/**
	 * Return a HTML code to display. You must use echo or print to display the calendar
	 * @return string HTML code to display
	 */
	public function draw() {
		$div_start = '<div class="calendar_main" style="position: relative; width: '.$this->getWidth().'; height: '.$this->getHeight().'">';
		$div_end = '</div>';
		if($this->type == "week") {
			return $div_start.$this->drawWeek().$div_end;
		}
		elseif($this->type == "month") {
			return $div_start.$this->drawMonth().$div_end;
		}
		else {
			return $div_start.$this->drawYear().$div_end;
		}
	}
	
	/********************************* By Year *********************************/
	/**
	 * Make table to display the calendar by year 
	 * @return string HTML code to display
	 */
	private function drawYear() {
		$return = '<table class="calendar_main_table" style="width: 100%; height: 100%">
					<tr class="calendar_main_table_header">
						<td colspan="4">
							<table style="width: '.$this->getWidth().'">
								<tr>
									<td class="calendar_main_table_link"></td>
									<td class="calendar_main_table_arrow_left">';
									$return .= '<a href="'.$this->url.'?type=year&year='.($this->year - 1).'" >&lt;=</a>';
									$return .= '</td>
									<td class="calendar_main_table_title">'.$this->year.'</td>
									<td class="calendar_main_table_arrow_right">';
									$return .= '<a href="'.$this->url.'?type=year&year='.($this->year + 1).'" >=&gt;</a>';
									$return .= '</td>
									<td class="calendar_main_table_link">';
									if($this->typeLink) {
										$return .= '<a href="'.$this->url.'?type=week&year='.$this->year.'">'.$this->translate("link.week").'</a><br />
											<a href="'.$this->url.'?type=month&year='.$this->year.'">'.$this->translate("link.month").'</a>';
									}
									$return .= '</td>
								</tr>
							</table>
						</td>
					</tr>';
		for($i = 1; $i <= 12; $i ++) {
			if(($i - 1) % 4 == 0)
				$return .= '<tr style="height: 31%;">';
			$return .= '<td style="width: 25%;">';
			$positions = array();
			if($i >= 1 && $i <= 4)
				$positions[].= "top";
			if(($i + 3) % 4 == 0)
				$positions[] = "left";
			if($i % 4 == 0)
				$positions[] = "right";
			if($i >= 9 && $i <= 12)
				$positions[] = "bottom";
			$return .= $this->displayMonthForYear($i, $this->year, $positions);
			$return .= "</td>";
			if($i % 4 == 0)
				$return .= "</tr>";
		}
		$return .= "</table>";
		return $return;
	}
	
	/**
	 * Make HTML code for one month
	 * @return string HTML code to display a month for year calendar
	 */
	private function displayMonthForYear($month, $year, $positions) {
		$first = date("N", $this->getDateOf(null, $month, 1));
		$day_nbr = date("t", $this->getDateOf(null, $month, 1));
		// Table for one month
		$return = '<table class="calendar_year_table_month" style="width: 100%; height: 100%;">
					<tr style="height: 10%">
						<th colspan="8"><a href="'.$this->url.'?type=month&month='.$month.'&year='.$this->year.'" class="calendar_year_table_month_title">'.ucfirst($this->translate($this->months[$month])).'</a></th>
					</tr>
					<tr style="height: 3%">
						<th style="width: 4%;"></th>
						<th style="width: 12%;" class="calendar_year_table_month_mon">'.ucfirst(substr($this->translate($this->days[1]), 0, 3)).'</th>
						<th style="width: 12%;" class="calendar_year_table_month_tue">'.ucfirst(substr($this->translate($this->days[2]), 0, 3)).'</th>
						<th style="width: 12%;" class="calendar_year_table_month_wed">'.ucfirst(substr($this->translate($this->days[3]), 0, 3)).'</th>
						<th style="width: 12%;" class="calendar_year_table_month_thu">'.ucfirst(substr($this->translate($this->days[4]), 0, 3)).'</th>
						<th style="width: 12%;" class="calendar_year_table_month_fri">'.ucfirst(substr($this->translate($this->days[5]), 0, 3)).'</th>
						<th style="width: 12%;" class="calendar_year_table_month_sat">'.ucfirst(substr($this->translate($this->days[6]), 0, 3)).'</th>
						<th style="width: 12%;" class="calendar_year_table_month_sun">'.ucfirst(substr($this->translate($this->days[7]), 0, 3)).'</th>
					</tr>';
		$pos = "";
		foreach ($positions as $position) {
			$pos .= " calendar_tooltip_pos_year_".$position;
		}
		
		// TR and TD for the day in month
		$k = 1;
		for($i = 1; $i <= ceil(($first+$day_nbr-1)/7); $i ++) {
			$week = (int)(date("W", $this->getDateOf(null, $month, 1+($i-1)*7)));
			$return .= '<tr style="height: 13%">
							<td class="calendar_year_table_week">
								<a href="'.$this->url.'?type=week&week='.$week.'&year='.$this->year.'">'.$week.'</a>
							</td>';
			for($j = 1; $j <= 7; $j++) {
				if(($k != 1 || $j == $first) && $k <= $day_nbr) {
					$style = "";
					$class = "";
					if($this->getDateNow() == $this->getDateOf(null, $month, $k))
						$class = " calendar_year_table_today";
					
					// Display events for a day
					$event = $this->displayDayForYear($this->getDateOf(null, $month, $k), $pos);
					if(!is_numeric($event))
						$class .= " calendar_year_table_day_event";
					$return .= '<td class="calendar_year_table_day'.$class.'" style="'.$style.'">'
									.$event.
								'</td>';
					$k ++;
				}
				else
					$return .= "<td></td>";
			}
			$return .= '</tr>';
		}
		for($j = $i; $j < 7; $j ++)
			$return .= '<tr style="height: 13%;"><td colspan="8">&nbsp;</td></tr>';
		$return .= '</table>';
		return $return;
	}
	
	/**
	 * Prepare the day to add event
	 * @param $date of day in timestamp
	 * @param $positions the position of the month in year (left, right, top and/or bottom)
	 * @return string HTML code to display for event in day
	 */
	private function displayDayForYear($date, $positions) {
		$return = "";
		// If there are event
		if(isset($this->events[$date])) {
			ksort($this->events[$date]);
			$first = true;
			$count = 0;
			$return_temp = "";
			// Get all event of one day
			foreach ($this->events[$date] as $key_time => $event_time) {
				// Event on many day
				if(isset($this->events[$date][$key_time]["parent"])) {
					foreach ($this->events[$date][$key_time]["parent"] as $event) {
						$count ++;
						if($first)
							$first = false;
						else
							$return_temp .= "<hr />";
						$return_temp .= $this->displayOneEventForYear($event, $date);
					}
				}
				// Event on one day and first day of event
				if(isset($this->events[$date][$key_time]["main"])) {
					foreach ($this->events[$date][$key_time]["main"] as $event) {
						$count ++;
						if($first)
							$first = false;
						else
							$return_temp .= "<hr />";
						$return_temp .= $this->displayOneEventForYear($event, $date);
					}
				}
			}
			// Display event
			$return .= '<div class="calendar_tooltip calendar_tooltip_pos_year'.$positions.'">
							<sup class="count">'.$count.'</sup>'.date("j", $date)
						.'<span class="calendar_tooltip_year">
							<div class="calendar_tooltip_year_date">'.$this->formatHalfDate($date).'</div>
								'.$return_temp.'
						</span>
					</div>';
		}
		else
			$return = date("j", $date);
		return $return;
	}
	
	/**
	 * Make HTML code for event of year
	 * @param $event Set event to show
	 * @param $date Set the date of day
	 * @return string HTML code to display
	 */
	private function displayOneEventForYear($event, $date) {
		$return = "";
		$style = "";
		$style_cat = "";
		$return .= '<div>';
		if(!is_null($event->getCategory())) {
			$category = $event->getCategory();
			$style_cat .= $category->getStyleCategory(); 
			$return .= '<div class="calendar_year_event_category" style="'.$style_cat.'">'.$category->getName()."</div>";
		}
		if(!is_null($event->getStyleEvent()))
			$style .= $event->getStyleEvent();
		if($event->getLink())
			$link = '<a href="'.$event->getLink().'" style="'.$style_cat.$style.'">';
		else
			$link = "";
			
		$return .= '<div style="'.$style_cat.$style.'">'.$link.$event->getTitle().($link?'</a>':'');
		
		$return .= '<br />'.$this->eventDateText($event).'</div>';
		
		$return .= "</div>";
		return $return;
	}
	
	/********************************* By Month *********************************/
	/**
	 * Make table to display the calendar by month 
	 * @return string HTML code to display
	 */
	private function drawMonth() {
		$first = date("N", $this->getDateOf(null, null, 1));
		$day_nbr = date("t", $this->getDateOf(null, null, 1));
		// Display the table
		$return ='<table class="calendar_main_table" style="width: 100%; height: '.$this->getHeight().'">
					<tr class="calendar_main_table_header" style="height: '.($this->height*11/100).'px">
						<td colspan="8">
							<table style="width: 100%">
								<tr>
									<td style="width: 30%"></td>
									<td style="text-align: left">';
									if($this->month == 1)
										$return .= '<a href="'.$this->url.'?type=month&month=12&year='.($this->year -1 ).'" >&lt;=</a>';
									else
										$return .= '<a href="'.$this->url.'?type=month&month='.($this->month - 1).'&year='.$this->year.'" >&lt;=</a>';
									$return .= '</td>
									<th style="text-transform: capitalize; font-size: 18px">'.$this->translate($this->months[$this->month]).'</th>
									<td style="text-align: right">';
									if($this->month == 12)
										$return .= '<a href="'.$this->url.'?type=month&month=1&year='.($this->year + 1).'" >=&gt;</a>';
									else
										$return .= '<a href="'.$this->url.'?type=month&month='.($this->month + 1).'&year='.$this->year.'" >=&gt;</a>';
									
									$return .= '</td>
									<td style="width: 30%; text-align: right">';
										if($this->typeLink) {
											$return .= '<a href="'.$this->url.'?type=week">'.$this->translate('link.week').'</a>';
										}
									$return .= '</td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: left">';
									$return .= '<a href="'.$this->url.'?type=month&month='.$this->month.'&year='.($this->year - 1).'" >&lt;=</a>';
									$return .= '</td>
									<th style="text-transform: capitalize; font-size: 18px">'.$this->year.'</th>
									<td style="text-align: right">';
									$return .= '<a href="'.$this->url.'?type=month&month='.$this->month.'&year='.($this->year + 1).'" >=&gt;</a>';
									$return .= '</td>
									<td style="text-align: right">';
										if($this->typeLink) {
											$return .= '<a href="'.$this->url.'?type=year&year='.$this->year.'">'.$this->translate('link.year').'</a>';
										}
									$return .= '</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: '.($this->height*5/100).'px">
						<th style="width: 4%;"></th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[1])).'</th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[2])).'</th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[3])).'</th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[4])).'</th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[5])).'</th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[6])).'</th>
						<th style="width: 12%;">'.ucfirst($this->translate($this->days[7])).'</th>
					</tr>';
									
		// Display all day of month
		$k = 1;
		for($i = 1; $i <= ceil(($first+$day_nbr-1)/7); $i ++) {
			$week = (int)(date("W", $this->getDateOf(null, null, 1+($i-1)*7)));
			$return .= '<tr style="height: '.($this->height*14/100).'px"><td style="vertical-align: middle"><a href="'.$this->url.'?type=week&week='.$week.'&year='.$this->year.'">'.$week.'</a></td>';
			
			for($j = 1; $j <= 7; $j++) {
				if(($k != 1 || $j == $first) && $k <= $day_nbr) {
					if($k == 1)
						$element = "start";
					elseif($k == $day_nbr)
						$element = "end";
					else
						$element = null;
					$style = "";
					if($this->getDateNow() == $this->getDateOf(null, null, $k))
						$style = "background-color: orange;";
					$return .= '<td style="'.$style.'border: 1px solid black; vertical-align: top; padding: 0px;">
								<span class="calendar_month_day_num">'.$k.'</span>';
					$positions = array();
					if($i == 1)
						$positions[] = "top";
					if($j == 1)
						$positions[] = "left";
					if($j == 7 || $j ==6)
						$positions[] = "right";
					if($i == 6)
						$positions[] = "bottom";
					$return .= $this->displayEventsForMonth($this->getDateOf(null, null, $k), $element, $positions);
					$return .= '</td>';
					$k ++;
				}
				else
					$return .= "<td></td>";
			}
			$return .= "</tr>";
		}
		// Fill all empty day
		for($j = $i; $j < 7; $j ++)
			$return .= '<tr style="height: '.($this->height*14/100).'px"><td colspan="8">&nbsp;</td></tr>';
		$return .= "</table>";
		return $return;
	}
	
	/**
	 * Prepare the day to add event for month
	 * @param $date timestamp Date of day in timestamp
	 * @param $element string "end", "start" or null set if the day is the first or not
	 * @param $positions array The position of the day in month (left, right, top and/or bottom)
	 * @return string HTML code to display for event in day
	 */
	private function displayEventsForMonth($date, $element, $positions) {
		$return = "";
		if(isset($this->events[$date])) {
			$return .= '<div>
						<div style="height: '.(($this->height*14/100) - 17).'px; overflow: auto;">';
			ksort($this->events[$date]);
			// Check all event of day
			foreach ($this->events[$date] as $key_time => $event_time) {
				// Event on many day
				if(isset($this->events[$date][$key_time]["parent"])) {
					foreach ($this->events[$date][$key_time]["parent"] as $event_parent) {
						$return .= $this->displayOneEventForMonth($event_parent, $date, $positions, $element);
					}
				}
				// Event on one day and first day of event
				if(isset($this->events[$date][$key_time]["main"])) {
					foreach ($this->events[$date][$key_time]["main"] as $event) {
						$return .= $this->displayOneEventForMonth($event, $date, $positions);
					}
				}
			}
			$return .= '</div>';
			$return .= '</div>';
		}
		return $return;
	}

	
	/**
	 * Make HTML code for event of year
	 * @param $event <EventInterface> Set event to show
	 * @param $date timestamp Set the date of day
	 * @param $positions array The position of the day in month (left, right, top and/or bottom)
	 * @param $element string "end", "start" or null set if the day is the first or not
	 * @return unknown_type
	 */
	private function displayOneEventForMonth($event, $date, $positions, $element = null) {
		$style = "";
		$class = "";
		$style_tooltip = "";
		$return = "";
		if($category = $event->getCategory()) {
			if($styleTemp = $category->getStyleCategory())
				$style .= $styleTemp.";";
			if($styleTemp = $category->getStyleTooltip())
				$style_tooltip .= $styleTemp.";";
		}
		if($styleTemp = $event->getStyleEvent())
			$style .= $styleTemp.";";
		if($styleTemp = $event->getStyleTooltip())
			$style_tooltip .= $styleTemp.";";
		$dateStart = $this->getDateOfTimestamp($event->getDateStart(), true);
		$dateEnd = $this->getDateOfTimestamp($event->getDateEnd(), true);
		
		if($date == $dateStart)
			$class .= "calendar_month_day_event_start ";
		if($date == $dateEnd)
			$class .= "calendar_month_day_event_end ";
		if($linkTemp = $event->getLink())
			$link = $linkTemp;
		else
			$link = "#";
		$return .= '<div class="calendar_event '.$class.'" style="'.$style.'">';
		if($element == "start")
			$return .= '<span style="font-size: 9px; float: left"><=&nbsp;&nbsp;</span>';
		if($element == "end")
			$return .= '<span style="font-size: 9px; float: right">&nbsp;&nbsp;=></span>';
		$class = "";
		foreach($positions as $position)
			$class .= " calendar_tooltip_pos_month_$position";
		if($link = $event->getLink())
			$alink = '<a href="'.$link.'">';
		$return .= '<div class="calendar_tooltip calendar_tooltip_pos_month calendar_tooltip_month'.$class.'">'.$event->getTitle();
		$return .= '<span style="z-index: 2; '.$style_tooltip.'">';
		if($category) {
			$return .= '<div style="font-size: 9px; text-align: right">'.$category->getName()."</div>";
		}
		$return .= $alink.$event->getTitle().($alink?"</a>":"");
		
		$return .= "<br />".$this->eventDateText($event);
		
		if($content = $event->getContent())
			$return .= "<br /><br />".$content;
		$return .= '</span></div>';
		$return .= "</div>";
		return $return;
	}
	
	/*********************************** By Week *****************************/
	/**
	 * Make table to display the calendar by week 
	 * @return string HTML code to display
	 */
	private function drawWeek() {
		// Prepare the table of week
		// The first table contain 2 tables one for display grid with hour and one with events
		$return = '<table style="width: 100%; height: '.$this->getHeight().'">
					<tr style="height: 42px">
						<td colspan="8">
							<table style="width: 100%">
								<tr>
									<td style="width: 30%"></td>
									<td style="text-align: left">';
									$week = (int)date("W", $this->getDateForWeek());
									if($week == 1) {
										$first_day = date("N", $this->getateForWeek($this->year_request, 1, 1));
										if(4 >= $first_day && $first_day != 1)
											$year = $this->yearWeek + 1;
										else
											$year = $this->yearWeek;
									}
									else
										$year = $this->yearWeek;
									if($week == 1) {
										$week_temp = date("W", $this->removeOneDay($this->mondayInWeek(1, $year)));
										$return .= '<a href="'.$this->url.'?type=week&week='.$week_temp.'&year='.($year -1 ).'" >&lt;=</a>';
									}
									else
										$return .= '<a href="'.$this->url.'?type=week&week='.($week - 1).'&year='.$year.'" >&lt;=</a>';
									$return .= '</td>
									<th style="text-transform: capitalize; font-size: 18px">'.$this->translate('week').' '.$week.'</th>
									<td style="text-align: right">';
									$week_temp =date("W", $this->getDateForWeek(null, 12, 31));
									if($week_temp == 1)
										$week_temp = 52;
										
									if($week == $week_temp)
										$return .= '<a href="'.$this->url.'?type=week&week=1&year='.($year + 1).'" >=&gt;</a>';
									else
										$return .= '<a href="'.$this->url.'?type=week&week='.($week + 1).'&year='.$year.'" >=&gt;</a>';
									
									$return .= '</td>
									<td style="width: 30%; text-align: right"><a href="'.$this->url.'?type=month&month='.$this->monthWeek.'">';
										if($this->typeLink) {
											$return .= $this->translate('link.month');
										}
									$return .= '</a></td>
								</tr>
								<tr>
									<td></td>
									<td style="text-align: left">';
									if($week == 53) {
										$week_add = date("W", $this->removeOneDay($this->mondayInWeek(1, $year + 2)));
										if($week_add == 1)
											$week_add = 52;
										$week_remove = date("W", $this->removeOneDay($this->mondayInWeek(1, $year - 1)));
										if($week_remove == 1)
											$week_remove = 52;
									}
									else{
										$week_add = $week;
										$week_remove = $week;
									}
									$return .= '<a href="'.$this->url.'?type=week&week='.$week_remove.'&year='.($year - 1).'" >&lt;=</a>';
									$return .= '</td>
									<th style="text-transform: capitalize; font-size: 18px">'.$year.'</th>
									<td style="text-align: right">';
									$return .= '<a href="'.$this->url.'?type=week&week='.$week_add.'&year='.($year + 1).'" >=&gt;</a>';
									$return .= '</td>
									<td style="text-align: right"><a href="'.$this->url.'?type=year&year='.$year.'">';
										if($this->typeLink) {
											$return .= $this->translate('link.year').'</a></td>';
										}
								$return .= '</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div style="overflow-x: hidden; overflow-y: scroll; height: '.($this->height-42).'px; position: relative">
								<table style="width: 100%; height: 992px; position: absolute; top: 0px; left: 0px">
									<tr style="height: 31px">
										<td style="width: 4%;">&nbsp;</td>
										<td style=""></td>
										<td style="width: 4%;">&nbsp;</td>
									</tr>';
		// Display all hour
		for($i = 0; $i <= 23; $i ++) {
				$return .= '<tr>
							<td style="width: 4%; vertical-align: top; text-align: center; border-top: 1px solid #DDDDDD; height: 19px">'.$i.':00</td>
							<td style="border-top: 1px solid #DDDDDD;">&nbsp;</td>
							<td style="width: 4%; vertical-align: top; text-align: center; border-top: 1px solid #DDDDDD;">'.$i.':00</td>
						</tr>
						<tr>
							<td style="height: 19px; border-top: 1px dotted #DDDDDD; border-bottom: 1px solid #DDDDDD;"></td>
							<td style="border-top: 1px dotted #DDDDDD; border-bottom: 1px solid #DDDDDD;"></td>
							<td style="border-top: 1px dotted #DDDDDD; border-bottom: 1px solid #DDDDDD;"></td>
						</tr>';
		}
		$return .= '</table>
					<table style="width: 100%; height: 990px"><tr>
							<td style="width: 4%;">&nbsp;</td>';
		$return_temp = '<tr>
							<td style="width: 4%;">
								&nbsp;';
		
		$return_temp .= '</td>';
		// Display all day in week
		for($i = 1; $i <= 7; $i ++) {
			$day = $this->getDateForWeek($this->yearWeek, null, $this->dayWeek + $i - 1);
			$style = "";
			if($day == $this->getDateNow())
				$style = "border: 1px solid orange;";
			$return .= "<th style='width: 13%; height: 30px; $style'>".ucfirst(substr($this->translate($this->days[date("N", $day)]), 0, 3))." ".$this->formatHalfDate($day)."</th>";
			$return_temp .= '<td style="height: 960px; position: relative; padding: 0px 4px 0px 2px; border-left: 1px dashed #DDDDDD; border-right: 1px dashed #DDDDDD;">
								<div style="position: relative; height: 960px; ">'.$this->displayDayForWeek($day)."</div></td>";
		}
		$return_temp .= '<td style="width: 4%;"></td>
						</tr>';
		$return .= '<td style="width: 4%;"></td>
					</tr>'.$return_temp.'</table></div></td></tr></table>';
		return $return;
	}
	
	/**
	 * Prepare the day to add event for week
	 * @param $date timestamp Date of day
	 * @return string HTML code to display day for week
	 */
	private function displayDayForWeek($date) {
		$events = array();
		if(isset($this->events[$date])) {
			ksort($this->events[$date]);
			// Get all event of one day
			foreach ($this->events[$date] as $key_time => $event_time) {
				// Event on many day
				if(isset($this->events[$date][$key_time]["parent"])) {
					foreach ($this->events[$date][$key_time]["parent"] as $event_parent) {
						$events[] = $event_parent;
					}
				}
				// Event on one day and first day of event
				if(isset($this->events[$date][$key_time]["main"])) {
					foreach ($this->events[$date][$key_time]["main"] as $event) {
						$events[] = $event;
					}
				}
			}
		}
		$eventPosition = $this->checkEventSuperposition($events);
		$return = $this->displayEventDayPositionForWeek($date, $eventPosition);
		
		return $return;
	}
	
	/**
	 * Check all event to detect if many event are on the same day
	 * @param $events array<EventInterface> List of all event
	 * @return array with event and its position
	 */
	private function checkEventSuperposition(&$events) {
		$eventPosition = array();
		// Check all event
		foreach ($events as $event) {
			if(count($eventPosition) == 0) {
				$eventPosition[0][] = $event;
			}
			else {
				$i = 0;
				$added = false;
				// Check the position empty
				while($i <= count($eventPosition) && !$added) {
					if(isset($eventPosition[$i])) {
						$j = 0;
						$continue = true;
						while($j < count($eventPosition[$i]) && $continue) {
							if($event->getDateStart() < $eventPosition[$i][$j]->getDateEnd()) {
								$continue = false;
							}
							$j ++;
						}
						if($continue) {
							$eventPosition[$i][$j] = $event;
							$added = true;
						}
					}
					else {
						$eventPosition[$i][] = $event;
						$added = true;
					}
					$i ++;
				}
			}
		}
		return $eventPosition;
	}
	
	/**
	 * Prepare the size of event with its position and duration
	 * @param $date timestamp Date of day in week
	 * @param $events_position array of <EventInterface> with position in index
	 * @return string HTML code to display day for week
	 */
	private function displayEventDayPositionForWeek($date, $events_position) {
		$date_start = $date;
		$date_end = $this->addOneDay($date) - 1;
		$nbr_col = count($events_position);
		$return = "";
		$coef = 961/(60*24);
		$width = (100 / ($nbr_col + 1)) * 2;
		// Check all event
		foreach ($events_position as $col => $event_col) {
			$left = 100 * $col / ($nbr_col + 1);
			// Prepare event
			foreach ($event_col as $row => $event) {
				$timeStart = $event->getDateStart();
				$timeEnd = $event->getDateEnd();
				if($this->getTimeOfTimestamp($timeEnd) == "00:00")
					$timeEnd = $this->addOneDay($timeEnd) - 1;
				if($timeStart < $date && $timeEnd > $date_end) {
					$return .= $this->displayEventDayForWeek($event, $left, 0, $width, 960);
				}
				elseif($timeStart < $date && $timeEnd <= $date_end) {
					$height = floor(($timeEnd - $date)/60*$coef);
					$return .= $this->displayEventDayForWeek($event, $left, 0, $width, $height, false, true);
				}
				elseif($timeStart >= $date && $timeEnd > $date_end) {
					$position = floor(($timeStart - $date)/60*$coef);
					$return .= $this->displayEventDayForWeek($event, $left, $position, $width, (960 - $position), true, false);
				}
				elseif($timeStart >= $date && $timeEnd <= $date_end) {
					$position = floor(($timeStart - $date)/60*$coef);
					$height = floor(($timeEnd - $timeStart)/60*$coef);
					$return .= $this->displayEventDayForWeek($event, $left, $position, $width, $height, true, true);
				}
			}
		}
		return $return;
	}
	
	/**
	 * Display event for week
	 * @param $event <EventInterface> Event to display
	 * @param $left int Position of the event from the left side of the table
	 * @param $top int Position of the event from the top side of the table
	 * @param $width int Width of the event according event number on the day
	 * @param $height int Height of the event according with duration of event
	 * @param $start int Position to start the display of event
	 * @param $end int Position to end the display of event
	 * @return string HTML code to display event of day for a week
	 */
	private function displayEventDayForWeek($event, $left, $top, $width, $height, $start = false, $end = false) {
		$style = "";
		$style_tooltip = "";
		if($category = $event->getCategory()) {
			if($styleTemp = $category->getStyleCategory())
				$style .= $styleTemp.";";
			if($styleTemp = $category->getStyleTooltip())
				$style_tooltip .= $styleTemp.";";
		}
		if($styleTemp = $event->getStyleEvent())
			$style .= $styleTemp.";";
		if($styleTemp = $event->getStyleTooltip())
			$style_tooltip .= $styleTemp.";";
			
		$return_content = "";
		$tooltip = "";
		$class = "";
		if(!$start) {
			$arrow_top = '<div style="float: left;">&uarr;</div>';
		}
		else {
			$arrow_top = "";
			$class .= " calendar_event_week_start";
		}
		if(!$end) {
			$arrow_bottom = '<div style="position: absolute; bottom: 0px; left: 0px">&darr;</div>';
		}
		else {
			$arrow_bottom = "";
			$class .= " calendar_event_week_end";
		}
		
		if($link = $event->getLink())
			$alink = '<a href="'.$link.'" style="'.$style.$style_tooltip.'">';
		else
			$alink = '';
			
		if($height < 20) {
			$tooltip .= '<span style="'.$style_tooltip.'">'.$arrow_top.$alink.$event->getTitle().($alink?"</a>":"")."<br />".$this->eventDateText($event, true).'<br /><br />'.$event->getContent().'</span>';
			$return_content .= "<br />";
			$class .= " calendar_event_week_tooltip";
		}
		elseif($height < 40) {
			$tooltip .= '<span style="'.$style_tooltip.';">'.$alink.$event->getTitle().($alink?"</a>":"")."<br />".$this->eventDateText($event, true).'<br /><br />'.$event->getContent().'</span>';
			$return_content .= ''.$arrow_top.$event->getTitle();
			$class .= " calendar_event_week_tooltip";
		}
		else {
			$tooltip .= "";
			$return_content .= '<div style="overflow: hidden; width: 100%; height: 100%">';
			$return_content .= $arrow_top.$alink.$event->getTitle().($alink?"</a>":"");
			$return_content .= '<div style="height: 3px; font-size: 3px">&nbsp;</div>';
			$return_content .= $this->eventDateText($event, true);
			$return_content .= "<br />";
			$return_content .= $event->getContent();
			$return_content .= '</div>';
		}
		$return = '<div class="calendar_week_event'.$class.' cal_test" style="position: absolute; left: '.$left.'%; top: '.$top.'px; width: '.$width.'%; height: '.$height.'px; border: 1px solid black; background-color: yellow; word-wrap: break-word;'.$style.'">';
		if(!$end) {
			$return .= '<div style="position: absolute; bottom: 0px; left: 0px">&darr;</div>';
		}
		$return .= '<div style="overflow: hidden; width: 100%; height: 100%">';
		$return .= $return_content;
		$return .= '</div>';
		$return .= $tooltip;
		$return .= '</div>';
		return $return;
	}
	
	/***************************** Utils to display calendar **********************/
	/**
	 * Format the event date according to the language
	 * @param $event <EventInterface> Set the event
	 * @param $all boolean Display the full date or a partial
	 * @return string A date format
	 */
	private function eventDateText($event, $all = true) {
		$return = "";
		$timeStart = $this->getTimeOfTimestamp($event->getDateStart());
		$timeEnd = $this->getTimeOfTimestamp($event->getDateEnd());
		if($this->getDateOfTimestamp($event->getDateStart()) != $this->getDateOfTimestamp($event->getDateEnd())) {
			if($timeStart != $timeEnd)
				$return .= $this->translate("from.day")." ".$this->formatHalfDate($event->getDateStart())." ".$this->translate("at")." ".$timeStart."<br />".$this->translate("from.day")." ".$this->formatHalfDate($event->getDateEnd())." ".$this->translate("at")." ".$timeEnd;
			else
				$return .= $this->translate("from.day")." ".$this->formatHalfDate($event->getDateStart())." ".$this->translate("to.day")." ".$this->formatHalfDate($event->getDateEnd());
		}
		else {
			if($timeEnd != $timeStart && !($timeStart == "00:00" && $timeEnd == "23:59")) {
				$return .= $this->translate("from.time")." ".$timeStart." ".$this->translate("to.time")." ".$timeEnd;
			}
			elseif($all) {
				$return .= ucfirst($this->translate("on.day"));
			}
		}
		return $return;
	}
	
	/**
	 * Add one day of a date 
	 * @param $date timestamp Get date
	 * @return timestamp Date with one more day
	 */
	private function addOneDay($date) {
		$mktime = mktime(0, 0, 0, date("n", $date), date("j", $date)+1, date("Y", $date));
		return $mktime;
	}

	/**
	 * Remove one day of a date 
	 * @param $date timestamp Get date
	 * @return timestamp Date with one less day
	 */
	private function removeOneDay($date) {
		$mktime = mktime(0, 0, 0, date("n", $date), date("j", $date)-1, date("Y", $date));
		return $mktime;
	}
	
	/**
	 * Get a format string of time (hour:minute)
	 * @param $date timestamp Set the timestamp to format
	 * @return string Time format (hh:mm)
	 */
	private function getTimeOfTimestamp($date) {
		return date("H:i", $date);
	}

	/**
	 * Return date without time, format timestamp or string yyyy/mm/dd
	 * @param $date timestamp Set date
	 * @param $timestamp boolean True if you would timestamp, else false for a string (by default)
	 * @return mixed string or timestamp
	 */
	private function getDateOfTimestamp($date, $timestamp = false) {
		if($timestamp)
			return mktime(null, null, null, date("n", $date), date("d", $date), date("Y", $date));
		else
			return date("Y/m/d", $date);
	}

	/**
	 * Return a timestamp for a day. If a parameter is empty, this function use attribut of week
	 * @param $year int Set a year, if the value is null, use the attribut yearWeek
	 * @param $month int Set a month, if the value is null, use the attribut monthWeek
	 * @param $day int Set a day, if the value is null, use the attribut dayWeek
	 * @return timestamp Date
	 */
	private function getDateForWeek($year = null, $month = null, $day = null) {
		if(is_null($year))
			$year = $this->yearWeek;
		if(is_null($month))
			$month = $this->monthWeek;
		if(is_null($day))
			$day = $this->dayWeek;
		return mktime(null, null, null, $month, $day, $year);
	}
	
	/**
	 * Return a timestamp for a day. If a parameter is empty, this function use attribut year, month, day
	 * @param $year int Set a year, if the value is null, use the attribut year
	 * @param $month int Set a month, if the value is null, use the attribut month
	 * @param $day int Set a day, if the value is null, use the attribut day
	 * @return timestamp Date
	 */
	private function getDateOf($year = null, $month = null, $day = null) {
		if(is_null($year))
			$year = $this->year;
		if(is_null($month))
			$month = $this->month;
		if(is_null($day))
			$day = $this->day;
		return mktime(null, null, null, $month, $day, $year);
	}
	
	/**
	 * Return the current date in timestamp
	 * @return timestamp Current date
	 */
	private function getDateNow() {
		return mktime(null, null, null, date("n"), date("j"), date("Y"));
	}

	/**
	 * Return an array with date, time and if timestamp is the first second of day
	 * @param $datetime timestamp of date time
	 * @return array timestamp date, timestamp time and boolean true if datetime is the first second of day
	 */
	private function getDateTimeOfData($datetime) {
		if(is_numeric($datetime)) {
			$date = mktime(0, 0, 0, date("n", $datetime), date("j", $datetime), date("Y", $datetime));
			$time = $datetime;
		}
		else {
			if(strpos($datetime, " ") !== false)
				list($date, $time) = explode(" ", $datetime);
			else
				$date = $datetime;
			$date = strtotime($date);
			$time = strtotime($datetime);
		}
		if($date == $time)
			$init = true;
		else
			$init = false;
		return array($date, $time, $init);
	}
	
	/**
	 * Get timestamp of the monday of week
	 * @param $week int Set a week number
	 * @param $year int Set a year
	 * @return timestamp Date of monday
	 */
	private function mondayInWeek($week, $year) {
		$start = date("N", mktime(null, null, null, 1, 1, $year));
		if($start <= 4)
			$value = mktime(0, 0, 0, 1, ($week - 1) * 7 - $start + 2, $year);
		else
			$value = mktime(0, 0, 0, 1, $week * 7 - $start + 2, $year);
		return $value;
	}
	
	/**
	 * Return string of a date. Use a calback function if functionFormatDate is set.  
	 * @param $date timestamp Set date
	 * @return string Date format
	 */
	private function formatDate($date) {
		if($this->functionFormatDate)
			return call_user_func($this->functionFormatDate, $date);
		else
			return date("Y-m-d", $date);
	}

	/**
	 * Return string of a half date (month and day). Use a calback function if functionFormatHalfDate is set.  
	 * @param $date timestamp Set date
	 * @return string Half date format
	 */
	private function formatHalfDate($date) {
		if(isset($this->functionFormatHalfDate))
			return call_user_func($this->functionFormatHalfDate, $date);
		else
			return date("m/d", $date);
	}

	/**
	 * Return string of a date time. Use a calback function if functionFormatDateTime is set.  
	 * @param $date timestamp Set date
	 * @return string Date time format
	 */
	private function formatDateTime($date) {
		if($this->functionFormatDateTime)
			return call_user_func($this->functionFormatDateTime, $date);
		else
			return date("Y/m:d H:i", $date);
	}
	
	/**
	 * Set message and returna translation. Use a callback function if translateFunction is set or file for translation or message
	 * @param $message string Set message
	 * @return string return a translation
	 */
	private function translate($message) {
		if(isset($this->translateFunction))
			$return = call_user_func($this->translateFunction, $message);
		elseif($this->language)
			if(isset($this->calendarLanguage[$message]))
				$return = $this->calendarLanguage[$message];
		if(!$return)
			$return = $message;
		if(!$this->encodeutf8)
			return utf8_decode($return);
		return $return;
	}
}
?>
