<?php
/**
 * This class is the simpliest FieldDescriptor:
 * it handles a field that has no "connections" to other objects (
 * as user name or login for example)
 * @Component
 */
class FieldDescriptor{

	/**
	 * Name of the field. This value must remain unique inside a form,
	 * it will be used for name and id attributes.
	 * @Property
	 * @var string
	 */
	public $fieldName;

	/**
	 * Optional formatter that will display a formatted value (example 2012-01-30 --> 01/30/2012).
	 * The formatter is also responsible for the reverse operation (01/30/2012 --> 2012-01-30).
	 * @Property
	 * @var FormatterInterface
	 */
	public $formatter;

	/**
	 * The label of the field as displayed in the form
	 * @Property
	 * @var string
	 */
	public $label;

	/**
	 * The renderer that will be responsible for delivering the HTML for that field
	 * @Property
	 * @var FieldRendererInterface
	 */
	public $renderer;

	/**
	 * The validator of the field. Returns true/(false+errorString)
	 * @Property
	 * @var array<ValidatorInterface>
	 */
	public $validators;
	
	public function toHtml(){
		echo $this->getRenderer()->render($this);
	}

	/**
	 * Get's the field's name (unique Id of the field inside a form (or name attribute)
	 */
	public function getFieldName(){
		return $this->fieldName;
	}

	/**
	 * Returns the Renderer for that bean
	 */
	public function getRenderer(){
		return $this->renderer;
	}

	/**
	 * Returns the list of Validators of this field
	 */
	public function getValidators(){
		return $this->validators;
	}

	/**
	 * Returns the label of the field
	 */
	public function getFieldLabel(){
		return $this->label;
	}

	/**
	 * Returns the Formatter of the field
	 */
	function getFormatter(){
		return $this->formatter;
	}
}