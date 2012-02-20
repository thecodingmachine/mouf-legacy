<?php
interface FieldDescriptorInterface{
	
	/**
	 * Returns the name of the field.
	 * This name is unique for the whole form,
	 * and is used by default for the name and id attributes.
	 * Enter description here ...
	 */
	public function getFieldName();
	
	/**
	 * The field descriptor must have a renderer that
	 * is is charge of rendering HTML code of the field inside the form
	 * Enter description here ...
	 */
	public function getRenderer();
	
	/**
	 * The field descriptor may have a validator 
	 * @Property
	 * @return ValidatorInterface
	 */
	public function getValidator();
	
	/**
	 * The value of the field is set into the descriptor when form is loaded
	 * @param mixed $value
	 */
	public function setFieldValue($value);
	
	/**
	 * The getter of the descriptor's value, that is given to the renderer at form's display
	 * Enter description here ...
	 */
	public function getFieldValue();
	
	/**
	* This function is called when the Form's bean has been intantiated,
	* and links the descriptor's value to mainbean's property
	*
	* @param mixed $mainBean
	*/
	public function load($mainBean);

	/**
	 * Gets the Label of the field as displayed in the form
	 * @return string
	 */
	public function getFieldLabel();
	
	
}