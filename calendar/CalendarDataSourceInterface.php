<?php 
/**
 * Any class implementing this interface can be used by the calendar for categories and events
 * @author Marc
 *
 */
interface CalendarDataSourceInterface {
	/**
	 * Return the event list
	 * @return array<EventInterface>
	 */
	function getEvents();
	/**
	 * Return the category list
	 * @return array<CategoryInterface>
	 */
	function getCategories();
	
}
?>