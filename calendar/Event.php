<?php 
class Event implements EventInterface {
	private $title;
	private $date_start;
	private $date_end;
	private $content;
	private $category;
	private $link;
	private $style_event;
	private $style_tooltip;
	
	/**
	 * Return the title
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	/**
	 * Return the start date in timestamp format
	 * @return int
	 */
	public function getDateStart() {
		return $this->date_start;
	}
	/**
	 * Return the end date in timestamp format
	 * @return int
	 */
	public function getDateEnd() {
		return $this->date_end;
	}
	/**
	 * Return the content
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}
	/**
	 * Return the category
	 * @return CategoryInterface
	 */
	public function getCategory() {
		return $this->category;
	}
	/**
	 * Return the link
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}
	/**
	 * Return the event style
	 * @return string
	 */
	public function getStyleEvent() {
		return $this->style_event;
	}
	/**
	 * Return the tooltip style
	 * @return string
	 */
	public function getStyleTooltip() {
		return $this->style_tooltip;
	}

	/**
	 * Set the title
	 * @param $title string
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	/**
	 * Set the start date
	 * @param $date_start mixed A date (YYYY-mm-dd or YYYY-mm-dd hh:ii:ss) or timestamp
	 */
	public function setDateStart($date_start) {
		if(is_numeric($date_start)) {
			$this->date_start = $date_start;
		}
		else {
			$this->date_start  = strtotime($date_start);
		}
	}
	/**
	 * Set the title
	 * @param $title string
	 */
	public function setDateEnd($date_end) {
		if(is_numeric($date_end)) {
			$date = $date_end;
		}
		else {
			$date = strtotime($date_end);
		}
////		echo $date_end." - ".$date." - ".date("His", $date);
//		if(date("His", $date) == "000000")
//			$date = mktime(23, 59, 59, date("n", $date), date("j", $date), date("Y", $date));
		$this->date_end = $date;
	}
	/**
	 * Set the title
	 * @param $title string
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	/**
	 * Set the title
	 * @param $title string
	 */
	public function setCategory($category) {
		$this->category = $category;
	}
	/**
	 * Set the title
	 * @param $title string
	 */
	public function setLink($link) {
		$this->link = $link;
	}
	/**
	 * Set the title
	 * @param $title string
	 */
	public function setStyleEvent($styleEvent) {
		$this->style_event = $styleEvent;
	}
	/**
	 * Set the title
	 * @param $title string
	 */
	public function setStyleTooltip($styleTooltip) {
		$this->style_tooltip = $styleTooltip;
	}
}

?>