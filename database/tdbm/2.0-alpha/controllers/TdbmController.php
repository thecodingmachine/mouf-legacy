<?php
require_once dirname(__FILE__).'/../utils/dao_generator.php';

/**
 * The controller to generate automatically the Beans, Daos, etc...
 * Sweet!
 * 
 * @Component
 */
class TdbmController extends AbstractMoufInstanceController {
	
		
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * //@Admin
	 */
	public function defaultAction($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/tdbmGenerate.php", $this);
		$this->template->draw();
	}
	
	/**
	 * This action generates the DAOs and Beans for the TDBM service passed in parameter. 
	 * 
	 * @Action
	 * @param $name
	 * @param $selfedit
	 */
	public function generate($name, $daofactoryclassname, $daofactoryinstancename, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$url = "http://".$_SERVER['SERVER_NAME'].ROOT_URL."plugins/database/tdbm/2.0-alpha/generateDaos.php?name=".urlencode($name)."&selfedit=".$selfedit."&daofactoryclassname=".$daofactoryclassname;
		$response = self::performRequest($url);
		
		/*if (trim($response) != "") {
			throw new Exception($response);
		}*/
		
		$xmlRoot = simplexml_load_string($response);
		
		if ($xmlRoot == null) {
			throw new Exception("An error occured while retrieving message: ".$response);
		}

		$this->moufManager->declareComponent($daofactoryinstancename, $daofactoryclassname);
		
		foreach ($xmlRoot->table as $table) {
			$daoName = TDBMDaoGenerator::getDaoNameFromTableName($table);
			$this->moufManager->addRegisteredComponentFile("dao/".$daoName.".php");

			$instanceName = TDBMDaoGenerator::toVariableName($daoName);
			$this->moufManager->declareComponent($instanceName, $daoName);
			$this->moufManager->bindComponentViaSetter($instanceName, "setTdbmService", $name);

			$this->moufManager->bindComponentViaSetter($daofactoryinstancename, "set".$daoName, $instanceName);
		}
		
		$this->moufManager->addRegisteredComponentFile("dao/".$daofactoryclassname.".php");
		
		$this->moufManager->rewriteMouf();
		
		// TODO: better: we should redirect to a screen that list the number of DAOs generated, etc...
		header("Location: ".ROOT_URL."mouf/instance/?name=".urlencode($name)."&selfedit=".$selfedit);
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