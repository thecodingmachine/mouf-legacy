<?php
/**
 * The base controller for Mouf (when the "mouf/" url is typed).
 *
 * @Component
 */
class MoufRootController extends Controller {
	
	/**
	 * The default action will redirect to the MoufController defaultAction.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction() {
		header("Location: ".ROOT_URL."mouf/validate/");
	}
}
?>