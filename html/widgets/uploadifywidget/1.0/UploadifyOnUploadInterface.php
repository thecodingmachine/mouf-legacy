<?php

/**
 * Classes implementing the UploadifyOnUploadInterface interface can be notified when a file is uploaded through a UploadifySingleFileWidget.
 * These classes must be registered in the UploadifySingleFileWidget::listeners property.
 * 
 * @author David Negrier
 */
interface UploadifyOnUploadInterface {
	
	/**
	 * This method is called by an UploadifySingleFileWidget when an upload is complete.
	 * 
	 * <p>Please note the 5th parameter is passed in reference. It is a PHP array containing additional data
	 * to be passed back to the page. The PHP array will be converted to JSON and be sent to the page.
	 * You can put additional parameters in this array, and read those parameters in your page, using the
	 * onCompleteJavascriptFunction property that will trigger some Javascript function when the upload
	 * is complete, client-side.</p>
	 * 
	 * <p>The $result array will always contain one key:</p>
	 * <pre>$result = array("status"=>"error|ok")</pre>
	 * 
	 * @param string $tmpFile The temporary path to the uploaded file.
	 * @param string $destFile The final path of the uploaded file. When the onUpload method is called, the file is not yet there. In this function, you can change the value of $destFile since it is passed by reference
	 * @param string $fileId The fileId that was set in the uploadify widget (see UploadifySingleFileWidget::fileId)
	 * @param UploadifySingleFileWidget $widget
	 * @param array $result The result array that will be returned to the page as a JSON object.
	 * @param string $uploadedFileName The name of the uploaded file
	 * @return boolean Return false to cancel the upload
	 */
	public function onUpload($tmpFile, &$destFile, $fileId, UploadifySingleFileWidget $widget, array &$result, $uploadedFileName);
}