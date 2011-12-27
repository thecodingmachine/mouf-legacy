<?php
/**
 * This Class will handle the display of MoufImages. 
 * Images are successiely treated by a set of MoufImageInterafce instances, and then, the final image resource will be outputed.
 * The first time the image is generated, it is saved in order to save some time.
 * The StaticImageDisplayer is called by using a direct URL inside this package: 
 *   ROOT_URL/plugins/utils/graphics/moufimage/1.0/direct/displayImage.php
 *   This URL should be called using 2 parameters:
 *       - instance: name of the StaticImageDisplayer instance 
 *       - path: relative path of the original image
 *       
 *  This class has helpers that will generate the given URL: 
 *    - $displayerInstance->getURL($path);
 *    or 
 *    - $displayerInstance->toHTML($path);
 * 
 * @Component
 * 
 * @author Kevin
 *
 */
class StaticImageDisplayer{
	
	/**
	 * The name of the MoufImageFromFile instance that will be the first to load the image from the given input $sourceFileName
	 * @Property
	 * @Compulsory
	 * @var MoufImageFromFile $initialImageFilter
	 */
	public $initialImageFilter;
	
	/**
	 * The MoufImage instance's name that delivers the final image resource, with all applied effects
	 * @Property
	 * @Compulsory
	 * @var MoufImageInterface $imageSource
	 */
	public $imageSource;
	
	/**
	 * The pathe into which the image file will be saved if it doesn't exist.
	 * This path is relative to the applcation's ROOT_PATH, and souhld have trailing slashes.
	 * @Property
	 * @Compulsory
	 * @var string $savePath
	 */
	public $savePath;
	
	/**
	 * The path to the original image that will be loaded (by the $initialImageFilter, transformed by a set of MoufImage instances.
	 * This path is relative to the applcation's ROOT_PATH, and souhld have trailing slashes.
	 * @Property
	 * @Compulsory
	 * @var string $basePath
	 */
	public $basePath;
	
	
	/**
	 * The path to the original image file, relative to the $basePath.
	 * The file name should not contain any '..' strings (for security reasons, the component dosn't allow users to access files outside the $basePath),
	 * but it may contain folders (ex: sub_folder/image.jpeg).
	 * @var string $sourceFileName
	 */
	public $sourceFileName;
	
	/**
	 * The Quality that should be applied in case the original image is of JPEG type (0 to 100).
	 * @Property
	 * @var int $jpegQuality
	 */
	public $jpegQuality = 75;
	
	/**
	 * The Quality that should be applied in case the original image is of PNG type (0 to 9).
	 * @Property
	 * @var int $pngQuality
	 */
	public $pngQuality = 6;
	
	/**
	 * Output the image: 
	 *   - original image is loaded by the $initialImageFilter, 
	 *   - final image (given by the $imageSource) is outputed (and saved if it doesn't exist yet)
	 * @throws Exception
	 */
	public function outputImage(){
		//Prevent from acessing parent folders
		if (strpos($this->sourceFileName, '..')) throw new Exception("Trying to access file in parent folders : '$sourceFileName'");
		
		//rebuild the original file pathe from the root image folder and the relative file's pathe
		$originalFilePath = ROOT_PATH . $this->basePath . DIRECTORY_SEPARATOR . $this->sourceFileName;
		if (!file_exists($originalFilePath)) throw new Exception("Original file doesn't exist : '$originalFilePath'");
		$this->initialImageFilter->path = $originalFilePath;
		
		//Get the image after all effects have been applied
		$moufImageResource = $this->imageSource->getResource();
		$finalImage = $moufImageResource->resource;
		$image_info = $moufImageResource->originInfo;
		$image_type = $image_info[2];
		
		//Originakl file's relative pat is teh file's Key, so no need to check whether there is already an image with the same file name
		$finalPath = ROOT_PATH . $this->savePath . DIRECTORY_SEPARATOR . $this->sourceFileName;
		
		
		$created = true;
		if (!file_exists($finalPath)){
			//if sourceFileName contains sub folders, create them
			$subPath = dirname($this->sourceFileName);
			if ($subPath != '.'){
				$dirCreate = mkdir(ROOT_PATH . $this->savePath . DIRECTORY_SEPARATOR . $subPath, 0777, true);
				if (!$dirCreate) throw new Exception("Could't create subfolders '$subPath' in " . ROOT_PATH . $this->savePath);
			}
			
			//create the image
			if( $image_type == IMAGETYPE_JPEG ) {
				$created = imagejpeg($finalImage, $finalPath, $this->jpegQuality);
			} elseif( $image_type == IMAGETYPE_GIF ) {
				$created = imagegif($finalImage, $finalPath);
			} elseif( $image_type == IMAGETYPE_PNG ) {
				$created = imagepng($finalImage, $finalPath, $this->pngQuality);
			}
		}
		
		if (!$created) throw new Exception("File could not be created: $finalPath");
		
		if( $image_type == IMAGETYPE_JPEG ) {
			header('Content-Type: image/jpeg');
			imagejpeg($finalImage);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			header('Content-Type: image/gif');
			imagegif($finalImage);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			header('Content-Type: image/jpeg');
			imagepng($finalImage);
		}
		imagedestroy($finalImage);
	}
	
	public function getURL($path){
		return ROOT_URL. $this->savePath . "/" . $path;
	}
	
	public function toHTML($path){
		echo "<img src='" .ROOT_URL. $this->savePath . "/" . $path . "'/>";
	}
}