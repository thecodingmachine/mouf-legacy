<?php

require_once(dirname(__FILE__)."/../reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../Moufspector.php");
require_once(dirname(__FILE__)."/../annotations/OneOfAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/OneOfTextAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/varAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/paramAnnotation.php");
require_once(dirname(__FILE__)."/../CanvasWriter.php");

/**
 * The controller allowing access to the Mouf framework.
 *
 * @Component
 */
class MoufController extends Controller {

	public $instanceName;
	public $className;
	public $properties;
	//public $instance;
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
	 * @Action
	 *
	 * @param string $name the name of the component to display
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function displayComponent($name, $selfedit = false) {
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
		
		$template = $this->template;
		$this->template->addHeadHtmlElement(new HtmlJSJit());
		$this->template->addJsFile(ROOT_URL."mouf/views/displayComponent.js");
		$this->template->addRightHtmlElement(new SplashMenu(
			array(
			//new SplashMenuItem("View properties", "mouf/mouf/displayComponent?name=".$name, null, array("selfedit")),
			new SplashMenuItem("View dependency graph", "mouf/mouf/displayGraph?name=".$name, null, array("selfedit")))));
		$template->addRightFunction(array($this, "displayComponentParents"));
		$template->addContentFunction(array($this, "displayComponentView"));
		$template->draw();	
	}
	
	/**
	 * Displays the component details view
	 *
	 */
	public function displayComponentView() {
		include(dirname(__FILE__)."/../../mouf/views/displayComponent.php");
	}
	
	/**
	 * Displays the dependency graph around the component passed in parameter.
	 * 
	 * @Action
	 *
	 * @param string $name
	 * @param string $selfedit
	 */
	public function displayGraph($name, $selfedit = false) {
		$this->instanceName = $name;
		$this->selfedit = $selfedit;
		/*$this->instance = MoufManager::getMoufManager()->getInstance($name);
		$this->className = MoufManager::getMoufManager()->getInstanceType($this->instanceName);*/
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$template = $this->template;
		$this->template->addHeadHtmlElement(new HtmlJSJit());
		$this->template->addJsFile(ROOT_URL."mouf/views/displayGraph.js");
		$this->template->addRightHtmlElement(new SplashMenu(
			array(
			new SplashMenuItem("View properties", "mouf/mouf/displayComponent?name=".$name, null, array("selfedit")),
			/*new SplashMenuItem("View dependency graph", "mouf/mouf/displayGraph?name=".$name, null, array("selfedit"))*/)));
		$template->addContentFile(dirname(__FILE__)."/../views/displayGraph.php", $this);
		$template->draw();
	}
	
	/**
	 * Displays the list of components directly referencing this component.
	 *
	 */
	function displayComponentParents() {
		$componentsList = $this->moufManager->getOwnerComponents($this->instanceName);
		
		if (!empty($componentsList)) {
			echo '<ul class="menu"><li><b>Referred by components:</b></li>';
			foreach ($componentsList as $component) {
				echo '<li><a href="'.ROOT_URL.'mouf/mouf/displayComponent?name='.urlencode($component).'&selfedit=false">'.plainstring_to_htmlprotected($component).'</a></li>';
			}
			echo '</ul>';
		}
	}
	
	/**
	 * Lists all the components available in order to edit them.
	 * 
	 * @Action
	 */
	public function defaultAction($selfedit = "false") {
		$this->selfedit = $selfedit;

		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/listComponents.php", $this);
		$this->template->draw();
	}
	
