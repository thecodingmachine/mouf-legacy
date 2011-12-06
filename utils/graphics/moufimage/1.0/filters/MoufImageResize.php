<?php
/**
 * @Component
 * @author Kevin
 *
 */
class MoufImageResize implements MoufImageInterface{

	
	/**
	 * The Mouf Image that will be resized
	 * @Property
	 * @Compulsory
	 * @var MoufImageInterface $source
	 */
	public $source;
	
	/**
	 * The resize height in px
	 * @Property
	 * @Compulsory
	 * @var int $height
	 */
	public $height;
	
	/**
	 * The resize width in px
	 * @Property
	 * @Compulsory
	 * @var int $width
	 */
	public $width;
	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return $image, the GD resource image
	 */
	public function getResource(){
		$imageResource = $this->source->getResource();
		
		$oHeight = imagesy($imageResource);
		$oWidth = imagesx($imageResource);
		
		$xRation = (float) $oWidth / $this->width;
		$yRation = (float) $oHeight / $this->height;
		
		$finalRatio = max(array($xRation, $yRation));
		
		$newWidth = $oWidth / $finalRatio;
		$newHeight = $oHeight / $finalRatio;
		
		$new_image = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($new_image, $imageResource, 0, 0, 0, 0, $newWidth, $newHeight, $oWidth, $oHeight);
		
		return $new_image;
	}
	
}