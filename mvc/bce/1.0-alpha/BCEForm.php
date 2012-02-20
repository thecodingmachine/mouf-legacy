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
			/* @var $descriptor FieldDescriptorInterface */
			$descriptor->load($this->baseBean);
			
			$fieldName = $descriptor->getFieldName();
			$validator = $descriptor->getValidator();
			if ($validator){
				$validator->loadRules();
				var_dump($validator);
				echo "<br/>-----------------<br/>$fieldName</br>";
				foreach ($validator->getJsRules() as $key => $rule) {
					$ruleObj->$fieldName->$key = $rule;
				}
			}
		}
		
		//Render the form
		echo json_encode($ruleObj);
		$this->renderer->render($this->fieldDescriptors);
	}
	
}