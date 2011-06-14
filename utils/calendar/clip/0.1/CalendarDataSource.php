<?php 
/**
 * Any class implementing this interface can be used by the calendar for categories and events
 * @author Marc
 *
 */
class CalendarDataSource implements CalendarDataSourceInterface {
	private $events = array();
	private $categories = array();
	
	/**
	 * Add event to the data source
	 * @param $title string Event title
	 * @param $date_start mixed The date of the event start (YYYY-mm-dd or YYYY-mm-dd hh:ii:ss) or timestamp
	 * @param $date_end mixed The date of the event end (YYYY-mm-dd or YYYY-mm-dd hh:ii:ss) or timestamp
	 * @param $content string The event content
	 * @param $category <CategoryInterface> Add a category for your event
	 * @param $link string A ling to your event
	 * @param $style_event string A css style of your event
	 * @param $style_tooltip string A css style of the tooltip event
	 */
	function addEvent($title, $date_start, $date_end = null, $content = null, $category = null, $link = null, $style_event = null, $style_tooltip = null) {
		$event = new Event();
		$event->setTitle($title);
		$event->setDateStart($date_start);
//		echo "<hr />";
		if(is_null($date_end))
			$event->setDateEnd($date_start);
		else
			$event->setDateEnd($date_end);
//		echo $title."<br />";
//		echo "start".$date_start."<br />";
//		echo "end".$date_end."<br />";
//		echo $event->getDateEnd()."<br />";
		$event->setContent($content);
		$event->setCategory($category);
		$event->setLink($link);
		$event->setStyleEvent($style_event);
		$event->setStyleTooltip($style_tooltip);
		$this->events[] = $event;
		return $event;
	}
	
	/**
	 * Add category to the data source
	 * @param string $name string Category name
	 * @param $id mixed You can add a id to simplify the element search (like a database id).
	 * @param $style_category string A css style of your category
	 * @param $style_tooltip string A css style of the tooltip category
	 */
	function addCategory($name, $id = null, $style_category = null, $style_tooltip = null) {
		$category = new Category();
		$category->setName($name);
		$category->setId($id);
		$category->setStyleCategory($style_category);
		$category->setStyleTooltip($style_tooltip);
		$this->categories[] = $category;
		return $category;
	}
	
	/**
	 * Search a category with its name
	 * @param string $name string Name searched
	 * @return <CategoryInterface> or null
	 */
	function getCategoryByName($name) {
		foreach ($this->categories as $category) {
			if($category->getName == $name)
				return $category;
		}
		return null;
	}

	/**
	 * Search a category with its id
	 * @param string $name mixed Id searched
	 * @return <CategoryInterface> or null
	 */
	function getCategoryById($id) {
		foreach ($this->categories as $category) {
			if($category->getId == $name)
				return $category;
		}
		return null;
	}
	
	/**
	 * Return the event list
	 * @return array<EventInterface>
	 */
	public function getEvents() {
		return $this->events;
	}
	/**
	 * Return the category list
	 * @return array<CategoryInterface>
	 */
	function getCategories() {
		return $this->categories;
		
	}
	
}
?>