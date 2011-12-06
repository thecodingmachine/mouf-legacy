<?php
/**
 * @Component
 * @author Kevin
 *
 */
class MoufImageFromFile implements MoufImageInterface{

	
	/**
	 * The absolute path of the image file
	 * @Property
	 * @var string $path
	 */
	public $path;
	
	private $image = null;
	
	/**
	 * Get the GD Image resource loaded
	 * @return $image, the GD resource image
	 */
	public function getResource(){
		$image_info = getimagesize($this->path);
		
		
		
		$image_type = $image_info[2];
		if( $image_type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($this->path);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($this->path);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($this->path);
		}
		
		return $this->image;
	}
}