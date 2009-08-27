<?php
/**
 * This class contains settings used to connect to a PostgreSQL database.
 * It will not perform the connection itself. It contains only the settings.
 *
 * @Component
 */
class DB_PGSQLConnectionSettings implements DB_ConnectionSettings {

	/**
	 * The host for the database.
	 * This is the IP or the URL of the server hosting the database.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $host;
	
	/**
	 * The port for the database.
	 * Keep empty to use default port.
	 *
	 * @Property
	 * @var int
	 */
	public $port;
	
	/**
	 * Database user to use when connecting.
	 *
	 * @Property
	 * @var string
	 */
	public $user;
	
	/**
	 * Password to use when connecting.
	 *
	 * @Property
	 * @var string
	 */
	public $password;
	
	/**
	 * Charset used to communicate with the database.
	 * The database will translate any string into this charset before sending us the string.
	 * If not set, this will default to UTF-8
	 *
	 * @Property
	 * @var string
	 */
	public $charset;
	
	/**
	 * Whether a persistent connection is used or not.
	 * If this application is used on the web, you should choose yes. The database connection
	 * will not be closed when the script stops and will be reused on the next connection.
	 * This will help improve your application's performance. 
	 *
	 * This defaults to "true"
	 * 
	 * @Property
	 * @var boolean
	 */
	public $isPersistentConnection;
	
	/**
	 * Returns the DSN for this connection.
	 *
	 * @return string
	 */
	public function getDsn() {
		$dsn = "pgsql:dbname=".$this->host.";";
		if (!empty($this->port)) {
			$dsn .= "port=".$this->port.";";
		}
		$charset = $this->charset;
		if (empty($charset)) {
			$charset = "UTF-8";
		}
		$dsn .= "charset=".$charset.";";
		
		return $dsn;
	}

	/**
	 * Returns the username for this connection (if any).
	 *
	 * @return string
	 */
	public function getUserName() {
		return $this->user;
	}

	/**
	 * Returns the password for this connection (if any).
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * Returns an array of options to apply to the connection.
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = array();
		if ($isPersistentConnection != "No") {
			$options[PDO::ATTR_PERSISTENT] = true;
		}
		return $options;
	}
}
?>