<?php
/**
 * Annotation to set the column of a table in which a class property should be stored.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 * @version     $Id: stubDBColumnAnnotation.php 1897 2008-10-24 10:22:57Z mikey $
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseTableColumn'
);
/**
 * Annotation to set the column of a table in which a class property should be stored.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
class stubDBColumnAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * the db table column container
     *
     * @var  stubDatabaseTableColumn
     */
    protected $dbTableColumn;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $this->dbTableColumn = new stubDatabaseTableColumn();
    }
    
    /**
     * returns the table column container
     *
     * @return  stubDatabaseTableColumn
     */
    public function getTableColumn()
    {
        return $this->dbTableColumn;
    }
    
    /**
     * Returns the target of the annotation as bitmap.
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_METHOD;
    }
    
    /**
     * set the order within the table
     *
     * @param  int  $order
     */
    public function setOrder($order)
    {
        $this->dbTableColumn->setOrder($order);
    }

    /**
     * sets the name of the column
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        $this->dbTableColumn->setName($name);
    }

    /**
     * sets the type of the column
     *
     * @param  string  $type
     */
    public function setType($type)
    {
        $this->dbTableColumn->setType($type);
    }

    /**
     * sets the size of the column
     *
     * @param  int|string  $size
     */
    public function setSize($size)
    {
        $this->dbTableColumn->setSize($size);
    }

    /**
     * set whether the column may be null or not
     *
     * @param  bool  $isUnsigned
     */
    public function setIsUnsigned($isUnsigned)
    {
        $this->dbTableColumn->setIsUnsigned($this->castToBool($isUnsigned));
    }

    /**
     * set whether the column may be null or not
     *
     * @param  bool  $isNullable
     */
    public function setIsNullable($isNullable)
    {
        $this->dbTableColumn->setIsNullable($this->castToBool($isNullable));
    }

    /**
     * sets the default value of the column
     *
     * @param  mixed  $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->dbTableColumn->setDefaultValue($defaultValue);
    }

    /**
     * set whether the column is a primary key or not
     *
     * @param  bool  $isKey
     */
    public function setIsKey($isKey)
    {
        $this->dbTableColumn->setIsKey($this->castToBool($isKey));
    }

    /**
     * set whether the column is unique or not
     *
     * @param  bool  $isUnique
     */
    public function setIsUnique($isUnique)
    {
        $this->dbTableColumn->setIsUnique($this->castToBool($isUnique));
    }

    /**
     * set the name of the setter method
     *
     * @param  string  $setterMethod
     */
    public function setSetterMethod($setterMethod)
    {
        $this->dbTableColumn->setSetterMethod($setterMethod);
    }

    /**
     * set the character set of the table
     *
     * @param  string  $characterSet
     */
    public function setCharacterSet($characterSet)
    {
        $this->dbTableColumn->setCharacterSet($characterSet);
    }
    
    /**
     * set the collation of the table
     *
     * @param  string  $collation
     */
    public function setCollation($collation)
    {
        $this->dbTableColumn->setCollation($collation);
    }
    
    /**
     * helper method to cast a value to bool
     *
     * @param   mixed  $value
     * @return  bool
     */
    protected function castToBool($value)
    {
        if (is_string($value) == true && 'false' == $value) {
            return false;
        }
        
        return (bool) $value;
    }
}
?>