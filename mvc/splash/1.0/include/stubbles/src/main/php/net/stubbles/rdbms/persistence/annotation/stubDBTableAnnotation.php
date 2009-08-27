<?php
/**
 * Annotation to set the table in which class data should be stored.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseTableDescription'
);
/**
 * Annotation to set the table in which class data should be stored.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
class stubDBTableAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * the table description container
     *
     * @var  stubDatabaseTableDescription
     */
    protected $tableDescription;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $this->tableDescription = new stubDatabaseTableDescription();
    }
    
    /**
     * returns the table description container
     *
     * @return  stubDatabaseTableDescription
     */
    public function getTableDescription()
    {
        return clone $this->tableDescription;
    }
    
    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_CLASS;
    }
    
    /**
     * sets the name of the table
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        $this->tableDescription->setName($name);
    }

    /**
     * sets the type of the table
     *
     * @param  string  $type
     */
    public function setType($type)
    {
        $this->tableDescription->setType($type);
    }

    /**
     * sets the character set of the table
     *
     * @param  string  $characterSet
     */
    public function setCharacterSet($characterSet)
    {
        $this->tableDescription->setCharacterSet($characterSet);
    }

    /**
     * sets the collation of the table
     *
     * @param  string  $collation
     */
    public function setCollation($collation)
    {
        $this->tableDescription->setCollation($collation);
    }

    /**
     * sets the comment of the table
     *
     * @param  string  $comment
     */
    public function setComment($comment)
    {
        $this->tableDescription->setComment($comment);
    }
}
?>