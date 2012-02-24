<?php
/**
 * 
 * Root Object of the BCE package.<br/>
 * <br/>
 * This component composes of:<br/>
 * <ul>
 *   <li>a main DAO, that will perform data access and persistence</li>
 *   <li>a set of fied descriptors, that define the fields of the form</li> 
 *   <li>a renderer that will generate the form's HTML output (sort of a template)</li>
 *   <li>a javascript validation handler that will generate the client side validation script</li>
 * </ul>
 * @Component
 * @author Kevin
 *
 */
class BCEForm{
	
	/**
	 * Field Decriptors define which fields avaiable through the main DAO should be involved in the form.<br/>
	 * They define a lot of data<br/>
	 * 
	 * @Property 
	 * @var array<FieldDescriptorInterface>
	 */
	public $fieldDescriptors;
	
	/**
	 * Field Decriptors of the bean's identifier.
	 * This is a special field because the id will help to retrive bean before saving it.
	 * 
	 * @Property 
	 * @var FieldDescriptorInterface
	 */
	public $idFieldValidator;
	
	/**
	 * The DAO reponsible of retrieving bean data and persist them
	 * 
	 * @Property
	 * @var DAOInterface
	 */
	public $mainDAO;
	
	/**
	 * The template used to render the form
	 * 
	 * @Property
	 * @var BCERenderer
	 */
	public $renderer;
	
	/**
	 * This object is responsible of generating the javascript validation code of the fields and the form 
	 * 
	 * @Property
	 * @var JsValidationHandlerInterface
	 */
	public $validationHandler;
	
	/**
	 * The validation JS scripts of the form
	 * @var array<string>
	 */
	public $validationJS;
	
	/**
	 * The action attribute of the form 
	 * 
	 * @var string
	 */
	public $action = "save";
	
	/**
	 * The submit method
	 * 
	 * @Property
	 * @var string
	 */
	public $method = "POST";
	
	/**
	 * The name attribute of the form
	 * 
	 * @Property
	 * @var string
	 */
	public $name = "default_form";
	
	/**
	 * The id attribute of the form
	 * 
	 * @Property
	 * @var string
	 */
	public $id = "default_id";
	
	/**
	 * Load the main bean of the Form, and then the linked descriptors to display bean values
	 * @param mid $id: The id of the bean (may be null for new objects)
	 */
	public function load($id = null){
		//Intantiate form's main bean (like JAVA Spring's formBindingObject)
		$this->baseBean = $id ? $this->mainDAO->getById($id) :  $this->mainDAO->getNew();
		
		$this->validationJS = array();
		//Load bean values into related field Descriptors
		$this->idFieldValidator->load($this->baseBean);
		foreach ($this->fieldDescriptors as $descriptor) {
			/* @var $descriptor FieldDescriptorInterface */
			$descriptor->load($this->baseBean);
			if ($this->validationHandler && count($descriptor->getValidators())){
				$this->validationJS = $this->validationHandler->buildValidationScript($descriptor, $this->id);
			}
		}
	}
	
	/**
	 * Returns the JS validation strings of the form in HTML
	 * @return string
	 */
	public function getValidationJS(){
		foreach ($this->validationJS as $script){
			echo "
				$script
			";
		}
	}
	
	/**
	 * Outputs the form's HTML
	 */
	public function toHTML(){
		//Render the form
		$this->renderer->render($this);
	}
	
	public function save($postValues){
		//getBean
		$id = $postValues[$this->idFieldValidator->getFieldName()];
		$bean = empty($id) ? $this->mainDAO->getNew() : $this->mainDAO->getById($id);
		
		foreach ($this->fieldDescriptors as $descriptor) {
			$value = $postValues[$descriptor->getFieldName()];
			echo "value : $value ->";
			//unformat values
			$formatter = $descriptor->getFormatter();
			if ($formatter) $value = $formatter->unformat($value);
			var_dump($value);echo"<br/>";
			
			//validate fields
			$validators = $descriptor->getValidators();
			if (count($validators)){
				foreach ($validators as $validator) {
					/* @var $validator ValidatorInterface */
					if (!$validator->validate($value)){
						$errors[$descriptor->getFieldName()] = $validator->getErrorMessage();
					}
				}
			}
			
			//save
			$descriptor->saveValue($bean, $value);
		}
		
		$this->mainDAO->save($bean);
	}
	
}