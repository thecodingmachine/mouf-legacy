<?php

/**
 * A FalseCondition is a condition that is always "false".
 *
 * @Component
 */
class FalseCondition implements ConditionInterface {
	
	/**
	 * Returns false, always.
	 *
	 * @param mixed $caller The condition caller. Optional.
	 * @return bool
	 */
	public function isOk($caller = null) {
		return false;
	}
}
?>