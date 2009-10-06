<?php
interface GridExpanderInterface{
	
	/**
	 * Retrieve expand data to display under a row following it's Id
	 * @param $id: the id of the object we need to get the expand data
	 * @return string: the HTML of the expanded row
	 */
	public function getExpandData($id);
	
}