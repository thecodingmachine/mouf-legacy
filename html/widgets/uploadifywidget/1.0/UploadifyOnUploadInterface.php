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
	 * @param string $tmpFile The temporary path to the uploaded file.
	 * @param string $destFile The final path of the uploaded file. When the onUpload method is called, the file is not yet there.
	 * @param string $fileId The fileId that was set in the uploadify widget (see UploadifySingleFileWidget::fileId)
	 * @param UploadifySingleFileWidget $widget
	 * @return boolean Return false to cancel the upload
	 */
	public function onUpload($tmpFile, $destFile, $fileId, UploadifySingleFileWidget $widget);
}