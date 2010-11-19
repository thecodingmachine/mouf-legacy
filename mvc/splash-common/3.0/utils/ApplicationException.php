<?php
/**
 * Application Exception should be extended by any exception thrown in Controllers.
 *
 */
class ApplicationException extends Exception {

	private $title;
	private $inner_exception;

	private $title_array = array();
	private $message_array = array();

	/**
	 * Two constructors for this exception:
	 *
	 * 	__construct($title=null, $message=null,$e=null)
	 * 	__construct(Exception $e)
	 */
	public function __construct($title=null, $message=null,$e=null) {

		if ($title instanceof Exception) {
			$this->inner_exception = $title;
			return;
		}

		$this->title = $title;
		$this->message = $message;

		$this->inner_exception = $e;

	}

	public function getTitle() {
		return $this->title;
	}
	public function getInnerException() {
		return $this->inner_exception;
	}

	/**
	 * The setTitle function sets the title (as an internationalized string, and any parameter to be passed).
	 */
	public function setTitle($title) {
		$args = func_get_args();

		$this->title = array_shift($args);
		$this->title_array = $args;
	}

	/**
	 * The setMessage function sets the message (as an internationalized string, and any parameter to be passed).
	 */
	public function setMessage($message) {
		$args = func_get_args();

		$this->message = array_shift($args);
		$this->message_array = $args;
	}

	/**
	 * @return string Returns the title, internationalized
	 */
	public function getI18Title() {
		// FIXME: internationalization enabled
		return $this->title;
		//return call_user_func_array("iMsg", array_merge(array($this->title), $this->title_array));
	}

	/**
	 * @return string Returns the message, internationalized
	 */
	public function getI18Message() {
		// FIXME: internationalization enabled
		return $this->getMessage();
		//return call_user_func_array("iMsg", array_merge(array($this->getMessage()), $this->message_array));
	}
}
?>