	/**
	 * Action that saves the component.
	 *
	 * @Action
	 * @param string $originalInstanceName The name of the instance
	 * @param string $instanceName The new name of the instance (if it was renamed)
	 * @param string $delete Whether the instance should be deleted or not
	 * @param string $selfedit Self edit mode
	 * @param string $createNewInstance If "true", a new instance should be created and attached to the saved component instance.
	 * @param string $bindToProperty The name of the property the new instance will be bound to.
	 * @param string $newInstanceName The name of new instance to create and attach to the saved object
	 * @param string $instanceClass The type of the new instance to create and attach to the saved object
	 * @param string $newInstanceKey The key of the new instance (if it is part of an associative array)
	 */
	public function saveComponent($originalInstanceName, $instanceName, $delete, $selfedit, $newInstanceName=null, $createNewInstance=null, $bindToProperty=null, $instanceClass=null, $newInstanceKey=null) {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		// Should we delete the component instead of save it?
		if ($delete) {
			$this->moufManager->removeComponent($originalInstanceName);
			$this->moufManager->rewriteMouf();
			
			header("Location: ".ROOT_URL."mouf/mouf/?selfedit=".$selfedit);
		}
		
		// Renames the component if needed.
		if ($originalInstanceName != $instanceName) {
			$this->moufManager->renameComponent($originalInstanceName, $instanceName);
		}
		
		$this->instanceName = $instanceName;
		$this->className = $this->moufManager->getInstanceType($instanceName);
		$this->reflectionClass = MoufReflectionProxy::getClass($this->className, $selfedit=="true");
		$this->properties = Moufspector::getPropertiesForClass($this->reflectionClass);
		//$this->properties = Moufspector::getPropertiesForClass($this->className);
		
		foreach ($this->properties as $property) {
			if ($property->hasType()) {
				//$varTypes = $property->getAnnotations("var");
				//$varTypeAnnot = $varTypes[0];
				//$varType = $varTypeAnnot->getType();
				$varType = $property->getType();
				$lowerVarType = strtolower($varType);
				
				$propertyType = "";
				
				if ($lowerVarType == "string" || $lowerVarType == "bool" || $lowerVarType == "boolean" || $lowerVarType == "int" || $lowerVarType == "integer" || $lowerVarType == "double" || $lowerVarType == "float" || $lowerVarType == "real" || $lowerVarType == "mixed") {
					$value = get($property->getName());
					if ($lowerVarType == "bool" || $lowerVarType == "boolean") {
						if ($value == "true") {
							$value = true;
						}
					}
					if ($property->isPublicFieldProperty()) {
						$this->moufManager->setParameter($instanceName, $property->getName(), $value);
					} else {
						$this->moufManager->setParameterViaSetter($instanceName, $property->getMethodName(), $value);
					}
				} else if ($lowerVarType == "array") {
					$recursiveType = $property->getSubType();
					$isAssociative = $property->isAssociativeArray();
					if ($recursiveType == "string" || $recursiveType == "bool" || $recursiveType == "boolean" || $recursiveType == "int" || $recursiveType == "integer" || $recursiveType == "double" || $recursiveType == "float" || $recursiveType == "real" || $recursiveType == "mixed") {
						if ($isAssociative) {
							$keys = get("moufKeyFor".$property->getName());
							$tmpValues = get($property->getName());
							
							$values = array();
							if (is_array($tmpValues)) {
								for ($i=0; $i<count($tmpValues); $i++) {
									$values[$keys[$i]] = $tmpValues[$i];
								}
							}
						} else {
							$values = get($property->getName());
						}
						if ($property->isPublicFieldProperty()) {
							$this->moufManager->setParameter($instanceName, $property->getName(), $values);
						} else {
							$this->moufManager->setParameterViaSetter($instanceName, $property->getMethodName(), $values);
						}
					} else {
						if ($isAssociative) {
							$keys = get("moufKeyFor".$property->getName());
							$tmpValues = get($property->getName());
							
							$values = array();
							if (is_array($tmpValues)) {
								for ($i=0; $i<count($tmpValues); $i++) {
									$values[$keys[$i]] = $tmpValues[$i];
								}
							}
						} else {
							$values = get($property->getName());
						}
						
						if ($property->isPublicFieldProperty()) {
							$this->moufManager->bindComponents($instanceName, $property->getName(), $values);
						} else {
							$this->moufManager->bindComponentsViaSetter($instanceName, $property->getMethodName(), $values);
						}
					}
				} else {
					$value = get($property->getName());
					if ($value == "")
						$value = null;
						
					if ($property->isPublicFieldProperty()) {
						$this->moufManager->bindComponent($instanceName, $property->getName(), $value);
					} else {
						$this->moufManager->bindComponentViaSetter($instanceName, $property->getMethodName(), $value);
					}
				}
				
				
			} else {
				// No @var annotation
				throw new Exception("Error while saving, no @var annotation for property ".$property->getName());
			}
		}
				
		// Ok, component was saved. Now, were we requested to create a new instance?
		if ($createNewInstance == "true") {
			// TODO: check $newInstanceName not empty (or accept anonymous objects).
			$this->moufManager->declareComponent($newInstanceName, $instanceClass);
			
			// Now, let's bind that new instance to the old one.
			/*foreach ($this->properties as $property) {
				// Find the right property
				if ($bindToProperty == $property->getName()) {
					// Ok, we bind to property "property".
					// Is it an array or not?
					if ($property->getType() == "array") {
						// TODO
						// Insert depending on position and associative array! (first, position must be passed!)
					} else {
						// This is not an array. Hooray!
						if ($property->isPublicFieldProperty()) {
							$this->moufManager->bindComponent($instanceName, $property->getName(), $newInstanceName);
						} else {
							$this->moufManager->bindComponentViaSetter($instanceName, $property->getMethodName(), $newInstanceName);
						}
					}
				}
			}*/
			
			$this->moufManager->rewriteMouf();
			
			$this->displayComponent($newInstanceName, $selfedit);
			return;
		}
		
		$this->moufManager->rewriteMouf();
		
		$this->displayComponent($instanceName, $selfedit);	
	}
	
