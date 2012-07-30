<?php
/**
 * The controller to generate automatically the Beans, Daos, etc...
 * Sweet!
 * 
 * @Component
 */
class BceConfigController extends AbstractMoufInstanceController {
	
	
	/**
	 * List of instances implementing the DAOInterface, and therefore suitable as form's mainDAO property 
	 * @var array<string>
	 */
	protected $daoInstances;
	
	/**
	 * @var array<string>
	 */
	protected $renderers;
	
	/**
	 * @var array<string>
	 */
	protected $formatters;
	
	/**
	 * @var array<string>
	 */
	protected $formRenderers;
	
	/**
	 * @var array<string>
	 */
	protected $validationHandlers;
	
	/**
	 * The name of the set main DAO of the form 
	 * @var string
	 */
	protected $mainDAOName;
	
	public $success = 0;
	

	/**
	 * Admin page used to display the DAO generation form.
	 * The main part of the form's confifuration is handled in bce_utils.pho and bceConfig.js.
	 * Therefore, this controller just load the context for form's configuration
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction($name, $selfedit="false", $success = 0) {
		$this->initController($name, $selfedit);
		$this->success = $success;
		
		// Test if the main DAO has already been set 
		// (if yes, do not allow to change it, else simply diplay a dao instances select box)
		$desc = $this->moufManager->getInstanceDescriptor($name);
		$prop = $desc->getProperty('mainDAO');
		/* @var $val MoufInstanceDescriptor */
		$val = $prop->getValue();
		if ($val){
			$this->mainDAOName = $val->getName();
			$this->mainDAOClass = $val->getClassName();
			$this->daoInstances = null;
		}else{
			$this->mainDAOName = null;
			$this->mainDAOClass = null;
		}
		
		//Initialize descriptor's attributes possible values
		$this->daoInstances = MoufReflectionProxy::getInstances("DAOInterface", false);
		$this->renderers = MoufReflectionProxy::getInstances("FieldRendererInterface", false);
		$this->formatters = MoufReflectionProxy::getInstances("FormatterInterface", false);
		$this->validators = MoufReflectionProxy::getInstances("ValidatorInterface", false);

		//Initialize form's attributes possible values
		$this->formRenderers = MoufReflectionProxy::getInstances("BCERendererInterface", false);
		$this->validationHandlers = MoufReflectionProxy::getInstances("JsValidationHandlerInterface", false);
		$this->validationHandlers = MoufReflectionProxy::getInstances("JsValidationHandlerInterface", false);
		
