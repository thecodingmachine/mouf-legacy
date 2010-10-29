<?php

/**
 * A TrueCondition is a condition that is always "true".
 *
 * @Component
 */
class TrueCondition implements ConditionInterface {
	
	/**
	 * Returns true, always.
	 *
	 * @param mixed $caller The condition caller. Optional.
	 * @return bool
	 */
	public function isOk($caller = null) {
		return true;
	}
}
?>