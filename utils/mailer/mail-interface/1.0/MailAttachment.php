<?php
/**
 * This class represents an attachement in a mail.
 *
 * @Component
 */
class MailAttachment implements MailAttachmentInterface {

	private $content;
	private $fileName;
	private $mimeType = 'application/octet-stream';
	private $encoding = 'ENCODING_BASE64';
	private $attachmentDisposition = 'attachment';
	private $contentId;
	
	/**
	 * Returns the content of the file to attach, as an octet stream.
	 *
	 * @return string
	 */
	public function getFileContent() {
		return $this->content;
	}
	
	/**
	 * The content of the file to attach, as an octet stream.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $content
	 */
	public function setFileContent($content) {
		$this->content = $content;
	}
	
	/**
	 * Returns the name of the file in the mail.
	 *
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}
	
	/**
	 * The name of the file in the mail.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $fileName
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}
	
	/**
	 * Returns the mime-type of the attachement
	 *
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}
	
	/**
	 * The mime-type of the attachement
	 *
	 * @Property
	 * @Compulsory
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}
	
	/**
	 * Returns the encoding.
	 * Can be one of: "ENCODING_7BIT", "ENCODING_8BIT", "ENCODING_QUOTEDPRINTABLE", "ENCODING_BASE64"
	 * 
	 * @return string
	 */
	public function getEncoding() {
		return $this->encoding;
	}
	
	/**
	 * The encoding of the attachement
	 *
	 * @Property
	 * @Compulsory
	 * @OneOf("ENCODING_7BIT", "ENCODING_8BIT", "ENCODING_QUOTEDPRINTABLE", "ENCODING_BASE64")
	 * @param string $encoding
	 */
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	
	
	/**
	 * Returns the attachement disposition.
	 * Can be one of: "attachment", "inline"
	 *
	 * @return string
	 */
	public function getAttachmentDisposition() {
		return $this->attachmentDisposition;
	}
	
	/**
	 * The encoding of the attachement
	 *
	 * @Property
	 * @Compulsory
	 * @OneOf("attachment", "inline")
	 * @param string $attachmentDisposition
	 */
	public function setAttachmentDisposition($attachmentDisposition) {
		$this->attachmentDisposition = $attachmentDisposition;
	}
	
	
	/**
	 * Returns the content-id of the mail attachment.
	 * The content-id is required if you want to embed an image in a mail.
	 * If so, you will use the syntax: <img src="cid:XXX" /> to display the image.
	 * 
	 * @return string
	 */
	public function getContentId() {
		return $this->contentId;
	}
	
	/**
	 * The content-id of the mail attachment.
	 * The content-id is required if you want to embed an image in a mail.
	 * If so, you will use the syntax: <img src="cid:XXX" /> to display the image.
	 *
	 * @Property
	 * @Compulsory
	 * @param string $contentId
	 */
	public function setContentId($contentId) {
		$this->contentId = $contentId;
	}
	
	
}
?>