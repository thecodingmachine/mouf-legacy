<?php
/**
 * Decorator for the star file of resources/xsl/master.xsl.
 * 
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  build
 */
/**
 * Decorator for the star file of resources/xsl/master.xsl.
 * 
 * @package     stubbles
 * @subpackage  build
 */
class MasterXslStarFile extends StarFile
{
    /**
     * the decorated StarFile instance
     *
     * @var  StarFile
     */
    protected $starFile;

    /**
     * constructor
     *
     * @param  StarFile  $starFile  the StarFile instance to decorate
     */
    public function __construct(StarFile $starFile)
    {
        $this->starFile = $starFile;
    }

    /**
     * returns the name of the file
     *
     * @return  string
     */
    public function getName()
    {
        return $this->starFile->getName();
    }

    /**
     * returns the basename of the file
     *
     * @return  string
     */
    public function getBaseName()
    {
        return $this->starFile->getBaseName();
    }

    /**
     * get the extension of the file
     *
     * @return  string
     */
    public function getExtension()
    {
        return $this->starFile->getExtension();
    }

    /**
     * set the extension
     *
     * @param  string  $extension
     */
    public function setExtension($extension)
    {
        $this->starFile->setExtension($extension);
    }

    /**
     * returns the path of the file
     *
     * @return  string
     */
    public function getPath()
    {
        return $this->starFile->getPath();
    }

    /**
     * returns the path of the file but with the base removed
     *
     * @var  string
     */
    public function getPathWithBaseRemoved()
    {
        return $this->starFile->getPathWithBaseRemoved();
    }

    /**
     * returns the contents of the file
     *
     * @return  string
     */
    public function getContents()
    {
        $contents = $this->starFile->getContents();
        $contents = str_replace('<xsl:import href="copy.xsl"/>', '<xsl:import href="?xsl/copy.xsl"/>', $contents);
        $contents = str_replace('<xsl:import href="stub.xsl"/>', '<xsl:import href="?xsl/stub.xsl"/>', $contents);
        $contents = str_replace('<xsl:import href="variant.xsl"/>', '<xsl:import href="?xsl/variant.xsl"/>', $contents);
        $contents = str_replace('<xsl:import href="ingrid.xsl"/>', '<xsl:import href="?xsl/ingrid.xsl"/>', $contents);
        return $contents;
    }
}
?>