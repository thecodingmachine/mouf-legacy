<?php

/**
 * This class represent an HTML/Flash file upload widget enabling the upload of a single file.
 *
 * @Component
 */
class UploadifySingleFileWidget extends AbstractHtmlInputWidget {
		
	/**
	 * Number of fields displayed
	 *
	 * @var int
	 */
	private static $count = 0;
	
	
	/**
	 * The list of file extensions for the files to upload, separated by a ";".
	 * For instance: *.jpg;*.gif;*.png
	 * 
	 * @Property
	 * @var string
	 */
	public $fileExtensions;
	
	
	/**
	 * The $fileDescription option sets the text that will appear in the file type drop down in the file selection system window.
	 * This option is required when using the $fileExtensions option.
	 * For instance: 'Web Image Files (.JPG, .GIF, .PNG)'
	 *
	 * @Property
	 * @var string
	 */
	public $fileDescription;
	
	/**
	 * The destination directory for the file to be written. 
	 * If it does not start with "/", this is relative to ROOT_PATH.
	 * The directory is created if it does not exist.
	 * 
	 * @Property
	 * @var string
	 */
	public $directory;
	
	/**
	 * The destination file name for the file to be written.
	 * This is a unique file name and cannot contain "/".
	 *
	 * @Property
	 * @var string
	 */
	public $fileName;
	
	/**
	 * A unique ID attached to the file.
	 * You should set this ID programmatically.
	 * The ID will be passed to the listeners when an upload is completed.
	 * 
	 * @Property
	 * @var string
	 */
	public $fileId;
	
	/**
	 * A list of instances that will be notified when an upload occurs.
	 * 
	 * @Property
	 * @var array<UploadifyOnUpoadInterface>
	 */
	public $listeners;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	public function toHtmlElement() {
		self::$count++;
		$id = $this->id;
		if (!$id) {
			$id = "mouf_slider_".self::$count;
		}
		
		echo "<label for='".plainstring_to_htmlprotected($id)."'>\n";
		if ($this->enableI18nLabel) {
			eMsg($this->label);
		} else {
			echo $this->label;
		}
		echo "</label>\n";
		
		echo "<input type='file'";
		echo " id='".plainstring_to_htmlprotected($id)."'";
	
		if ($this->disabled) {
			echo ' disabled="disabled"';
		}
		
		echo " name='".plainstring_to_htmlprotected($this->name)."' />\n";

		$version = basename(dirname(__FILE__));
		
		$uniqueId = rand();
		$thisInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
		
		echo '<script type="text/javascript">jQuery(function() {
			jQuery( "#'.plainstring_to_htmlprotected($id).'" ).uploadify({
				  "uploader"  : "'.ROOT_URL.'plugins/javascript/jquery/jquery.uploadify/2.1.0/uploadify.swf",
				  "script"    : "'.ROOT_URL.'plugins/html/widgets/uploadifywidget/'.$version.'/direct/upload.php?'.htmlspecialchars(session_name()."=".session_id()).'",
				  "cancelImg" : "'.ROOT_URL.'plugins/javascript/jquery/jquery.uploadify/2.1.0/cancel.png",
				  "scriptData"    : {"uniqueId": "'.$uniqueId.'",
									 "sessionName": "'.session_name().'",
									 "sessionId": "'.session_id().'"},
			';
		if (!empty($this->fileExtensions)) {
			echo '"fileExt"   : "'.plainstring_to_htmlprotected($this->fileExtensions).'",';
			echo '"fileDesc"   : "'.plainstring_to_htmlprotected($this->fileDescription).'",';
		}
		echo '	  "auto"      : true
			});
		});</script>';
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit</a>\n";
			}
		}
		
		// Start a session using the session manager.
		Mouf::getSessionManager()->start();
		$_SESSION["mouf_uploadify_autorizeduploads"][$uniqueId] = array("path"=>$this->getUploadedFilePath(),
																		"fileId"=>$this->fileId,
																		"instanceName"=>$thisInstanceName);
	}
	
	/**
	 * Returns the complete absolute path to the file that will be uploaded.
	 * @return string
	 */
	public function getUploadedFilePath() {
		$directory = $this->directory;
		if (strpos($directory, '/') !== 0) {
			$directory = ROOT_PATH.$directory;
		}
		rtrim($directory, DIRECTORY_SEPARATOR);
		$directory .= DIRECTORY_SEPARATOR;
		$file = $directory.basename($this->fileName);
		$file = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $file);
		return $file;
	}
}
?>