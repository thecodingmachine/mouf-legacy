<?php 

/**
 * A component extending the MoufActionProviderInterface can be used to perform actions during an installation process. 
 * 
 * @author david
 */
interface MoufActionProviderInterface {
	
	/**
	 * Executes the action passed in parameter.
	 * 
	 * @param MoufActionDescriptor $actionDescriptor
	 */
	function execute(MoufActionDescriptor $actionDescriptor);
	
	/**
	 * Returns the text describing the action.
	 * 
	 * @param MoufActionDescriptor $actionDescriptor
	 */
	function getName(MoufActionDescriptor $actionDescriptor);
}

?>