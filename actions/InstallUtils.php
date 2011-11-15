<?php
require_once dirname(__FILE__).'/../../MoufUniversalParameters.php';

/**
 * This utility class should be used during package install processes
 * @author david
 */
class InstallUtils {
	
	public static $INIT_APP = 1;
	public static $INIT_ADMIN = 2;
		
	public static function init($initMode) {
		require_once dirname(__FILE__).'/../direct/utils/check_rights.php';
		
		if ($initMode == self::$INIT_APP) {
			require_once dirname(__FILE__).'/../../Mouf.php';
			require_once dirname(__FILE__).'/../MoufPackageManager.php';
		} else {
			require_once dirname(__FILE__).'/../MoufManager.php';
			MoufManager::initMoufManager();
			require_once dirname(__FILE__).'/../MoufAdmin.php';
			require_once dirname(__FILE__).'/../MoufPackageManager.php';
		}
	}
	
	/**
	 * Redirects the user to the end of the install procedure.
	 * The current install process will be validated.
	 * 
	 * This function writes a header "Location:" to perform the redirect.
	 * Therefore, to be effective, nothing should have been outputed.
	 */
	public static function continueInstall($selfEdit = false) {
		header("Location: ".ROOT_URL."mouf/install/installStepDone?selfedit=".(($selfEdit)?"true":"false"));
	}
}