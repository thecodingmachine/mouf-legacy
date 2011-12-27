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
	 * If the image should keep it's original ratio.
	 * If true, the resize will be done so the resulting image fits into the dimensions.
	 * @Property
	 * @Compulsory
	 * @var boolean $keepRatio
	 */
	public $keepRatio = true;
	
	/**
	 * If the image may be enlarged. If not, and the image is smaller than the target rectangle, the image won't be resized.
	 * @Property
	 * @Compulsory
	 * @var boolean $allowEnlarge
	 */
	public $allowEnlarge = false;
	
	/**
	 * Get the GD Image resource after effect has been applied
	 * @return $image, the GD resource image
	 */
	public function getResource(){
		$moufImageResource = $this->source->getResource();
		
		$imageResource = $moufImageResource->resource;
		$imgInfo = $moufImageResource->originInfo;

		$oHeight = imagesy($imageResource);
		$oWidth = imagesx($imageResource);
		
		if (!$this->allowEnlarge && $oHeight < $this->height && $oWidth < $this->width){
			//do Nothing : the image is smaller than the target rectangle, so it should remain unchanged
			$newWidth = $oWidth;
			$newHeight = $oHeight;
		}else if ($this->keepRatio){
			//image doesn't fit the target rectangle (at least on dimension is greater), the final ratio is the one that make the resized image fit the target rectangle
			$xRation = (float) $oWidth / $this->width;
			$yRation = (float) $oHeight / $this->height;
			
			$finalRatio = max(array($xRation, $yRation));
			
			$newWidth = $oWidth / $finalRatio;
			$newHeight = $oHeight / $finalRatio;
				
		}else{
			//Simply apply a stupid resize, whater the original image's ratio is
			$newWidth = $this->width;
			$newHeight = $this->height;
		}
		
		
		
		$new_image = imagecreatetruecolor($newWidth, $newHeight);
		
		//If image id of type PNG or GIF, presenve Transprency
		if(($imgInfo[2] == 1) || ($imgInfo[2]==3)){
			imagealphablending($new_image, false);
			imagesavealpha($new_image,true);
			$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
			imagefilledrectangle($new_image, 0, 0, $newWidth, $newHeight, $transparent);
		}
		
		imagecopyresampled($new_image, $imageResource, 0, 0, 0, 0, $newWidth, $newHeight, $oWidth, $oHeight);
		
		$moufImageResource->resource = $new_image;
		
		return $moufImageResource;
	}
	
}