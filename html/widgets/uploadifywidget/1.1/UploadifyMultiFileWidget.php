<?php

/**
 * This class represent an HTML/Flash file upload widget enabling the upload of a single file.
 *
 * @Component
 */
class UploadifyMultiFileWidget extends UploadifySingleFileWidget {
		
	protected function getParameters() {
		$parameters = parent::getParameters();
		$parameters['multi'] = 'true';
		
		return $parameters;
	}
}
?>