	/**
	 * Displays the screen allowing to create new instances.
	 *
	 * @Action
	 */
	public function newInstance($selfedit = "false") {
		//$componentsList = Moufspector::getComponentsList();
		$this->selfedit = $selfedit;
		$componentsList = MoufReflectionProxy::getComponentsList($selfedit=="true");
		sort($componentsList);
		
		$template = $this->template;
		$template->addContentFunction(array($this, "displayNewInstanceScreen"), $componentsList, $selfedit);
		//$template->addContentFile(dirname(__FILE__)."/../views/displayNewInstance.php", $this);
		$template->draw();	
	}
	
	/**
	 * Displays the new component view
	 *
	 */
	public function displayNewInstanceScreen($componentsList, $selfedit) {
		include(dirname(__FILE__)."/../views/displayNewInstance.php");
	}
	
	/**
	 * The action that creates a new component instance.
	 *
	 * @Action
	 * @param string $instanceName The name of the instance to create
	 * @param string $instanceClass The class of the component to create
	 */
	public function createComponent($instanceName, $instanceClass, $selfedit) {
		$this->selfedit = $selfedit;
		
		if ($selfedit == "true") {
			$this->moufManager = MoufManager::getMoufManager();
		} else {
			$this->moufManager = MoufManager::getMoufManagerHiddenInstance();
		}
		
		
		$this->moufManager->declareComponent($instanceName, $instanceClass);
		$this->moufManager->rewriteMouf();
		
		// Redirect to the display component page:
		$this->displayComponent($instanceName, $selfedit);
	}
	
