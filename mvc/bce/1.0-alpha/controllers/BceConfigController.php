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
	 * The name of the set main DAO of the form 
	 * @var string
	 */
	protected $mainDAOName;
	
	/**
	 * The default fields of the form 
	 * @var array<string, mixed>
	 */
	protected $fields;
	
	/**
	 * The existing fields of the form 
	 * @var array<array>
	 */
	protected $existingFieldDescriptors;
	
	/**
	 * The existing id field descriptor of the form 
	 * @var array
	 */
	protected $idFieldDescriptor;
	
	/**
	 * Admin page used to display the DAO generation form.
	 *
	 * @Action
	 * @Login
	 */
	public function defaultAction($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->reflectionClass;
		$this->mainDAO = $this->properties['mainDAO'];

		$desc = $this->moufManager->getInstanceDescriptor($name);

		$idDesc = $desc->getProperty('idFieldDescriptor')->getValue();
		if ($idDesc){
			$this->idFieldDescriptor = new BceAdminBaseFieldBean($idDesc);
			
		}
		
		$props = $desc->getProperty('fieldDescriptors');
		$values = $props->getValue();
		if ($values){
			foreach ($values as $fieldDescriptor) {
				/* @var $fieldDescriptor MoufInstanceDescriptor */
				$instanceName = $fieldDescriptor->getName();
				$fdDesc = $this->moufManager->getInstanceDescriptor($instanceName);
				$className = $fdDesc->getClassName();
				switch ($className) {
					case "BaseFieldDescriptor":
						$obj = new BceAdminBaseFieldBean($fdDesc);
					break;
					case "ForeignKeyFieldDescriptor":
						$obj = new BceAdminFKFieldBean($fdDesc, $this);
					break;
				}
				$this->existingFieldDescriptors[$obj->getter] = $obj;
			}
		}
		
		$prop = $desc->getProperty('mainDAO');
		/* @var $val MoufInstanceDescriptor */
		$val = $prop->getValue();
		if ($val){
			$this->mainDAOName = $val->getName();
			$returnClass = MoufReflectionProxy::getClass($val->getClassName(), false)->getMethod('getById')->getAnnotations("return");
			$returnClass = $returnClass[0];
			$fields = MoufReflectionProxy::getClass($returnClass, false)->getMethods();
			foreach ($fields as $field) {
				/* @var $field MoufXmlReflectionMethod */
				$methodName = $field->getName();
				$propName = strtolower(substr($methodName, 3));
				$obj = new stdClass();
				$obj->fieldName = $propName; 
				if (substr($methodName, 0, 3) == "get"){
					$obj->getter = $methodName;
				}else if (substr($methodName, 0, 3) == "set"){
					$obj->setter = $methodName;
				}
				$fieldTab[$propName] = $obj;
			}
			foreach ($fieldTab as $field) {
				$getterName = $field->getter;
				if (!isset($this->existingFieldDescriptors[$getterName]) 
						&& $this->idFieldDescriptior && $this->idFieldDescriptor->getter != $getterName){
					$this->fields[$getterName] = $field;
				}
			}
		}else{
			$this->daoInstances = MoufReflectionProxy::getInstances("DAOInterface", false);
		}
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/bceConfig.php", $this);
		$this->template->draw();
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
	
}