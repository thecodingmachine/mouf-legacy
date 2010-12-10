<?php

/**
 * This interface should be implemented by any controller that can be accessed for full-text search.
 * 
 * @author David
 */
interface MoufSearchable {
	
	/**
	 * Outputs HTML that will be displayed in the search result screen.
	 * If there are no results, this should not return anything.
	 * 
	 * @Action
	 * @param string $query The full-text search query performed.
	 * @param string $selfedit Whether we are in self-edit mode or not.
	 * @return string The HTML to be displayed.
	 */
	public function search($query, $selfedit = "false");
	
	/**
	 * Returns the name of the search module.
	 * This name in displayed when the search is pending.
	 * 
	 * @return string
	 */
	public function getSearchModuleName();
}