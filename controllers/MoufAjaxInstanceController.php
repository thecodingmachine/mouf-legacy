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
 * This controller displays the (not so) basic full ajax details page.
 *
 * @Component
 */
class MoufAjaxInstanceController extends AbstractMoufInstanceController {

	/**
	 * @Action
	 * @Logged
	 *
	 * @param string $name the name of the component to display
	 * @param string $selfedit If true, the name of the component must be a component from the Mouf framework itself (internal use only) 
	 */
	public function defaultAction($name, $selfedit = false) {
		$this->initController($name, $selfedit);
		
		$this->template->addJsFile(ROOT_URL."mouf/views/instances/messages.js");
		$this->template->addJsFile(ROOT_URL."mouf/views/instances/utils.js");
		$this->template->addJsFile(ROOT_URL."mouf/views/instances/instances.js");
		
		//$this->template->addContentFunction(array($this, "displayComponentView"));
		$this->template->addContentFile(dirname(__FILE__)."/../../mouf/views/instances/viewInstance.php", $this);
		$this->template->draw();	
	}
	
}
?>