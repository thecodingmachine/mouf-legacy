<?php

FilterUtils::registerFilter("Xaja");

/**
 * The @Xaja annotation initializes Xaja before calling the action and starts the listening loop just after.
 */
class stubXajaAnnotation extends AbstractFilter
{

	/**
	 * Function to be called before the action.
	 */
	public function beforeAction() {
		$xajaController = XajaController::getInstance();
		$xajaController->init();
		$xajaController->setGlobalContext($this->controller);
	}

	/**
	 * Function to be called after the action.
	 */
	public function afterAction() {
		XajaController::getInstance()->startListening();
	}

	/**
	 * Returns the priority. The higher the priority, the earlier the beforeAction will be executed and the later the afterAction will be executed.
	 * Default priority is 50.
	 * @return int The priority.
	 */
	protected function getPriority() {
		return 999;
	}
}
?>