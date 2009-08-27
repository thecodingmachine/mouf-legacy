<?php
/**
 * Class to transfer image data into an xml document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
stubClassLoader::load('net::stubbles::xml::xsl::stubXSLCallbackException',
                      'net::stubbles::xml::xsl::util::stubXSLAbstractCallback'
);
/**
 * Class to transfer image data into an xml document.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
class stubXSLImageDimensions extends stubXSLAbstractCallback
{
    /**
     * a list of file types where the key corresponds to the IMAGETYPE constants of PHP
     * 
     * @var  array<int,string>
     */
    protected $types = array('unknown', 'GIF', 'JPG', 'PNG', 'SWF', 'PSD', 'BMP',
                             'TIFF(intel byte order)', 'TIFF(motorola byte order)',
                             'JPC', 'JP2', 'JPX', 'JB2', 'SWC', 'IFF', 'WBMP', 'XBM'
                       );
    /**
     * path to images
     *
     * @var  string
     */
    protected $path            = null;

    /**
     * sets the path to the images
     *
     * @param  string  $path
     * @Inject
     * @Named('imagePath')
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * takes a dom attribute and return the image informations for the first one
     * 
     * @param   array<DOMAttr>|string  $imageFile
     * @return  DOMDocument
     * @throws  stubXSLCallbackException
     * @XSLMethod
     */
    public function getImageDimensions($imageFile)
    {
        $imageFileName = $this->parseValue($imageFile);
        if (null !== $this->path) {
            $imageFileName = $this->path . '/' . $imageFileName;
        }
        
        if (file_exists($imageFileName) == false) {
            throw new stubXSLCallbackException('Image ' . $imageFileName . ' does not exist.');
        }
        
        $image = @getimagesize($imageFileName);
        if (false === $image) {
            throw new stubXSLCallbackException('Image ' . $imageFileName . ' seems not to be an image, can not retrieve dimension data.');
        }
        
        $this->xmlStreamWriter->writeStartElement('image');
        $this->xmlStreamWriter->writeElement('width', array(), $image[0]);
        $this->xmlStreamWriter->writeElement('height', array(), $image[1]);
        $this->xmlStreamWriter->writeElement('type', array(), $this->getType($image[2]));
        $this->xmlStreamWriter->writeElement('mime', array(), $image['mime']);
        $this->xmlStreamWriter->writeEndElement();
        $doc = $this->xmlStreamWriter->asDom();
        $this->xmlStreamWriter->clear();
        return $doc;
    }

    /**
     * returns the image type as string
     *
     * @param   int     $type
     * @return  string
     */
    protected function getType($type)
    {
        if (isset($this->types[$type]) == true) {
            return $this->types[$type];
        }
        
        return $this->types[0];
    }
}
?>