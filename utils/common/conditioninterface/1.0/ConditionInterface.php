<?php
/**
 * A condition is a class that possesses an "isOk" method. The condition returns true if the condition is met,
 * and false otherwise.
 *
 */
interface ConditionInterface {
	
	/**
	 * Returns true if the condition is met, false otherwise.
	 *
	 * @param mixed $caller The condition caller. Optional.
	 * @return bool
	 */
	function isOk($caller = null);
}
?>