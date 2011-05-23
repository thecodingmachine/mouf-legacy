<?php
/**
 * The controller to generate automatically the stats table.
 * 
 * @Component
 */
class DbStatsController extends AbstractMoufInstanceController {
	
	
	/**
	 * Admin page used to create the stats table.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/dbStats.php", $this);
		$this->template->draw();
	}
	
	/**
	 * This action generates the DAOs and Beans for the TDBM service passed in parameter. 
	 * 
	 * @Action
	 * @param string $name
	 * @param string $selfedit
	 */
	public function generate($name, $dropIfExist = "false", $selfedit="false") {
		$this->initController($name, $selfedit);
						
		$url = MoufReflectionProxy::getLocalUrlToProject()."plugins/database/dbstats/2.0/direct/generateStatTable.php?name=".urlencode($name)."&selfedit=".$selfedit."&dropIfExist=".$dropIfExist;
		$response = self::performRequest($url);
		
		if (trim($response) != "") {
			throw new Exception($response);
		}
		
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