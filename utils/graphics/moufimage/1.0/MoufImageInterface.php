<?php
interface MoufImageInterface{

	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return $image, the GD resource image
	 */
	public function getResource();
	
}