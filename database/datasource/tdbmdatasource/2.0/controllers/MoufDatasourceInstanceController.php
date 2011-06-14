<?php

/**
 * The controller to generate automatically the beans from the columns based on the SQL query.
 * 
 * @Component
 */
class MoufDatasourceInstanceController extends AbstractMoufInstanceController {

	protected $dsPrefix;
	
	/**
	 * Admin page used to enable or disable label edition.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->dsPrefix = $this->moufManager->getVariable("jqGrid.datasource.prefix.".$name);
		if ($this->dsPrefix == null) {
			$this->dsPrefix = $name.".columns";
		}
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/columnsGenerate.php", $this);
		$this->template->draw();
	}
	
	/**
	 * This action generates the columns instances for the jqGrid instance passed in parameter. 
	 * 
	 * @Action
	 * @param string $name
	 * @param bool $selfedit
	 */
	public function generate($name, $prefix, $selfedit="false") {
		$this->initController($name, $selfedit);

		$this->moufManager->setVariable("jqGrid.datasource.prefix.".$name, $prefix);
		
		$url = MoufReflectionProxy::getLocalUrlToProject()."plugins/database/datasource/tdbmdatasource/2.0/direct/generateColumnInstances.php?name=".urlencode($name)."&selfedit=".$selfedit."&prefix=".urlencode($prefix);

		$response = self::performRequest($url);
		
		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception("Unable to unserialize message:\n".$response."\n<br/>URL in error: <a href='".plainstring_to_htmlprotected($url)."'>".plainstring_to_htmlprotected($url)."</a>");
		}
		
		$columns = $obj;
		$columnInstances = array();
		foreach ($columns as $column=>$type) {
			$columnInstanceName = $prefix.".".$column;
			$columnInstances[] = $columnInstanceName;
			$this->moufManager->declareComponent($columnInstanceName, "DataSourceDBColumn");
			$this->moufManager->setParameterViaSetter($columnInstanceName, "setDbColumn", $column);
			$this->moufManager->setParameterViaSetter($columnInstanceName, "setType", $type);
			$this->moufManager->setParameterViaSetter($columnInstanceName, "setName", $column);
		}
		$this->moufManager->bindComponentsViaSetter($name, "setColumns", $columnInstances);
		
		$this->moufManager->rewriteMouf();
		
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