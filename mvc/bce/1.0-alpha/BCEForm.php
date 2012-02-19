<?php
/**
 * 
 * Enter description here ...
 * @Component
 * @author Kevin
 *
 */
class BCEForm{
	
	/**
	 * @Property 
	 * @var array<FieldDescriptorInterface>
	 */
	public $fieldDescriptors;
	
	/**
	 * @Property
	 * @var DAOInterface
	 */
	public $mainDAO;
	
	/**
	 * @Property
	 * @var BCERenderer
	 */
	public $renderer;
	
	/**
	 * Load the main bean of the Form, and then the linked descriptors to display bean values
	 * @param mid $id: The id of the bean (may be null for new objects)
	 */
	public function load($id = null){
		//Intantiate form's main bean (like JAVA Spring's formBindingObject)
		$this->baseBean = $id ? $this->mainDAO->getById($id) :  $this->mainDAO->getNew();
		
		//Load bean values into related field Descriptors
		foreach ($this->fieldDescriptors as $descriptor) {
			$descriptor->load($this->baseBean);
		}
		
		//Render the form
		$this->renderer->render($this->fieldDescriptors);
	}
	
}