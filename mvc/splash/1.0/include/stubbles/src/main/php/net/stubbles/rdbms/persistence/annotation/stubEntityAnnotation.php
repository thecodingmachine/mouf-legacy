<?php
/**
 * Annotation to mark an object as an entity.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAnnotation',
                      'net::stubbles::reflection::annotations::stubAbstractAnnotation'
);
/**
 * Annotation to mark an object as an entity.
 *
 * @package     stubbles
 * @subpackage  rdbms_persistence_annotation
 */
class stubEntityAnnotation extends stubAbstractAnnotation implements stubAnnotation
{
    /**
     * optional: name of the entity (non-qualified class name)
     *
     * @var  string
     */
    protected $name;
    /**
     * default order by statement to be used when fetching more then one instance
     *
     * @var  string
     */
    protected $defaultOrder;

    /**
     * sets the name of the entity (non-qualified class name)
     *
     * @param  string  $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * returns the name of the entity (non-qualified class name)
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the default order by statement to be used when fetching more then one instance
     *
     * @param  string  $defaultOrder
     */
    public function setDefaultOrder($defaultOrder)
    {
        $this->defaultOrder = $defaultOrder;
    }

    /**
     * checks whether a default order by statement is set
     *
     * @return  bool
     */
    public function hasDefaultOrder()
    {
        return (null !== $this->defaultOrder);
    }

    /**
     * returns the default order by statement to be used when fetching more then one instance
     *
     * @return  string
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrder;
    }

    /**
     * returns the target of the annotation as bitmap
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_CLASS;
    }
}
?>