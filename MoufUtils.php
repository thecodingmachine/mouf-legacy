<?php

/**
 * This class contains utility functions
 * 
 * @author David Negrier
 */
class MoufUtils {
	
	/**
	 * Registers a new menuItem instance to be displayed in Mouf main menu.
	 * 
	 * @param string $instanceName
	 * @param string $label
	 * @param string $url
	 * @param string $parentMenuItemName The parent menu item instance name. 'mainMenu' is the main menu item, and 'moufSubMenu' is the 'Mouf' menu 
	 * @param float $priority The position of the menu
	 */
	public static function registerMenuItem($instanceName, $label, $url, $parentMenuItemName = 'moufSubMenu', $priority = 50) {
		$moufManager = MoufManager::getMoufManager();

		if ($moufManager->instanceExists($instanceName)) {
			return;
		}
		
		$moufManager->declareComponent($instanceName, 'MenuItem', true);
		$moufManager->setParameterViaSetter($instanceName, 'setLabel', $label);
		$moufManager->setParameterViaSetter($instanceName, 'setUrl', $url);
		$moufManager->setParameterViaSetter($instanceName, 'setPriority', $priority);
		if (strpos($url, "javascript:") !== 0) {
			$moufManager->setParameterViaSetter($instanceName, 'setPropagatedUrlParameters', array (
			  0 => 'selfedit',
			));
		}

		$parentMenuItem = MoufManager::getMoufManager()->getInstance($parentMenuItemName);
		/* @var $parentMenuItem MenuItem */
		$parentMenuItem->addMenuItem(MoufManager::getMoufManager()->getInstance($instanceName));		
	}
}