	/**
	 * Returns the value set for the instance passed in parameter... or the default value if the value is not set.
	 *
	 * @param MoufPropertyDescription $property
	 * @return mixed
	 */
	private function getValueForProperty(MoufPropertyDescriptor $property) {
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
	 * Returns all components that are from the baseClass (or base interface) type.
	 * The call is performed through the ReflectionProxy.
	 * 
	 * @param string $baseClass
	 * @return array<string>
	 */
	private function findInstances($baseClass) {
		return MoufReflectionProxy::getInstances($baseClass, $this->selfedit=="true");
	}
	
	/**
	 * @Action
	 * 
	 */
	public function mouf() {
		//echo Moufspector::getPropertyType("PaypalConfig", "paypalUrl");
		echo Moufspector::testComment();
	}
	
	/**
	 * Returns the Jit graph from the rootNode and downward.
	 *
	 * @param string $rootNode
	 * @return array JSON message as a PHP array
	 */
	protected function getJitJson($rootNode) {
		return $this->getJitJsonRecursive($rootNode, array());
	}

	/**
	 * Returns the Jit graph from the rootNode and downward.
	 *
	 * @param string $rootNode
	 * @return array JSON message as a PHP array
	 */
	/*private function getJitJsonAllInstances() {
		$instances = array_keys($this->moufManager->getInstancesList());
		$allJson = array();
		
		if (is_array($instances)) {
			foreach ($instances as $instance) {
				$allJson[] = $this->getJitJsonNode($instance);
			}
		
		}
		return $allJson;
	}*/
	
	/**
	 * Builds the Json message (as a PHP array) for JIT to display the tree.
	 *
	 * @param string $nodeToAdd The instance to add
	 * @param array $nodesList The Json message so far
	 * @return array The Json message with the current node (and its children) added.
	 */
	private function getJitJsonRecursive($nodeToAdd, $nodesList) {
		$node = $this->getJitJsonNode($nodeToAdd);
		$nodesList[] = $node;
		
		$componentsList = $this->getComponentsListBoundToInstance($nodeToAdd);
		
		foreach ($componentsList as $component) {
			// Let's check if we have already passed this component:
			$alreadyDone = false;
			foreach ($nodesList as $traversedNode) {
				if ($traversedNode["id"] == $component) {
					$alreadyDone = true;
					break;
				}
			}
			
			if (!$alreadyDone) {
				$nodesList = $this->getJitJsonRecursive($component, $nodesList);
			}
			
		}
		
		
		
		
		
		
		$componentsList = $this->moufManager->getOwnerComponents($nodeToAdd);

		
		
		foreach ($componentsList as $component) {
			// Let's check if we have already passed this component:
			$alreadyDone = false;
			foreach ($nodesList as $traversedNode) {
				if ($traversedNode["id"] == $component) {
					$alreadyDone = true;
					break;
				}
			}
			
			if (!$alreadyDone) {
				$nodesList = $this->getJitJsonRecursive($component, $nodesList);
			}
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		return $nodesList;
	}

	/**
	 * Returns a PHP array representing a node that will be used by JIT to build a visual representation.
	 *
	 */
	private function getJitJsonNode($instanceName) {
		$node = array();
		
		$node["id"] = $instanceName;
		$node["name"] = $instanceName;
		// We can set some data (dimension, other keys...) but we will keep tht to 0 for now.
		
		$adjacencies = array();
						
		$componentsList = $this->getComponentsListBoundToInstance($instanceName);
		
		foreach ($componentsList as $component) {
			$adjacency = array();
			$adjacency['nodeTo'] = $component;
			// We can set some data (weight...) but we will keep tht to 0 for now.
			
			$data = array();
			$data['$type'] = "arrow";
			$data['$direction'] = array($instanceName, $component);
			
			/*            "data": {
                "$type":"arrow",
                "$direction": ["node4", "node3"],
                "$dim":25,
                "$color":"#dd99dd",
                "weight": 1
			
            }*/
			$adjacency['data'] = $data;
			
			$adjacencies[] = $adjacency;
		}
		
		$node["adjacencies"] = $adjacencies;
		
		return $node;        
	}
	
	/**
	 * Returns the list of components that this component possesses bindings on.
	 *
	 * @param string $instanceName
	 * @return array<string>
	 */
	private function getComponentsListBoundToInstance($instanceName) {
		$componentsList = array();
		$boundComponents = $this->moufManager->getBoundComponents($instanceName);

		if (is_array($boundComponents)) {
			foreach ($boundComponents as $property=>$components) {
				if (is_array($components)) {
					$componentsList = array_merge($componentsList, $components);
				} else {
					$componentsList[] = $components;
				}
			}
		}
		return $componentsList;
	}
}
?>