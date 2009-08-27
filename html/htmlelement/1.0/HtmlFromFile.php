<?php
require_once 'HtmlElementInterface.php';
require_once 'Scopable.php';

/**
 * This class loads a file, and displays it as Html.
 * The file loaded executes into a scope. The scope must be an object extending the Scopable property.
 * The $this keyword in the file will refer to the scope.
 * If no scope is provided, the scope will be the instance of the HtmlFromFile class (which is not very useful).
 *
 * @Component
 */
class HtmlFromFile implements HtmlElementInterface {
	
	/**
	 * The PHP file to be executed.
	 *
	 * @Property
	 * @Compulsory 
	 * @var string
	 */
	public $fileName;
	
	/**
	 * The scope of the file to be executed.
	 *
	 * @Property
	 * @var Scopable
	 */
	public $scope;
	
	
	public function toHtml() {
		if ($this->scope != null) {
			$this->scope->loadFile($this->fileName);
		} else {
			// TODO: improve this with relative / absolute filename support.
			require $this->fileName;
		}
	}
}
?>