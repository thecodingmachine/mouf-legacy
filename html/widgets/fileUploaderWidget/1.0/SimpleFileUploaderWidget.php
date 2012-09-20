<?php

/**
 * This class extends FileUploaderWidget.
 * It pre save the file in temp folder
 *
 * @Component
 */
class SimpleFileUploaderWidget extends FileUploaderWidget {

	/**
	 * Number of fields displayed
	 *
	 * @var int
	 */
	private static $simpleCount = 0;

	/**
	 * Name of hidden input to retrieve files
	 *
	 * @Property
	 * @var string
	 */	
	public $inputName;

	/**
	 * Activate it if you want only one file in the upload.
	 *
	 * @Property
	 * @var bool
	 */
	public $onlyOneFile = false;
	
	/**
	 * Renders the object in HTML.
	 * The Html is echoed directly into the output.
	 *
	 */
	public function toHtml() {
		// Retrieve static value in parent to display a single element by function call
		$count = parent::$count + 1;
		
		if(!$this->inputName)
			throw new MoufException('Please add a input name in your instance of SimpleFileUploaderWidget');
		
		echo '<script type="text/javascript">';
		// Add JS to save the temp folder
		echo 'function simpleFileUploadWidgetOnComplete_'.$this->inputName.'_'.$count.'(id, fileName, responseJSON) {
				document.getElementById("'.$this->inputName.'").value = responseJSON.targetFolder;
				'.($this->onComplete?$this->onComplete.'(id, fileName, responseJSON);':'').'
				}';
		// Add JS if only one file can be send. This remove the upload list
		if($this->onlyOneFile) {
			echo 'function simpleFileUploadWidgetOnSubmit_'.$this->inputName.'_'.$count.'(id, fileName) {
						document.getElementById("mouf_fileupload_'.$count.'").getElementsByTagName("div")[0].getElementsByTagName("ul")[0].innerHTML = "";
					}';
		}
		echo '</script>';
		
		// Add listener
		$this->onComplete = 'simpleFileUploadWidgetOnComplete_'.$this->inputName.'_'.$count;
		if($this->onlyOneFile)
			$this->onSubmit = 'simpleFileUploadWidgetOnSubmit_'.$this->inputName.'_'.$count;
		
		// Add hidden input
		echo '<input type="hidden" name="'.$this->inputName.'" value="" id ="'.$this->inputName.'" />';
		
		// Save parameters to retrieve data in ajax call back
		$this->setParams(array('input' => $this->inputName, 'random' => time().rand(1,9999999)));
		
		
		// Call parent toHtml
		parent::toHtml();
	}
	
	/**
	 * Call all listener before upload file.
	 *
	 * @param string $targetFile The final path of the uploaded file. When the afterUpload method is called, the file is there.
	 *  * @param string $fileName The final name of the uploaded file. When the beforeUpload method is called, the file is not yet there. In this function, you can change the value of $fileName since it is passed by reference
	 * @param string $fileId The fileId that was set in the uploadify widget (see FileUploadWidget::fileId)
	 * @param array $result The result array that will be returned to the page as a JSON object.
	 * @param string $uniqueId Unique id of file uploader form.
	 */
	public function triggerBeforeUpload(&$targetFile, &$fileName, $fileId, array &$returnArray, $uniqueId) {
		Mouf::getSessionManager()->start();
		
		// Retrieve temp folder to save file uploaded
		if(isset($_SESSION["mouf_simplefileupload_folder"][$parameters['input'].$parameters['random']]))
			$folderName = $_SESSION["mouf_simplefileupload_folder"][$parameters['input'].$parameters['random']];
		else {
			$folderName = time().rand(1, 9999999);
			$_SESSION["mouf_simplefileupload_folder"][$parameters['input'].$parameters['random']] = $folderName;
		}
		
		// Temp folder
		$sysFolder = sys_get_temp_dir();
		if(strrpos($sysFolder, '/') != (strlen($sysFolder) - 1))
			$sysFolder = $sysFolder.'/';
		$this->directory = $sysFolder.'simplefileupload/'.$folderName.'/';
		// If only one file can be send, remove other file in temp file
		if($this->onlyOneFile) {
			if(is_dir($this->directory)) {
				if ($dh = opendir($this->directory)) {
					while ($file = readdir($dh)) {
						if (is_file($this->directory."/".$file)) {
							unlink($this->directory."/".$file);
						}
					}
				}
			}
		}
		// Change target in FileUploaderWidget
		$targetFile = $this->directory;
		
		// Add to return value in JS
		$returnArray['targetFolder']=$folderName;
		
		parent::triggerBeforeUpload($targetFile, $fileName, $fileId, $returnArray, $uniqueId);
	}
	
	
	
	/**
	 * After saved, call this function to retrieve your file.
	 * @param string $inputName Input name of the hidden field
	 * @param string $folderDest Destination folder name
	 * @return array List of the file moved
	 */
	public function moveFile($inputName, $folderDest) {
		// Search [] to retrieve an array if the input is it
		if(strrpos($inputName, '[]') == (strlen($inputName) - 2))
			$inputName = substr($inputName, 0, strlen($inputName) - 2);
		
		// Create folder destination if don t exist
		if(!is_dir($folderDest))
			mkdir($folderDest, '0777', true);
		
		// retrieve value of input
		$values = get($inputName);
		// if array
		if(is_array($values)) {
			$fileList = array();
			// move all element in each value
			foreach ($values as $value) {
				$fileList = array_merge($fileList, $this->moveFileOfFolder($value, $folderDest));
			}
		}
		else
			$fileList = $this->moveFileOfFolder($values, $folderDest);
		return $fileList;
	}
	
	/**
	 * Check if there is file in temp folder
	 * @param string $inputName Input name of the hidden field
	 * @return array List of the file in temp folder
	 */
	public function hasFileToMove($inputName) {
		if(strrpos($inputName, '[]') == (strlen($inputName) - 2))
			$inputName = substr($inputName, 0, strlen($inputName) - 2);
		
		$values = get($inputName);
		if(is_array($values)) {
			$fileList = array();
			foreach ($values as $value) {
				$fileList = array_merge($fileList, $this->hasFileToMoveOfFolder($value));
			}
		}
		else
			$fileList = $this->hasFileToMoveOfFolder($values);
		return $fileList;
	}
	

	/**
	 * Check if there is file in temp folder
	 * @param string $inputName Input name of the hidden field
	 * @return array List of the file in temp folder
	 */
	private function hasFileToMoveOfFolder($value) {
		$fileList = array();
		$sysFolder = sys_get_temp_dir();
		if(strrpos($sysFolder, '/') != (strlen($sysFolder) - 1))
			$sysFolder = $sysFolder.'/';
		$directory = $sysFolder.'simplefileupload/'.$value;
		if(is_dir($directory)) {
			if ($dh = opendir($directory)) {
				while ($file = readdir($dh)) {
					if (is_file($directory."/".$file)) {
						$fileList[] = $file;
					}
				}
			}
			else
				return false;
		}
		else
			return false;
		return $fileList;
	}
	
	
	/**
	 * Move the file of folder
	 * @param string $value Value of the hidden field
	 * @param string $folderDest Destination folder name
	 * @return false if there is a problem on move function
	 */
	private function moveFileOfFolder($value, $folderDest) {
		$fileList = array();
		// retrieve temp file
		$sysFolder = sys_get_temp_dir();
		if(strrpos($sysFolder, '/') != (strlen($sysFolder) - 1))
			$sysFolder = $sysFolder.'/';
		$directory = $sysFolder.'simplefileupload/'.$value;
		if(is_dir($directory)) {
			if ($dh = opendir($directory)) {
				while ($file = readdir($dh)) {
					if (is_file($directory."/".$file)) {
						// if the file destination already exist
						if(is_file($folderDest."/".$file)) {
							// if the option replace is actived
							if($this->replace) {
								// remove old file
								unlink($folderDest."/".$file);
								// move file
								$return = rename($directory."/".$file, $folderDest."/".$file);
							} else {
								// add random number to create new file
								$fileOld = $file;
								$dotPos = strrpos($file, '.');
								$file = substr($file, 0, $dotPos).'_'.rand(1,999999).substr($file, $dotPos, strlen($file) - $dotPos);
								// move file
								$return = rename($directory."/".$fileOld, $folderDest."/".$file);
							}
						} else
							$return = rename($directory."/".$file, $folderDest."/".$file);
						$fileList[] = $file;
					}
				}
			}
			else
				return false;
			rmdir($directory);
		}
		else
			return false;
		return $fileList;
	}
}
?>