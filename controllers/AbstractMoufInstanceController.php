<?php

require_once(dirname(__FILE__)."/../reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../Moufspector.php");
require_once(dirname(__FILE__)."/../annotations/OneOfAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/OneOfTextAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/varAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/paramAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/ExtendedActionAnnotation.php");

/**
 * This abstract controller helps performing basic operations to display a detail instance page
 * (or any page that looks loke the detail instance page, with the right menu, etc...) 
 *
 */
abstract class AbstractMoufInstanceController extends Controller {

	public $instanceName;
	public $className;
	/**
	 * List of properties for this class.
	 * 
	 * @var array<MoufPropertyDescriptor>
	 */
	public $properties;
	public $reflectionClass;
	public $selfedit;
	
	/**
	 * The active MoufManager to be edited/viewed
	 *
	 * @var MoufManager
	 */
	public $moufManager;
	
	/**
	 * The template used by the main page for mouf.
	 *
	 * @Property
	 * @Compulsory
	 * @var TemplateInterface
	 */
	public $template;

	/**
	 * This function initiates the class variables of the controller according to the parameters passed.
	 * It will also configure the template to have the correct entry, especially in the right menu thazt is context dependent.
	 * 
	 * 
	 * @param string $name the name of the component to display
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	protected function initController($name, $selfedit) {
		$this->instanceName = $name;
		$this->selfedit = $selfedit;
		/*$this->instance = MoufManager::getMoufManager()->getInstance($name);
		$this->className = MoufManager::getMoufManager()->getInstanceType($this->instanceName);*/
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->className = $this->moufManager->getInstanceType($this->instanceName);		
		$this->reflectionClass = MoufReflectionProxy::getClass($this->className, $selfedit=="true");
		$this->properties = Moufspector::getPropertiesForClass($this->reflectionClass);
		
		// Init the right menu:
		$extendedActions = $this->reflectionClass->getAnnotations("ExtendedAction");
		if (!empty($extendedActions)) {
			$items = array();
			$items[] = new SplashMenuItem("<b>Special actions</b>", null, null);
			foreach ($extendedActions as $extendedAction) {
				$menuItem = new SplashMenuItem();
				$menuItem->menuText = $extendedAction->getName();
				$menuItem->menuLink = ROOT_URL.$extendedAction->getUrl();
				$menuItem->propagatedUrlParameters=array("selfedit", "name");
				$items[] = $menuItem;
			}
			$menuItems = new SplashMenu($items);
			$this->template->addRightHtmlElement($menuItems);	
		}
		
		$this->template->addRightHtmlElement(new SplashMenu(
			array(
			new SplashMenuItem("<b>Common</b>", null, null),
			new SplashMenuItem("View properties", ROOT_URL."mouf/instance/?name=".$name, null, array("selfedit")),
			new SplashMenuItem("View dependency graph", "mouf/displayGraph/?name=".$name, null, array("selfedit")))));
		$this->template->addRightFunction(array($this, "displayComponentParents"));
		
	}
	
	/**
	 * Displays the list of components directly referencing this component.
	 *
	 */
	public function displayComponentParents() {
		$componentsList = $this->moufManager->getOwnerComponents($this->instanceName);
		
		if (!empty($componentsList)) {
			$selfedit = get('selfedit');
			if (!$selfedit) {
				$selfedit = "false";
			}
			echo '<ul class="menu"><li><b>Referred by instances:</b></li>';
			foreach ($componentsList as $component) {
				echo '<li><a href="'.ROOT_URL.'mouf/mouf/displayComponent?name='.urlencode($component).'&selfedit='.$selfedit.'">'.plainstring_to_htmlprotected($component).'</a></li>';
			}
			echo '</ul>';
		}
	}
	
	/**
	 * Returns the value set for the instance passed in parameter... or the default value if the value is not set.
	 *
	 * @param MoufPropertyDescription $property
	 * @return mixed
	 */
	protected function getValueForProperty(MoufPropertyDescriptor $property) {
		if ($property->isPublicFieldProperty()) {
			$propertyName =  $property->getName();
			if ($this->moufManager->hasParameter($this->instanceName, $propertyName)) {
				$defaultValue = $this->moufManager->getParameter($this->instanceName, $propertyName);
			} else {
				$defaultValue = $this->reflectionClass->getProperty($propertyName)->getDefault();
			}
		} else {
			// This is a setter.
			$propertyName =  $property->getName();
			if ($this->moufManager->hasParameterForSetter($this->instanceName, $property->getMethodName())) {
				$defaultValue = $this->moufManager->getParameterForSetter($this->instanceName, $property->getMethodName());
			} else {
				// TODO: return a default value. We could try to find it using a getter maybe...
				// Or a default value for the setter? 
				return null;
			}
			
		}
		return $defaultValue;
	}
	
	/**
	 * Returns the type set for the instance passed in parameter.
	 * Type is one of string|config|request|session
	 *
	 * @param MoufPropertyDescription $property
	 * @return mixed
	 */
	protected function getTypeForProperty(MoufPropertyDescriptor $property) {
		if ($property->isPublicFieldProperty()) {
			$propertyName =  $property->getName();
			if ($this->moufManager->hasParameter($this->instanceName, $propertyName)) {
				$defaultValue = $this->moufManager->getParameterType($this->instanceName, $propertyName);
			} else {
				return "string";
			}
		} else {
			// This is a setter.
			$propertyName =  $property->getName();
			if ($this->moufManager->hasParameterForSetter($this->instanceName, $property->getMethodName())) {
				$defaultValue = $this->moufManager->getParameterTypeForSetter($this->instanceName, $property->getMethodName());
			} else {
				return "string";
			}
			
		}
		return $defaultValue;
	}
	
	/**
	 * Returns the metadata for the instance passed in parameter.
	 *
	 * @param MoufPropertyDescription $property
	 * @return array
	 */
	protected function getMetadataForProperty(MoufPropertyDescriptor $property) {
		if ($property->isPublicFieldProperty()) {
			$propertyName =  $property->getName();
			if ($this->moufManager->hasParameter($this->instanceName, $propertyName)) {
				$defaultValue = $this->moufManager->getParameterMetadata($this->instanceName, $propertyName);
			} else {
				return array();
			}
		} else {
			// This is a setter.
			$propertyName =  $property->getName();
			if ($this->moufManager->hasParameterForSetter($this->instanceName, $property->getMethodName())) {
				$defaultValue = $this->moufManager->getParameterMetadataForSetter($this->instanceName, $property->getMethodName());
			} else {
				return array();
			}
			
		}
		return $defaultValue;
	}
	
	/**
	 * Returns the value set for the instance passed in parameter... or the default value if the value is not set.
	 *
	 * @param MoufPropertyDescription $property
	 * @return mixed
	 */
	protected function getValueForPropertyByName($propertyName) {
		foreach ($this->properties as $property) {
			if ($property->getName() == $propertyName) {
				return $this->getValueForProperty($property);
			}
		}
	}
	
	/**
	 * Returns all components that are from the baseClass (or base interface) type.
	 * The call is performed through the ReflectionProxy.
	 * 
	 * @param string $baseClass
	 * @return array<string>
	 */
	protected function findInstances($baseClass) {
		return MoufReflectionProxy::getInstances($baseClass, $this->selfedit=="true");
	}
	
}
?>