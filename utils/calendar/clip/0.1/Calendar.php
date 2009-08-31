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
	private $link;
	private $events = array();
	private $categories = array();
	private $functionFormatDate;
	private $functionFormatDateTime;
	private $functionFormatHalfDate;
	private $colors = array("#FF0000", "#00FF00", "#0000FF",
							"#FFFF00", "#00FFFF", "#FF00FF",
							"#FFAAAA", "#AAFFAA", "#AAAAFF",
							"#FFFFAA", "#AAFFFF", "#FFAAFF");
	private $months = array("", "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");
	private $daysSmall = array("", "mon", "tue", "wed", "thu", "fri", "sat", "sun");
	private $days = array("", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
	private $colorUse = 0;
	private $url;
	private $functionTranslate = null;
	private $language = "en";
	private $width;
	private $height;
	
	/**
	* Constructor
	*/
	function __construct() {
		// Retrieve calendar where calendar is displayed
		$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		if($pos = strpos($url, "?"))
			$url = substr($url, 0, $pos);
		$this->setUrl($url);
		
		$this->setDate(mktime());
		
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
		
		$this->setHeight(600);
		$this->setWidth(null);
		
		$this->setLanguage("en");
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * 
	 * @param $function
	 * @return unknown_type
	 */
	public function setTranslateFunction($function) {
		$this->translate_function = $function;
	}

	public function setType($type) {
		if(is_null($type)) {
			if(isset($_REQUEST["type"])) {
				$this->type = $_REQUEST["type"];
			}
			else
				$this->type = "year";
		}
		else
			$this->type = $type;
	}

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
	
	public function setYear($year) {
		$this->year = $year;
	}
	
	public function setMonth($month) {
		$this->month = $month;
	}

	public function setDay($day) {
		$this->day = $day;
	}

	public function setWeek($week) {
		$timestamp = $this->mondayInWeek($week, $this->year);
		$this->yearWeek = date("Y", $timestamp);
		$this->monthWeek = date("n", $timestamp);
		$this->dayWeek = date("j", $timestamp);
	}

	public function setWidth($width) {
		$this->width = $width;
	}

	public function setHeight($height) {
		if($height)
			$this->height = $height;
	}
	
	/**
	* Set a call back function to display the date in your format (year, month and day)
	* @param function string function name
	*/
	public function setFormatDateFunction($function) {
		$this->functionformatDate = $function;
	}

	/**
	* Set a call back function to display the date and time in your format (year, month, day, hour, minute and second)
	* @param function string function name
	*/
	public function setFormatDateTimeFunction($function) {
		$this->functionformatDateTime = $function;
	}

	/**
	* Set a call back function to display a part of date in your format (only month and day)
	* @param function string function name
	*/
	public function setFormatHalfDateFunction($function) {
		$this->functionformatHalfDate = $function;
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
	
	public function setCalendarDataSource($dataSource) {
//		foreach ($dataSource->getCategories() as $category)
//			$this->addCategory($category);
		foreach ($dataSource->getEvents() as $event)
			$this->addEvent($event);
//			echo "<br /><br />";
//			var_dump($this->events);
//			echo "<br /><br />";
	}
	
	public function getWidth() {
		if(is_null($this->width))
			return "100%";
		else
			return $width."px";
	}

	public function getHeight() {
		return $this->height."px";
	}
	
	/**
	* Add an event on the calendar.
	* @param date mixed If you give only a date format, this is use to display an event on the full day. You can give an array with key start and end to make an event on many day and/or with a time start and time end. All the date can be timestamp or string format (YYYY-mm-dd hh:mm:ss).
	* @param message mixed You can give only one text, it will be used for event title. You can give a array with index title and content. They are displayed according to the type of calendar.
	* @param style[optionnal] mixed It is a css style. If you give only astring, it will be use for calendar event. You can give an array with event and tooltip.
	* @param link[optionnal] string To add a link on the title
	*/
	public function addEvent($event) {
		if(($date_start = $event->getDateStart()) && ($date_end = $event->getDateEnd())) {
			list($date_start, $time_start, $init_start) = $this->getDateTimeOfData($date_start);
			list($date_end, $time_end, $init_end) = $this->getDateTimeOfData($date_end);
			$timeStart = $this->getTimeOfTimestamp($time_start);
			$timeEnd = $this->getTimeOfTimestamp($time_end);
//			echo $event->getTitle()." - ".$timeStart." - ".$timeEnd." - ".$time_end."<br />";
			if($timeStart == $timeEnd && $timeStart == "00:00")
				$time_end = mktime(23, 59, 59, date("n", $date_end), date("j", $date_end), date("Y", $date_end));
//			echo $event->getTitle()." - ".$time_end."<br />";
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
									<td class="calendar_main_table_link">
										<a href="'.$this->url.'?type=month&year='.$this->year.'">'.$this->translate("link.month").'</a><br />
										<a href="'.$this->url.'?type=week&year='.$this->year.'">'.$this->translate("link.week").'</a>
									</td>
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
	

	private function displayMonthForYear($month, $year, $positions) {
		$first = date("N", $this->getDateOf(null, $month, 1));
		$day_nbr = date("t", $this->getDateOf(null, $month, 1));
		$return = '<table class="calendar_year_table_month" style="width: 100%; height: 100%;">
					<tr style="height: 10%">
						<th colspan="8"><a href="'.$this->url.'?type=month&month='.$month.'&year='.$this->year.'" class="calendar_year_table_month_title">'.ucfirst($this->translate($this->months[$month])).'</a></th>
					</tr>
					<tr style="height: 3%">
						<th style="width: 4%;"></th>
						<th style="width: 12%;" class="calendar_year_table_month_mon">'.$this->daysSmall[1].'</th>
						<th style="width: 12%;" class="calendar_year_table_month_tue">'.$this->daysSmall[2].'</th>
						<th style="width: 12%;" class="calendar_year_table_month_wed">'.$this->daysSmall[3].'</th>
						<th style="width: 12%;" class="calendar_year_table_month_thu">'.$this->daysSmall[4].'</th>
						<th style="width: 12%;" class="calendar_year_table_month_fri">'.$this->daysSmall[5].'</th>
						<th style="width: 12%;" class="calendar_year_table_month_sat">'.$this->daysSmall[6].'</th>
						<th style="width: 12%;" class="calendar_year_table_month_sun">'.$this->daysSmall[7].'</th>
					</tr>';
		$pos = "";
		foreach ($positions as $position) {
			$pos .= " calendar_tooltip_pos_year_".$position;
		}
		
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
	
	private function displayDayForYear($date, $positions) {
		$return = "";
		if(isset($this->events[$date])) {
			ksort($this->events[$date]);
			$first = true;
			$count = 0;
			$return_temp = "";
			foreach ($this->events[$date] as $key_time => $event_time) {
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
			$return .= '
							<div class="calendar_tooltip calendar_tooltip_pos_year'.$positions.'">
									<sup class="count">'.$count.'</sup>'.date("j", $date)
								.'<span>
									<div class="calendar_tooltip_year_date">'.$this->formatHalfDate($date).'</div>
									'.$return_temp.'
								</span>
							</div>';
		}
		else
			$return = date("j", $date);
		return $return;
	}
	

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
			$link = '<a href="'.$event->getLink().'">';
		else
			$link = "";
			
		$return .= '<div style="'.$style_cat.$style.'">'.$link.$event->getTitle().($link?'</a>':'').'</div>';
		
		$return .= $this->eventDateText($event);
		
		$return .= "</div>";
		return $return;
	}
	
	/********************************* By Month *********************************/
	private function drawMonth() {
		$first = date("N", $this->getDateOf(null, null, 1));
		$day_nbr = date("t", $this->getDateOf(null, null, 1));
		
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
									<th style="text-transform: capitalize; font-size: 18px">'.$this->months[$this->month].'</th>
									<td style="text-align: right">';
									if($this->month == 12)
										$return .= '<a href="'.$this->url.'?type=month&month=1&year='.($this->year + 1).'" >=&gt;</a>';
									else
										$return .= '<a href="'.$this->url.'?type=month&month='.($this->month + 1).'&year='.$this->year.'" >=&gt;</a>';
									
									$return .= '</td>
									<td style="width: 30%; text-align: right"><a href="'.$this->url.'?type=week&week='.$week.'">Lien par semaine</a></td>
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
									<td style="text-align: right"><a href="'.$this->url.'?type=year&year='.$this->year.'">Lien par annee</a></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr style="height: '.($this->height*5/100).'px">
						<th style="width: 4%;"></th>
						<th style="width: 12%;">'.$this->days[1].'</th>
						<th style="width: 12%;">'.$this->days[2].'</th>
						<th style="width: 12%;">'.$this->days[3].'</th>
						<th style="width: 12%;">'.$this->days[4].'</th>
						<th style="width: 12%;">'.$this->days[5].'</th>
						<th style="width: 12%;">'.$this->days[6].'</th>
						<th style="width: 12%;">'.$this->days[7].'</th>
					</tr>';
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
					$return .= '<td style="'.$style.'border: 1px solid black; vertical-align: top; padding: 0px;">'.$k;
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
		for($j = $i; $j < 7; $j ++)
			$return .= '<tr style="height: '.($this->height*14/100).'px"><td colspan="8">&nbsp;</td></tr>';
		$return .= "</table>";
		return $return;
	}
	
	private function displayEventsForMonth($date, $element, $positions) {
		$return = "";
		if(isset($this->events[$date])) {
			$return .= '<div>
						<div style="height: '.(($this->height*14/100) - 12).'px; overflow: auto;">';
			ksort($this->events[$date]);
			foreach ($this->events[$date] as $key_time => $event_time) {
				if(isset($this->events[$date][$key_time]["parent"])) {
					foreach ($this->events[$date][$key_time]["parent"] as $event_parent) {
						$return .= $this->displayOneEventForMonth($event_parent, $date, $positions, $element);
					}
				}
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
		$return .= '<div style="'.$style.'" class="calendar_event '.$class.'">';
		if($element == "start")
			$return .= '<span style="font-size: 9px; float: left"><=&nbsp;&nbsp;</span>';
		if($element == "end")
			$return .= '<span style="font-size: 9px; float: right">&nbsp;&nbsp;=></span>';
		$class = "";
		foreach($positions as $position)
			$class .= " calendar_tooltip_pos_month_$position";
		$return .= '<div class="calendar_tooltip calendar_tooltip_pos_month'.$class.'" style="'.$style.'">'.$event->getTitle();
		$return .= '<span style="z-index: 2; '.$style_tooltip.'">';
		if($category) {
			$return .= '<div style="font-size: 9px; text-align: right">'.$category->getName()."</div>";
		}
		$return .= $event->getTitle();
		
		$return .= "<br />".$this->eventDateText($event);
		
		if($content = $event->getContent())
			$return .= "<br /><br />".$content;
		$return .= '</span></div>';
		$return .= "</div>";
		return $return;
	}
	
	/***** By Week *****/
	private function drawWeek() {
		$days = array("", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
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
									<th style="text-transform: capitalize; font-size: 18px">Semaine '.$week.'</th>
									<td style="text-align: right">';
									$week_temp =date("W", $this->getDateForWeek(null, 12, 31));
									if($week_temp == 1)
										$week_temp = 52;
										
									if($week == $week_temp)
										$return .= '<a href="'.$this->url.'?type=week&week=1&year='.($year + 1).'" >=&gt;</a>';
									else
										$return .= '<a href="'.$this->url.'?type=week&week='.($week + 1).'&year='.$year.'" >=&gt;</a>';
									
									$return .= '</td>
									<td style="width: 30%; text-align: right"><a href="'.$this->url.'?type=month&month='.$this->monthWeek.'">Lien par mois</a></td>
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
									<td style="text-align: right"><a href="'.$this->url.'?type=year&year='.$year.'">Lien par annee</a></td>
								</tr>
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
		for($i = 0; $i <= 23; $i ++) {
				$return .= '<tr style="height: 19px">
							<td style="width: 4%; vertical-align: top; text-align: center; border-top: 1px solid #DDDDDD;">'.$i.':00</td>
							<td style="border-top: 1px solid #DDDDDD;">&nbsp;</td>
							<td style="width: 4%; vertical-align: top; text-align: center; border-top: 1px solid #DDDDDD;">'.$i.':00</td>
						</tr>
						<tr style="height: 19px">
							<td style="border-top: 1px dotted #DDDDDD; border-bottom: 1px solid #DDDDDD;"></td>
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
		for($i = 1; $i <= 7; $i ++) {
			$day = $this->getDateForWeek($this->yearWeek, null, $this->dayWeek + $i - 1);
			$style = "";
			if($day == $this->getDateNow())
				$style = "border: 1px solid orange;";
			$return .= "<th style='width: 13%; height: 30px; $style'>".$this->translate($days[date("N", $day)])." ".$this->formatHalfDate($day)."</th>";
			$return_temp .= '<td style="height: 960px; position: relative; padding: 0px 4px 0px 2px; border-left: 1px dashed #DDDDDD; border-right: 1px dashed #DDDDDD;"><div style="position: relative; height: 960px; ">'.$this->displayDayForWeek($day)."</div></td>";
		}
		$return_temp .= '<td style="width: 4%;"></td>
						</tr>';
		$return .= '<td style="width: 4%;"></td>
					</tr>'.$return_temp.'</table></div></td></tr></table>';
		return $return;
	}
	
	private function displayDayForWeek($date) {
		$events = array();
		if(isset($this->events[$date])) {
			ksort($this->events[$date]);
			foreach ($this->events[$date] as $key_time => $event_time) {
				if(isset($this->events[$date][$key_time]["parent"])) {
					foreach ($this->events[$date][$key_time]["parent"] as $event_parent) {
//						$event = $this->events[$event_parent["date"]][$event_parent["time"]]["main"][$event_parent["position"]];
						$events[] = $event_parent;
					}
				}
				if(isset($this->events[$date][$key_time]["main"])) {
					foreach ($this->events[$date][$key_time]["main"] as $event) {
						$events[] = $event;
					}
				}
			}
		}
//		var_dump($events);
		$eventPosition = $this->checkEventSuperposition($events);
//		echo "event pos $date <br />";
//		var_dump($eventPosition);
		
		$return = $this->displayEventDayPositionForWeek($date, $eventPosition);
		
		return $return;
	}
	
	private function checkEventSuperposition(&$events) {
		$eventPosition = array();
		foreach ($events as $event) {
			if(count($eventPosition) == 0) {
				$eventPosition[0][] = $event;
			}
			else {
				$i = 0;
				$added = false;
				while($i <= count($eventPosition) && !$added) {
					if($eventPosition[$i]) {
						$j = 0;
						$continue = true;
						while($j < count($eventPosition[$i]) && $continue) {
//							echo $j."<br />";
//							var_dump($eventPosition[$i][$j]);
//							if($event["time_start"] < $eventPosition[$i][$j]["time_end"]) {
//							if($this->getTimeOfTimestamp($event->getDateStart()) < $this->getTimeOfTimestamp($eventPosition[$i][$j]->getDateEnd())) {
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
	
	private function displayEventDayPositionForWeek($date, $events_position) {
//		krsort($events);
		$date_start = $date;
		$date_end = $this->addOneDay($date) - 1;
		$nbr_col = count($events_position);
		$return = "";
		$coef = 961/(60*24);
		$width = (100 / ($nbr_col + 1)) * 2;
		foreach ($events_position as $col => $event_col) {
			$left = 100 * $col / ($nbr_col + 1);
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
			$arrow_top .= '<div style="float: left;">&#8648;</div>';
		}
		
		
		if($height < 20) {
			$tooltip .= '<span style="'.$style_tooltip.'">'.$arrow_top.$event->getTitle()."<br />".$this->eventDateText($event, true).'<br /><br />'.$event->getContent().'</span>';
			$return_content .= "<br />";
			$class = "calendar_event_week_tooltip";
		}
		elseif($height < 40) {
			$tooltip .= '<span style="'.$style_tooltip.';">'.$event->getTitle()."<br />".$this->eventDateText($event, true).'<br /><br />'.$event->getContent().'</span>';
			$return_content .= ''.$arrow_top.$event->getTitle();
//			$return_content .= '<a style="background-color: rgb(255, 0, 0); margin-left: 3px; margin-right: 3px;" class="calendar_tooltip tooltip_pos_month" href="#">Info matin<span style="background-color: red;"><div style="font-size: 9px; text-align: right;">Cat 1</div>Info matin<br/>from_h 01:00 to_h 01:15</span></a>';
			$class = "calendar_event_week_tooltip";
		}
		else {
			$tooltip .= "";
			$return_content .= '<div style="overflow: hidden; width: 100%; height: 100%">';
			$return_content .= $arrow_top.$event->getTitle();
			$return_content .= '<div style="height: 3px; font-size: 3px">&nbsp;</div>';
			$return_content .= $this->eventDateText($event, true);
			$return_content .= "<br />";
			$return_content .= $event->getContent();
			$return_content .= '</div>';
//			$class = "calendar_week_event_all_day";
		}
		//class="calendar_week_event '.$class.' cal_test"
		$return = '<div class="calendar_week_event '.$class.' cal_test" style="position: absolute; left: '.$left.'%; top: '.$top.'px; width: '.$width.'%; height: '.$height.'px; border: 1px solid black; background-color: yellow; word-wrap: break-word;'.$style.'">';
		if(!$end) {
			$return .= '<div style="position: absolute; bottom: 0px; left: 0px">&#8650;</div>';
		}
		$return .= '<div style="overflow: hidden; width: 100%; height: 100%">';
		$return .= $return_content;
		$return .= '</div>';
		$return .= $tooltip;
		$return .= '</div>';
		return $return;
	}
	
	
	
	
	
	
	
	
	
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
	
	
	public function displayLegend() {
		ksort($this->categories);
		$return = "<table>";
		foreach ($this->categories as $category) {
			$return .= '<tr>
							<td style="width: 30px;'.$category["style"]["event"].'">&nbsp;</td>
							<td>'.$category["name"].'</td>
						</tr>';
		}
		$return .= '</table>';
		return $return;
	}
	
	
	private function addOneDay($date) {
		$mktime = mktime(0, 0, 0, date("n", $date), date("j", $date)+1, date("Y", $date));
		return $mktime;
	}

	private function removeOneDay($date) {
		$mktime = mktime(0, 0, 0, date("n", $date), date("j", $date)-1, date("Y", $date));
		return $mktime;
	}
	
	private function getTimeOfTimestamp($date) {
		return date("H:i", $date);
	}

	private function getDateOfTimestamp($date, $timestamp = false) {
		if($timestamp)
			return mktime(null, null, null, date("n", $date), date("d", $date), date("Y", $date));
		else
			return date("Y/m/d", $date);
	}

	private function getDateForWeek($year = null, $month = null, $day = null) {
		if(is_null($year))
			$year = $this->yearWeek;
		if(is_null($month))
			$month = $this->monthWeek;
		if(is_null($day))
			$day = $this->dayWeek;
		$return = $year."-".$month."-".$day;
		return mktime(null, null, null, $month, $day, $year);
	}
	
	private function getDateOf($year = null, $month = null, $day = null) {
		if(is_null($year))
			$year = $this->year;
		if(is_null($month))
			$month = $this->month;
		if(is_null($day))
			$day = $this->day;
		$return = $year."-".$month."-".$day;
		return mktime(null, null, null, $month, $day, $year);
	}
	
	private function getDateNow() {
		return mktime(null, null, null, date("n"), date("j"), date("Y"));
	}

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
	
	private function mondayInWeek($weeknb, $year) {
		$start = date("N", mktime(null, null, null, 1, 1, $year));
		if($start <= 4)
			$value = mktime(0, 0, 0, 1, ($weeknb - 1) * 7 - $start + 2, $year);
		else
			$value = mktime(0, 0, 0, 1, $weeknb * 7 - $start + 2, $year);
		return $value;
//		return $year;
	}
	
	private function formatDate($date) {
		if($this->formatDate)
			return call_user_func($this->formatDate, $date);
		else
			return date("Y-m-d", $date);
	}

	private function formatHalfDate($date) {
		if($this->formatHalfDate)
			return call_user_func($this->formatHalfDate, $date);
		else
			return date("m/d", $date);
	}
	
	private function formatDateTime($date) {
		if($this->formatDateTime)
			return call_user_func($this->formatDateTime, $date);
		else
			return date("Y/m:d H:i", $date);
	}
	
	private function translate($message) {
		if($this->translateFunction)
			return call_user_func($this->translateFunction, $message);
		elseif($this->language)
			if(isset($this->calendarLanguage[$message]))
				return $this->calendarLanguage[$message];
		return $message;
	}
}
?>