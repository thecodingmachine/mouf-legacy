<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

/**
 * Interface that allows a class to execute PHP files inside its scope.
 *
 */
interface Scopable {
	
	/**
	 * Loads the file.
	 *
	 * @param string $file
	 */
	public function loadFile($file);
}
?>