		$this->template->addJsFile(ROOT_URL."plugins/mvc/bce/1.0-alpha/js/bceConfig.js");
		$this->template->addJsFile(ROOT_URL."plugins/mvc/bce/1.0-alpha/js/ui.multiselect.js");
		$this->template->addCssFile("plugins/mvc/bce/1.0-alpha/views/adminbce.css");
		$this->template->addCssFile("plugins/mvc/bce/1.0-alpha/js/ui.multiselect.css");
		$this->template->addContentFile(dirname(__FILE__)."/../views/bceConfig.php", $this);
		$this->template->draw();
	}
	
	/**
	 * @Action
	 * Ajax Call : Just set the main property of the DAO
	 */
	public function setDao($instance, $dao){
		try {
			// First, let's request the install utilities
			require_once dirname(__FILE__).'/../../../../../mouf/actions/InstallUtils.php';
			
			// Let's create the instance
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
			
			$instanceObj = $this->moufManager->getInstanceDescriptor($instance);
			$daoObj = $this->moufManager->getInstanceDescriptor($dao);
			$instanceObj->getProperty("mainDAO")->setValue($daoObj);
			
			$this->moufManager->rewriteMouf();
			
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}
	
	/**
	 * @Action
	 * Update the form
	 */
	public function save(){
		//Get the form instance
		$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		$formInstance = $this->moufManager->getInstanceDescriptor($_POST['formInstanceName']);
		
		//idfieldDesc 
		if (isset($_POST["idField"]['active']))
		$idFieldDesc = $this->updateFieldDescriptor($_POST["idField"]);
		$formInstance->getProperty('idFieldDescriptor')->setValue($idFieldDesc);		
		
		//Field Descriptors
		$fields = array();
		foreach ($_POST['fields'] as $data){
			if (isset($data['active'])){
				$field = $this->updateFieldDescriptor($data);
				$fields[] = $field; 
			}
		}
		$formInstance->getProperty("fieldDescriptors")->setValue($fields);
		
		
		//Form's own attributes
		$action = $_POST['config']['action'];
		$method = $_POST['config']['method'];
		$validate = $_POST['config']['validate'];
		$renderer = $_POST['config']['renderer'];
		
		$formInstance->getProperty('action')->setValue($action);
		$formInstance->getProperty('method')->setValue($method);
		$formInstance->getProperty('validationHandler')->setValue($this->moufManager->getInstanceDescriptor($validate));
		$formInstance->getProperty('renderer')->setValue($this->moufManager->getInstanceDescriptor($renderer));
		
		//Save form tag's attributes
		$attributes['name'] = $_POST['config']['name'];
		$attributes['id'] = $_POST['config']['id'];
		$attributes['acceptCharset'] = $_POST['config']['accept-charset'];
		$attributes['enctype'] = $_POST['config']['enctype'];
		$attributes['class'] = $_POST['config']['class'];
		$formInstance->getProperty('attributes')->setValue($attributes);
		
		$this->moufManager->rewriteMouf();
		
		header("Location: " . ROOT_URL . "mouf/bceadmin/?name=" . $_POST['formInstanceName'] . "&success=1");
	}
	
	/**
	 * Gets descriptor value from the POST, create or update the descriptor and return it
	 * @param array $fieldData
	 */
	private function updateFieldDescriptor($fieldData){
		//Create or update the descriptor
		if ($fieldData['new'] != "false"){
			switch ($fieldData['type']) {
				case "base":
					$className = "BaseFieldDescriptor";
				break;
				case "fk":
					$className = "ForeignKeyFieldDescriptor";
				break;
				case "m2m":
					$className = "Many2ManyFieldDescriptor";
				break;
			}
			
			$fieldDescriptor = $this->moufManager->createInstance($className);
			$instanceName = $fieldData['type'] == "m2m" ? $this->getInstanceName($fieldData['instanceNameInput']) : $this->getInstanceName($fieldData['instanceName']);
			$fieldDescriptor->setName($instanceName);
		}else{
			$fieldDescriptor = $this->moufManager->getInstanceDescriptor($fieldData['instanceName']);
		}
		
		
		//Set data depending on descriptor's type
		if ($fieldData['type'] != "custom"){
			$this->loadFieldDescriptor($fieldDescriptor, $fieldData);
			
			if ($fieldData['type'] != "m2m"){
				$this->loadBaseFieldDescriptor($fieldDescriptor, $fieldData);
			}
			
			if ($fieldData['type'] == "fk"){
				$this->loadFKDescriptor($fieldDescriptor, $fieldData);
			}else if ($fieldData['type'] == "m2m"){
				$this->loadM2MDescriptor($fieldDescriptor, $fieldData);
			}
		}
		
		return $fieldDescriptor;
	}
	
	/**
	 * Set common properties for any descriptor
	 * @param MoufInstanceDescriptor $fieldDescriptor
	 * @param array $fieldData the data to be updated
	 */
	private function loadFieldDescriptor(MoufInstanceDescriptor &$fieldDescriptor, $fieldData){
		if (isset($fieldData['formatter']) && !empty($fieldData['formatter'])){
			$formatter = $this->moufManager->getInstanceDescriptor($fieldData['formatter']);
			$fieldDescriptor->getProperty('formatter')->setValue($formatter);
		}
		if (isset($fieldData['renderer']) && !empty($fieldData['renderer'])){
			$renderer = $this->moufManager->getInstanceDescriptor($fieldData['renderer']);
			$fieldDescriptor->getProperty('renderer')->setValue($renderer);
		}
		
		
		$fieldDescriptor->getProperty('fieldName')->setValue($fieldData['fieldname']);
		$fieldDescriptor->getProperty('label')->setValue($fieldData['label']);

		$validators = array();
		if (isset($fieldData['validators'])){
			foreach ($fieldData['validators'] as $validatorName) {
				$validators[] = $this->moufManager->getInstanceDescriptor($validatorName);
			}
		}
		$fieldDescriptor->getProperty('validators')->setValue($validators);
	}
	
	/**
	 * Set properties for base descriptors (Base and FK)
	 * @param MoufInstanceDescriptor $fieldDescriptor
	 * @param array $fieldData the data to be updated
	 */
	private function loadBaseFieldDescriptor(MoufInstanceDescriptor &$fieldDescriptor, $fieldData){
		$fieldDescriptor->getProperty('getter')->setValue($fieldData['getter']);
		$fieldDescriptor->getProperty('setter')->setValue($fieldData['setter']);
		
	}
	
	/**
	 * Set common properties for FK descriptors
	 * @param MoufInstanceDescriptor $fieldDescriptor
	 * @param array $fieldData the data to be updated
	 */
	private function loadFKDescriptor(MoufInstanceDescriptor &$fieldDescriptor, $fieldData){
		/* @var $fkFieldDescriptor ForeignKeyFieldDescriptor */
		$dao = $this->moufManager->getInstanceDescriptor($fieldData['linkedDao']);
		$fieldDescriptor->getProperty('dao')->setValue($dao);
		
		$fieldDescriptor->getProperty('dataMethod')->setValue($fieldData['dataMethod']);
		$fieldDescriptor->getProperty('linkedIdGetter')->setValue($fieldData['linkedIdGetter']);
		$fieldDescriptor->getProperty('linkedLabelGetter')->setValue($fieldData['linkedLabelGetter']);
	}
	
	/**
	 * Set common properties for M2M descriptors
	 * @param MoufInstanceDescriptor $fieldDescriptor
	 * @param array $fieldData the data to be updated
	 */
	private function loadM2MDescriptor(&$fieldDescriptor, $fieldData){
		$mappingDao = $this->moufManager->getInstanceDescriptor($fieldData['mappingDao']);
		$fieldDescriptor->getProperty('mappingDao')->setValue($mappingDao);
		
		$fieldDescriptor->getProperty('mappingIdGetter')->setValue($fieldData['mappingIdGetter']);
		$fieldDescriptor->getProperty('mappingLeftKeySetter')->setValue($fieldData['mappingLeftKeySetter']);
		$fieldDescriptor->getProperty('mappingRightKeyGetter')->setValue($fieldData['mappingRightKeyGetter']);
		$fieldDescriptor->getProperty('mappingRightKeySetter')->setValue($fieldData['mappingRightKeySetter']);
		$fieldDescriptor->getProperty('beanValuesMethod')->setValue($fieldData['beanValuesMethod']);
		
		$linkedDao =  $this->moufManager->getInstanceDescriptor($fieldData['linkedDao']);
		$fieldDescriptor->getProperty('linkedDao')->setValue($linkedDao);
		
		$fieldDescriptor->getProperty('linkedIdGetter')->setValue($fieldData['linkedIdGetter']);
		$fieldDescriptor->getProperty('linkedLabelGetter')->setValue($fieldData['linkedLabelGetter']);
		$fieldDescriptor->getProperty('dataMethod')->setValue($fieldData['dataMethod']);
	}
	
	/**
	 * Helper for get a new instance Name, 
	 * and being sure an instance with the same name doesn't exist already
	 * @param string $defaultName the initial name to be set
	 */
	public function getInstanceName($defaultName){
		$i = 2;
		$finalName = $defaultName;
		while ($this->moufManager->instanceExists($finalName)){
			$finalName = $defaultName . $i;
			$i++;
		}
		return $finalName;
	}
}