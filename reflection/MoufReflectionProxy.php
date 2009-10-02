<?php
require_once 'MoufXmlReflectionClass.php';

/**
 * Class specialized in forwarding a reflexion request to another script that will perform it.
 * It is useful to perform reflexion in a separate script because in another script, the 
 * context of the Mouf management is not loaded.
 *
 */
class MoufReflectionProxy {

	/**
	 * Returns a MoufXmlReflectionClass representing the class we are going to analyze.
	 *
	 * @param string $className
	 * @param boolean $selfEdit
	 * @return MoufXmlReflectionClass
	 */
	public static function getClass($className, $selfEdit) {
		$url = "http://".$_SERVER['SERVER_NAME'].ROOT_URL."mouf/direct/get_class.php?class=".$className."&selfedit=".(($selfEdit)?"true":"false");
		
		$response = self::performRequest($url);
		
		return new MoufXmlReflectionClass($response);
	}
	
	/**
	 * Returns a list of all the components that are of a class that extends or implements $baseClass
	 *
	 * @param string $baseClass The class or interface name.
	 * @return array<string>
	 */
	public static function getInstances($baseClass, $selfEdit) {
		$url = "http://".$_SERVER['SERVER_NAME'].ROOT_URL."mouf/direct/get_instances.php?class=".$baseClass."&selfedit=".(($selfEdit)?"true":"false");
		
		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception($response);
		}
		
		return $obj;
	}
	
	public static function getComponentsList($selfEdit) {
		$url = "http://".$_SERVER['SERVER_NAME'].ROOT_URL."mouf/direct/get_components_list.php?selfedit=".(($selfEdit)?"true":"false");

		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception($response);
		}
		
		return $obj;
		
	}
	
	public static function getEnhancedComponentsList($selfEdit) {
		$url = "http://".$_SERVER['SERVER_NAME'].ROOT_URL."mouf/direct/get_enhanced_components_list.php?selfedit=".(($selfEdit)?"true":"false");

		$response = self::performRequest($url);

		$obj = unserialize($response);
		
		if ($obj === false) {
			throw new Exception($response);
		}
		
		return $obj;
		
	}
	
	/**
	 * Returns the default value for the property of the class, through a call to the "get_default.php" page. 
	 * 
	 * @param string $className
	 * @param string $propertyName
	 * @return mixed
	 */
	public static function getDefaultValue($className, $propertyName) {

		var_dump($_SERVER);
		exit;
		$url = ROOT_URL."mouf/direct/get_default.php";
		
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		curl_setopt( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $MSGPBX );
		
		if( curl_error($ch) ) { 
			$REPONSE = ERROR_CURL;
		} else {
			$REPONSE = curl_exec( $ch );
		}
		curl_close( $ch );
			
	
	
		return $value;
	}
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		//curl_setopt( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		//curl_setopt( $ch, CURLOPT_POST, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		
		if( curl_error($ch) ) { 
			throw new Exception("TODO: texte de l'erreur curl");
		} else {
			$response = curl_exec( $ch );
		}
		curl_close( $ch );
		
		return $response;
	}
}
?>