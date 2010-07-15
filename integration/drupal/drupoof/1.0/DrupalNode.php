<?php

/**
 * A typical Drupal node, represented as a Mouf component.
 * It does NOT contain the NID or VID (since those are system specific).
 * It does NOT contain either a user id.
 * 
 * @Component
 */
class DrupalNode {
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $type;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $language;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $status;
	
	/**
	 * The timestamp the node was created
	 * 
	 * @Property
	 * @var timestamp
	 */
	public $created;

	/**
	 * The timestamp the node was changed
	 * 
	 * @Property
	 * @var timestamp
	 */
	public $changed;
	
	/**
	 * Whether comments are disabled / read only / read-write 
	 * 
	 * @Property
	 * @var int
	 */
	public $comment;
	
	/**
	 * 
	 * @Property
	 * @var boolean
	 */
	public $promote;
	
	/**
	 * 
	 * @Property
	 * @var boolean
	 */
	public $moderate;
	
	/**
	 * 
	 * @Property
	 * @var boolean
	 */
	public $sticky;
	
	/**
	 * 
	 * @Property
	 * @var boolean
	 */
	public $translate;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $title;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $body;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $teaser;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $log;

	/**
	 * 
	 * @Property
	 * @var timestamp
	 */
	public $revision_timestamp;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $format;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $name;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $picture;
	
	/**
	 * 
	 * @Property
	 * @var string
	 */
	public $data;
	
	/**
	 * Any non standard propertyu in the node stdClass representation goes here.
	 * 
	 * @Property
	 * @var string
	 */
	public $otherData = array();
	
	/**
	 * The name of the  Mouf instance.
	 * 
	 * @var string
	 */
	public $instanceName;
	
	/**
	 * Inits this class from a Drupal node object (stdClass used internally by Drupal)
	 * 
	 * @param stdClass $node
	 */
	public function initFromNode(stdClass $node) {
		$this->instanceName = $node->drupoof_instancename;
		
		$directProperties = array("type", "language", "status", "created", "changed", "comment", "promote", 
									"moderate", "sticky", "translate", "title", "body", "teaser", "log", "revision_timestamp", "format", "name",
									"picture", "data");
		
		foreach (get_object_vars($node) as $propertyName => $propertyValue) {
			if (array_search($propertyName, $directProperties) === false) {
				if ($propertyName != "nid") {
					$this->otherData[$propertyName] = $propertyValue;
				}
			} else {
				$this->$propertyName = $propertyValue;
			}
		}		
	}
	
	/**
	 * Saves the object into MoufComponents.php.
	 */
	public function saveToMouf($instanceName) {
		
		$moufManager = MoufManager::getMoufManager();
		
		// Delete the old instance
		if (isset($this->instanceName)) {
			$moufManager->removeComponent($this->instanceName);
		}
		
		// Create a new instance (note: the name could be different)
		$moufManager->declareComponent($instanceName, get_class($this));
		
		$allProperties = array("type", "language", "status", "created", "changed", "comment", "promote", 
									"moderate", "sticky", "translate", "title", "body", "teaser", "log", "revision_timestamp", "format", "name",
									"picture", "data", "otherData");
		
		foreach ($allProperties as $directPropertyName) {
			$moufManager->setParameter($instanceName, $directPropertyName, $this->$directPropertyName, "string");
		}
		
		$moufManager->rewriteMouf();
		
	}
	
	/**
	 * Puts this node in the Drupal database.
	 * 
	 */
	public function saveToDrupalDb() {
		
		$instanceName = MoufManager::getMoufManager()->findInstanceName($this);
		
		// First, let's build the stdClass.
		$node = (object) $this->otherData;
		
		$directProperties = array("type", "language", "status", "created", "changed", "comment", "promote", 
									"moderate", "sticky", "translate", "title", "body", "teaser", "log", "revision_timestamp", "format", "name",
									"picture", "data");
		
		foreach ($directProperties as $propertyName) {
			$node->$propertyName = $this->$propertyName;
		}
		
		// Let's get back the nid and vid from database.
		$arr = drupoof_db_get_node_id_from_instance_name($instanceName);
		if ($arr != null) {
			list($nid, $vid) = $arr;
			$node->nid = $nid;
			$node->vid = $vid;
		}
		
		
		// Ok, $node is initialiazed.
		// Now, let's write it to Drupal DB
		
		// The save method will trigger the update or insert hooks. We don't want the trigger to be triggered, so we set a variable to prevent this in the node.
		$node->drupoof_is_updating_from_drupoof = true;
		
		node_save($node);
		
		// Note: node_save is populating the node with nid and vid (I hope)
		drupoof_db_set_instance_name_for_node($node->nid, $node->vid, $instanceName);
		
		// Last step: let's override the "changed date" to put it back to OUR value.
		// Indeed, the changed date is set to the current date in "node_save". This is not what we want.
		drupoof_db_set_node_changed_date($node->nid, $node->vid, $this->changed);
	}
}

?>