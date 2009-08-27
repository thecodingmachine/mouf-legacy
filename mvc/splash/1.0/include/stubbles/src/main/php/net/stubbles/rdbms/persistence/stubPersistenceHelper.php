<?php
/**
 * Base class with helper methods for entity operations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence
 * @version     $Id: stubPersistenceHelper.php 1927 2008-11-10 23:54:32Z mikey $
 */
stubClassLoader::load('net::stubbles::rdbms::persistence::stubPersistenceException',
                      'net::stubbles::rdbms::persistence::annotation::stubDBColumnAnnotation',
                      'net::stubbles::rdbms::persistence::annotation::stubDBTableAnnotation',
                      'net::stubbles::rdbms::persistence::annotation::stubEntityAnnotation',
                      'net::stubbles::rdbms::persistence::annotation::stubIdAnnotation',
                      'net::stubbles::rdbms::persistence::annotation::stubTransientAnnotation',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseTableDescription',
                      'net::stubbles::rdbms::querybuilder::stubDatabaseTableColumn',
                      'net::stubbles::reflection::reflection'
);
/**
 * Base class with helper methods for entity operations.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence
 */
abstract class stubPersistenceHelper extends stubBaseObject
{
    /**
     * list of table descriptions
     *
     * @var  array<string,stubDatabaseTableDescription>
     */
    protected static $tableDescriptions = array();
    /**
     * list of classes with their persistence data
     *
     * @var  array<string,array<string,stubDatabaseTableColumn>>
     */
    protected static $columns           = array();
    /**
     * list of forbidden methods
     *
     * @var  array<string>
     */
    protected $forbiddenMethods = array('getClass',
                                        'getPackage',
                                        'getClassName',
                                        'getPackageName',
                                        'getSerialized'
                                  );

    /**
     * helper method to create the correct table definition
     *
     * @param   stubBaseReflectionClass       $entityClass
     * @return  stubDatabaseTableDescription
     */
    protected function getTableDescription(stubBaseReflectionClass $entityClass)
    {
        $fqClassName = $entityClass->getFullQualifiedClassName();
        if (isset(self::$tableDescriptions[$fqClassName]) === false) {
            if ($entityClass->hasAnnotation('DBTable') === true) {
                 self::$tableDescriptions[$fqClassName] = $entityClass->getAnnotation('DBTable')->getTableDescription();
            } else {
                self::$tableDescriptions[$fqClassName] = new stubDatabaseTableDescription();
                self::$tableDescriptions[$fqClassName]->setName($entityClass->getName() . 's');
            }
        }
        
        return self::$tableDescriptions[$fqClassName];
    }

    /**
     * returns list of columns and method names
     *
     * @param   stubBaseReflectionClass                $entityClass
     * @return  array<string,stubDatabaseTableColumn>
     */
    protected function getColumns(stubBaseReflectionClass $entityClass)
    {
        $fqClassName = $entityClass->getFullQualifiedClassName();
        if (isset(self::$columns[$fqClassName]) === false) {
            self::$columns[$fqClassName] = array();
            foreach ($entityClass->getMethods() as $method) {
                $column = $this->getTableColumn($method);
                if (null === $column) {
                    continue;
                }
                
                self::$columns[$fqClassName][$method->getName()] = $column;
            }
            
        }
        return self::$columns[$fqClassName];
    }

    /**
     * helper method to create the column definition
     * 
     * Returns null if the method does not return a proper definition.
     *
     * @param   stubReflectionMethod     $method
     * @return  stubDatabaseTableColumn
     * @throws  stubPersistenceException
     */
    private function getTableColumn(stubReflectionMethod $method)
    {
        if ($method->isStatic() === true || $method->isPublic() === false
                || in_array($method->getName(), $this->forbiddenMethods) === true
                || $method->hasAnnotation('Transient') === true
                || $method->getNumberOfParameters() > 0) {
            return null;
        }
        
        if ($method->hasAnnotation('DBColumn') === true) {
            $column = $method->getAnnotation('DBColumn')->getTableColumn();
        } elseif (substr($method->getName(), 0, 3) !== 'get' && substr($method->getName(), 0, 2) !== 'is') {
            return null;
        } else {
            $column = new stubDatabaseTableColumn();
            $column->setName($this->getPropertyName($method->getName()));
            $returnType = $method->getReturnType();
            if (null === $returnType) {
                // no type hint or returns null -> ignore method
                return null;
            }
            
            if ($returnType instanceof stubReflectionClass) {
                if ($returnType->getName() !== 'stubDate') {
                    // not supported yet
                    throw new stubPersistenceException('Returning classes from entity getter methods is currently not supported, except for net::stubbles::lang::types::stubDate. Sorry. :(');
                }
                
                $column->setType('DATETIME');
            } else {
                switch ($returnType->value()) {
                    case 'int':
                        $column->setType('INT');
                        $column->setSize(10);
                        break;
                    
                    case 'float':
                        $column->setType('FLOAT');
                        $column->setSize(10);
                        break;
                    
                    case 'bool':
                        $column->setType('TINYINT');
                        $column->setSize(1);
                        break;
                    
                    default:
                        $column->setType('VARCHAR');
                        $column->setSize(255);
                }
            }
        }
        
        if ($method->hasAnnotation('Id') === true) {
            $column->setIsPrimaryKey(true);
        }
        
        return $column;
    }

    /**
     * creates the property name from the name of the method
     *
     * @param   string  $methodName
     * @return  string
     */
    protected function getPropertyName($methodName)
    {
        $propertyName = str_replace('is', '', str_replace('get', '', $methodName));
        return strtolower($propertyName{0}) . substr($propertyName, 1);
    }
}
?>