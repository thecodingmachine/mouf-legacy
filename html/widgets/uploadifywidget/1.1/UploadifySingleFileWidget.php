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
	 * <p>For instance: *.jpg;*.gif;*.png</p>
	 * 
	 * @Property
	 * @var string
	 */
	public $fileExtensions;
	
	
	/**
	 * The $fileDescription option sets the text that will appear in the file type drop down in the file selection system window.
	 * <p>This option is required when using the $fileExtensions option.
	 * For instance: 'Web Image Files (.JPG, .GIF, .PNG)'</p>
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
	 * You can of course set this value dynamically, in your code, using
	 * <pre>$instance->directory = "my/directory";</pre>
	 * 
	 * @Property
	 * @var string
	 */
	public $directory;
	
	/**
	 * The destination file name for the file to be written.
	 * This is a unique file name and cannot contain "/".
	 *
	 * Most of the time, you will set this value dynamically, in your code, using
	 * <pre>$instance->fileName = "myFileName.ext";</pre>
	 * 
	 * If not set, the name of the file provided by the user is used instead.
	 * 
	 * @Property
	 * @var string
	 */
	public $fileName;
	
	/**
	 * If you want to trigger some code when the file is uploaded, you will need to give the file a unique ID.
	 * You should set this ID programmatically, using:
	 * <pre>$instance->fileId = $myId;</pre>
	 * Then, you should register a listener that will be triggered when the file is uploaded (see the "listeners"
	 * property). The ID will be passed to the listener when an upload is completed.
	 * 
	 * @Property
	 * @var string
	 */
	public $fileId;
	
	/**
	 * A list of instances that will be notified when an upload occurs.
	 * To be registered, an instance should implement the UploadifyOnUpoadInterface interface.
	 * 
	 * @Property
	 * @var array<UploadifyOnUploadInterface>
	 */
	public $listeners;
	
	/**
	 * The name of the javascript function to trigger on upload completed.
	 * See http://www.uploadify.com/documentation/events/oncomplete-2/ for more information.
	 * 
	 * @Property
	 * @var string
	 */
	public $onCompleteJavascriptFunction;
	
	/**
	 * 
	 * Unique id generate to retrieve the session
	 * @var int
	 */
	private $uniqueId;
	
	protected function getParameters() {
		$version = basename(dirname(__FILE__));
		
		$thisInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
		
		$scriptDataArray = array("uniqueId"=>$this->uniqueId,
								"sessionName"=>session_name(),
								"sessionId"=>session_id(),
								"path" =>$this->getUploadedFilePath(),
								"fileId" =>$this->fileId,
								"instanceName" =>$thisInstanceName);
		
		$parameters = array();
		$parameters['uploader'] = '"'.ROOT_URL.'plugins/javascript/jquery/jquery.uploadify/2.1.0/uploadify.swf"';
		$parameters['script'] = '"'.ROOT_URL.'plugins/html/widgets/uploadifywidget/'.$version.'/direct/upload.php?'.htmlspecialchars(session_name()."=".session_id()).'"';
		$parameters['cancelImg'] = '"'.ROOT_URL.'plugins/javascript/jquery/jquery.uploadify/2.1.0/cancel.png"';
		$parameters['scriptData'] = json_encode($scriptDataArray);
	
		if (!empty($this->fileExtensions)) {
			$parameters['fileExt'] = '"'.plainstring_to_htmlprotected($this->fileExtensions).'"';
			$parameters['fileDesc'] = '"'.plainstring_to_htmlprotected($this->fileDescription).'"';
		}
		if (!empty($this->onCompleteJavascriptFunction)) {
			$parameters['onComplete'] = $this->onCompleteJavascriptFunction;
		}
		$parameters['auto'] = 'true';
		
		return $parameters;
	}
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	public function toHtmlElement() {
		self::$count++;
		$this->uniqueId = rand();
		
		$id = $this->id;
		if (!$id) {
			$id = "mouf_uploadify_".self::$count;
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
		
		$parameters = $this->getParameters();
		
		echo '<script type="text/javascript">jQuery(function() {
			jQuery( "#'.plainstring_to_htmlprotected($id).'" ).uploadify({'."\n";
		$first = true;
		foreach ($parameters as $key => $value) {
			if($first)
				$first = false;
			else
				echo ','."\n";
			echo '"'.$key.'" : '.$value;
		}
		echo '	});
		});</script>';
		
		if (BaseWidgetUtils::isWidgetEditionEnabled()) {
			$manager = MoufManager::getMoufManager();
			$instanceName = $manager->findInstanceName($this);
			if ($instanceName != false) {
				echo " <a href='".ROOT_URL."mouf/mouf/displayComponent?name=".urlencode($instanceName).BaseWidgetUtils::getBackToParameter()."'>Edit</a>\n";
			}
		}
		
		$thisInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
		
		// Start a session using the session manager.
		Mouf::getSessionManager()->start();
		$_SESSION["mouf_uploadify_autorizeduploads"][$this->uniqueId] = array("path"=>$this->getUploadedFilePath(),
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