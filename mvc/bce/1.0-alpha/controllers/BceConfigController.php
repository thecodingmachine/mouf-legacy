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
	protected $formaters;
	
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
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction($name, $selfedit="false", $success = 0) {
		$this->initController($name, $selfedit);
		$this->success = $success;
		
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
		
		$this->daoInstances = MoufReflectionProxy::getInstances("DAOInterface", false);
		$this->renderers = MoufReflectionProxy::getInstances("FieldRendererInterface", false);
		$this->formaters = MoufReflectionProxy::getInstances("FormatterInterface", false);
		$this->validators = MoufReflectionProxy::getInstances("ValidatorInterface", false);

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
	 */
	public function setDao($instance, $dao){
		try {
			// First, let's request the install utilities
			require_once dirname(__FILE__).'/../../../../../mouf/actions/InstallUtils.php';
			
			// Let's init Mouf
// 			InstallUtils::init(InstallUtils::$INIT_APP);
			
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
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		
		if( curl_error($ch) ) { 
			throw new Exception("TODO: texte de l'erreur curl");
		} else {
			$response = curl_exec( $ch );
		}
		curl_close( $ch );
		
		return $response;
	}

	/**
	 * @Action
	 */
	public function save(){
		$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		$formInstance = $this->moufManager->getInstanceDescriptor($_POST['formInstanceName']);
		
		//idfieldDesc 
		if (isset($_POST["idField"]['active']))
		$idFieldDesc = $this->updateFieldDescriptor($_POST["idField"]);
		$formInstance->getProperty('idFieldDescriptor')->setValue($idFieldDesc);		
		
		$fields = array();
		$m2mfields = array();
		foreach ($_POST['fields'] as $data){
			if (isset($data['active'])){
				$field = $this->updateFieldDescriptor($data);
				if ($data['type'] != 'm2m'){
					$fields[] = $field; 
				}else{
					$m2mfields[] = $field; 
				}
			}
		}
		$formInstance->getProperty("fieldDescriptors")->setValue($fields);
		$formInstance->getProperty("many2ManyFieldDescriptors")->setValue($m2mfields);
		
		$name = $_POST['config']['name'];
		$action = $_POST['config']['action'];
		$id = $_POST['config']['id'];
		$method = $_POST['config']['method'];
		$validate = $_POST['config']['validate'];
		$renderer = $_POST['config']['renderer'];
		
		$formInstance->getProperty('name')->setValue($name);
		$formInstance->getProperty('action')->setValue($action);
		$formInstance->getProperty('id')->setValue($id);
		$formInstance->getProperty('method')->setValue($method);
		$formInstance->getProperty('validationHandler')->setValue($this->moufManager->getInstanceDescriptor($validate));
		$formInstance->getProperty('renderer')->setValue($this->moufManager->getInstanceDescriptor($renderer));
		
		$this->moufManager->rewriteMouf();
		
		header("Location: " . ROOT_URL . "mouf/bceadmin/?name=" . $_POST['formInstanceName'] . "&success=1");
	}
	
	private function updateFieldDescriptor($fieldData){
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
		
		if ($fieldData['new'] != "false"){
			$fieldDescriptor = $this->moufManager->createInstance($className);
			$instanceName = $fieldData['type'] == "m2m" ? $this->getInstanceName($fieldData['instanceNameInput']) : $this->getInstanceName($fieldData['instanceName']);
			$fieldDescriptor->setName($instanceName);
		}else{
			$fieldDescriptor = $this->moufManager->getInstanceDescriptor($fieldData['instanceName']);
		}
		
		$this->loadFieldDescriptor($fieldDescriptor, $fieldData);
		
		if ($fieldData['type'] != "m2m"){
			$this->loadBaseFieldDescriptor($fieldDescriptor, $fieldData);
		}
		
		if ($fieldData['type'] == "fk"){
			$this->loadFKDescriptor($fieldDescriptor, $fieldData);
		}else if ($fieldData['type'] == "m2m"){
			$this->loadM2MDescriptor($fieldDescriptor, $fieldData);
		}
		
		return $fieldDescriptor;
	}
	
	private function loadFieldDescriptor(&$fieldDescriptor, $fieldData){
		if (isset($fieldData['formater']) && !empty($fieldData['formater'])){
			$formater = $this->moufManager->getInstanceDescriptor($fieldData['formater']);
			$fieldDescriptor->getProperty('formatter')->setValue($formater);
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
	
	private function loadBaseFieldDescriptor(&$fieldDescriptor, $fieldData){
		$fieldDescriptor->getProperty('getter')->setValue($fieldData['getter']);
		$fieldDescriptor->getProperty('setter')->setValue($fieldData['setter']);
		
	}
	
	private function loadFKDescriptor(MoufInstanceDescriptor &$fieldDescriptor, $fieldData){
		/* @var $fkFieldDescriptor ForeignKeyFieldDescriptor */
		$dao = $this->moufManager->getInstanceDescriptor($fieldData['linkedDao']);
		$fieldDescriptor->getProperty('dao')->setValue($dao);
		
		$fieldDescriptor->getProperty('dataMethod')->setValue($fieldData['dataMethod']);
		$fieldDescriptor->getProperty('linkedIdGetter')->setValue($fieldData['linkedIdGetter']);
		$fieldDescriptor->getProperty('linkedLabelGetter')->setValue($fieldData['linkedLabelGetter']);
	}
	
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