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
 * @ExtendedAction {"name":"Configure Form", "url":"mouf/bceadmin/", "default":false}
 * @author Kevin
 *
 */
class BCEForm{
	
	/**
	 * The main bean of the form, i.e. the object that define the edited data in the form
	 * @var mixed $baseBean
	 */
	public $baseBean;
	
	/**
	 * Field Decriptors define which fields avaiable through the main DAO should be involved in the form.<br/>
	 * They define a lot of data<br/>
	 * 
	 * @Property 
	 * @var array<BaseFieldDescriptor>
	 */
	public $fieldDescriptors;
	
	
	/**
	 * Field Decriptors that are not directly related to the bean, but through an associative table (many to many relation ships)
	 * 
	 * @Property 
	 * @var array<Many2ManyFieldDescriptor>
	 */
	public $many2ManyFieldDescriptors;
	
	/**
	 * Field Decriptors of the bean's identifier.
	 * This is a special field because the id will help to retrive bean before saving it.
	 * 
	 * @Property 
	 * @var BaseFieldDescriptor
	 */
	public $idFieldDescriptor;
	
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
	 * The errors returned by the fields' validators
	 * @var array<string>
	 */
	public $errorMessages;
	
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
			/* @var $descriptor FieldDescriptor */
			$descriptor->load($this->baseBean);
			if ($this->validationHandler && count($descriptor->getValidators())){
				$this->validationHandler->buildValidationScript($descriptor, $this->id);
			}
		}
		
		foreach ($this->many2ManyFieldDescriptors as $descriptor) {
			/* @var $descriptor Many2ManyFieldDescriptor */
			$descriptor->load($id);
			if ($this->validationHandler && count($descriptor->getValidators())){
				$this->validationHandler->buildValidationScript($descriptor, $this->id);
			}
		}
	}
	
	/**
	 * Returns the JS validation strings of the form in HTML
	 * @return string
	 */
	public function getValidationJS(){
		return $this->validationHandler->getValidationJs($this->id);
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
		$this->baseBean = empty($id) ? $this->mainDAO->getNew() : $this->mainDAO->getById($id);
		
		$descriptors = array_merge($this->fieldDescriptors, $this->many2ManyFieldDescriptors);
		foreach ($descriptors as $descriptor) {
			if (!isset($postValues[$descriptor->getFieldName()])){
				$value = null;
			}else{
				$value = $postValues[$descriptor->getFieldName()];
			}
			$values[$descriptor->getFieldName()] = $value;
			
			//unformat values
			$formatter = $descriptor->getFormatter();
			if ($formatter && $formatter instanceof BijectiveFormatterInterface) {
				$value = $formatter->unformat($value);
			}
			
			//validate fields
			$validators = $descriptor->getValidators();
			if (count($validators)){
				foreach ($validators as $validator) {
					/* @var $validator ValidatorInterface */
					if (is_array($validator->validate($value))){
						$this->errorMessages[$descriptor->getFieldName()][] = $validator->getErrorMessage();
					}
				}
			}
			if ($descriptor instanceof BaseFieldDescriptor) {
				$descriptor->setValue($this->baseBean, $value);
			}else if ($descriptor instanceof Many2ManyFieldDescriptor) {
				$descriptor->setSaveValues($value);
			}
		}
		if (!count($this->errorMessages)){
			//save
			echo "<h1>Save!!</h1>";
			$this->mainDAO->save($this->baseBean);
			
			echo "<h2>M2M</h2>";
			$id = $this->getMainBeanId();
			foreach ($this->many2ManyFieldDescriptors as $descriptor){
				echo "<h3>".$descriptor->getFieldName()."</h3>";
				$this->m2mSave($id, $descriptor);		
			}
		}else{
			echo "<h1>Php Errors!!</h1>";
			var_dump($this->errorMessages);
		}
		
	}
	
	private function getMainBeanId(){
		$this->idFieldDescriptor->load($this->baseBean);
		return $this->idFieldDescriptor->getFieldValue();
	}
	
	/**
	 * Handle saving data for Many 2 Many relationships
	 * @param mixed $mainBeanId : the Id of the current table
	 * @param Many2ManyFieldDescriptor $descriptor
	 */
	private function m2mSave($mainBeanId, Many2ManyFieldDescriptor $m2mdescriptor){
		$m2mdescriptor->loadValues($mainBeanId);
		$beforeValues = $m2mdescriptor->getBeanValues();
		$finalValues = $m2mdescriptor->getSaveValues();
		
		$toDelete = array_diff($beforeValues, $finalValues);
		$toSave = array_diff($finalValues, $beforeValues);

		foreach ($toDelete as $linkedBeanId) {
			$m2mdescriptor->dao->deleteByForeignKeys($mainBeanId, $linkedBeanId);
		}
		foreach ($toSave as $linkedBeanId) {
			$bean = $m2mdescriptor->dao->getNew();
			$m2mdescriptor->setMainId($mainBeanId, $bean);
			$m2mdescriptor->setLinkedId($linkedBeanId, $bean);
			$m2mdescriptor->dao->save($bean);
		}
	}
	
}