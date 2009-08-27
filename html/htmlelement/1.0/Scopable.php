<?php
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