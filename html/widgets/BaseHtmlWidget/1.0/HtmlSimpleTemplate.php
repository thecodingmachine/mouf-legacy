<?php
/**
 * This class is used to insert HTML elements into a another template HTML element.
 * 
 * @author David
 * @Component
 */
class HtmlSimpleTemplate extends AbstractHtmlElement {
	
	/**
	 * The base element (template) in which we will insert additional elements.
	 * 
	 */
	private $template;
	
	/**
	 * The elements to insert into the template.
	 * The key is the name, the value the element to insert.
	 * For instance, if the key is "CONTENT", the class will look for a string "CONTENT" in the main template and replace it with the HTML element.
	 * 
	 * @Property
	 * @Compulsory
	 * @var array<string, HtmlElementInterface>
	 */
	public $insertedElements = array();
	
	/**
	 * Renders the generated HTML in a string.
	 *
	 */
	public function getHtml() {
		$templateContent = self::getHtmlElement($this->template);
		
		foreach ($this->insertedElements as $key => $elem) {
			$templateContent = str_replace($key, self::getHtmlElement($elem), $templateContent);
		}
		
		return $templateContent;
	}
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	public function toHtmlElement() {
		echo $this->getHtml();
	}
	
	private static function getHtmlElement(HtmlElementInterface $element) {
		ob_start();
		$element->toHtml();
		$content = ob_get_clean();
		return $content;
	}
	
	/**
	 * The base element (template) in which we will insert additional elements.
	 * 
	 * @Property
	 * @Compulsory
	 * @var HtmlElementInterface
	 */
	public function setTemplate(HtmlElementInterface $template) {
		$this->template = $template;
	}
	
	/**
	 * The base element (template) in which we will insert additional elements.
	 * 
	 * @return HtmlElementInterface
	 */
	public function getTemplate() {
		return $this->template;
	}
	
	/**
	 * Adds a new HTML element to be put in the template.

	 * @param string $key The key of the element
	 * @param HtmlElementInterface $element The element
	 */
	public function putElement($key, HtmlElementInterface $element) {
		$this->insertedElements[$key] = $element;
	}
	
	/**
	 * Puts a PHP file at the requested position
	 * 
	 * @param string $key
	 * @param string $fileName
	 * @param Scopable $scope
	 * @return HtmlSimpleTemplate
	 */
	public function putFile($key, $fileName, $scope = null) {
		$elem = new HtmlFromFile();
		$elem->fileName = $fileName;
		$elem->scope = $scope;
		$elem->relativeToRootPath = true;
		$this->insertedElements[$key] = $elem;
		return $this;
	}
	
	/**
	 * Puts some content at the requested position by calling the function passed in parameter.
	 * 
	 * @param string $key
	 * @param callback $function
	 * @return HtmlSimpleTemplate
	 */
	public function putFunction($key, $function) {
		$arguments = func_get_args();
		// Remove the first argument
		array_shift($arguments);

		$content = new HtmlFromFunction();
		$content->functionPointer = $function;
		$content->parameters = $arguments;
		$this->insertedElements[$key] = $content;
		return $this;
	}

	/**
	 * 
	 * Puts the text passed in parameter at the requested position.
	 * 
	 * @param string $key
	 * @param string $text
	 * @return HtmlSimpleTemplate
	 */
	public function putText($key, $text) {
		$content = new HtmlString();
		$content->htmlString = $text;
		$this->insertedElements[$key] = $content;
		return $this;
	}
}
?>