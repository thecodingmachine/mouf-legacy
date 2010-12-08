<?php
/**
 * The controller displaying the PHP Info page.
 *
 * @Component
 */
class PhpInfoController extends Controller {
	
	/**
	 * Displays the PHP info page.
	 * 
	 * @Action
	 * @Logged
	 */
	public function defaultAction() {
		echo phpinfo();
	}
}
?>