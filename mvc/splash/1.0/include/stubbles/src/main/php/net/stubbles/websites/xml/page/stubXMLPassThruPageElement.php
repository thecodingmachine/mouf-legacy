<?php
/**
 * XML page element that passes thru the contents of an xml file.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_xml_page
 */
stubClassLoader::load('net::stubbles::websites::stubAbstractPageElement',
                      'net::stubbles::websites::xml::page::stubXMLPageElement'
);
/**
 * XML page element that passes thru the contents of an xml file.
 * 
 * @package     stubbles
 * @subpackage  websites_xml_page
 * @XMLTag(tagName='passThru')
 * @XMLMethods[XMLMatcher](pattern='/getContents/')
 */
class stubXMLPassThruPageElement extends stubAbstractPageElement implements stubXMLPageElement
{
    /**
     * directory where files can be found
     *
     * @var  string
     */
    protected $directory;
    /**
     * the name of the xml file to pass thru
     *
     * @var  string
     */
    protected $fileName;

    /**
     * sets the directory where the file can be found
     *
     * @param  string  $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * sets the name of the xml file to pass thru
     *
     * @param  string  $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * returns the contents of the file
     *
     * @return  string
     * @XMLFragment(tagName='content')
     */
    public function getContents()
    {
        $fileName = $this->directory . '/' . $this->fileName;
        if (file_exists($fileName) == false) {
            return '<error>The file ' . $fileName . ' does not exist.</error>';
        }
        
        return file_get_contents($fileName);
    }

    /**
     * returns a list of variables that have an influence on caching
     *
     * @return  array<string,scalar>
     */
    public function getCacheVars()
    {
        return array('filename' => $this->directory . '/' . $this->fileName);
    }

    /**
     * returns a list of files used to create the content
     *
     * @return  array<string>
     */
    public function getUsedFiles()
    {
        return array($this->directory . '/' . $this->fileName);
    }

    /**
     * processes the page element
     *
     * @return  mixed
     * @XMLIgnore()
     */
    public function process()
    {
        return $this;
    }

    /**
     * returns a list of form values
     *
     * @return  array<string,string>
     * @XMLIgnore()
     */
    public function getFormValues()
    {
        return array();
    }
}
?>