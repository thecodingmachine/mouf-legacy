<?php

require_once('AbstractMoufInstanceController.php');
/*require_once(dirname(__FILE__)."/../reflection/MoufReflectionProxy.php");
require_once(dirname(__FILE__)."/../Moufspector.php");
require_once(dirname(__FILE__)."/../annotations/OneOfAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/OneOfTextAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/varAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/paramAnnotation.php");
require_once(dirname(__FILE__)."/../annotations/ExtendedActionAnnotation.php");*/

/**
 * This controller displays the (not so) basic details page.
 *
 * @Component
 */
class MoufInstanceController extends AbstractMoufInstanceController {

	/**
	 * @Action
	 *
	 * @param string $name the name of the component to display
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($name, $selfedit = false) {
		$this->initController($name, $selfedit);
		
		$this->template->addJsFile(ROOT_URL."mouf/views/displayComponent.js");
		
		//$this->template->addContentFunction(array($this, "displayComponentView"));
		$this->template->addContentFile(dirname(__FILE__)."/../../mouf/views/displayComponent.php", $this);
		$this->template->draw();	
	}
	
	/**
	 * Displays the component details view
	 *
	 */
	/*public function displayComponentView() {
		include(dirname(__FILE__)."/../../mouf/views/displayComponent.php");
	}*/
	
	/**
	 * Displays the dependency graph around the component passed in parameter.
	 * 
	 * @Action
	 *
	 * @param string $name
	 * @param string $selfedit
	 */
	public function displayGraph($name, $selfedit = false) {
		$this->initController($name, $selfedit);
		
		$template = $this->template;
		$this->template->addHeadHtmlElement(new HtmlJSJit());
		$this->template->addJsFile(ROOT_URL."mouf/views/displayGraph.js");
		$template->addContentFile(dirname(__FILE__)."/../views/displayGraph.php", $this);
		$template->draw();
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
	 * @param string $duplicateInstance If "true", a copy of the instance will be created. This copy will be named after the $newInstanceName param.
	 */
	public function saveComponent($originalInstanceName, $instanceName, $delete, $selfedit, $newInstanceName=null, $createNewInstance=null, $bindToProperty=null, $instanceClass=null, $newInstanceKey=null, $duplicateInstance=null) {
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
		
		$this->moufManager->unsetAllParameters($instanceName);
		
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
				if ($property->isPublicFieldProperty()) {
					// No @var annotation
					throw new Exception("Error while saving, no @var annotation for property ".$property->getName());
				} else {
					throw new Exception("Error while saving, no @param annotation for setter ".$property->getMethodName());
				}
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
			
			$this->defaultAction($newInstanceName, $selfedit);
			return;
		}
		
		// Let's duplicate the component.
		if ($duplicateInstance == "true") {
			$this->moufManager->duplicateInstance($instanceName, $newInstanceName);
			$this->moufManager->rewriteMouf();
			
			$this->defaultAction($newInstanceName, $selfedit);
			return;
		}
		
		$this->moufManager->rewriteMouf();
		
		$this->defaultAction($instanceName, $selfedit);	
	}
}
?>