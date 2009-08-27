<?php
/**
 * Interface for all variants.
 *
 * @author      Stephan Schmidt <stephan.schmidt@schlund.de>
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::session::stubSession'
);
/**
 * Interface for all variants.
 *
 * @package     stubbles
 * @subpackage  websites_variantmanager_types
 */
interface stubVariant extends stubSerializable
{
    /**
     * returns the name of the variant
     * 
     * @return  string
     */
    public function getName();

    /**
     * returns the full qualified name of the variant
     *
     * @return  string
     */
    public function getFullQualifiedName();

    /**
     * sets the name of the variant
     *
     * @param  string  $name
     */
    public function setName($name);

    /**
     * returns title of the variant
     * 
     * @return  string
     */
    public function getTitle();

    /**
     * sets the title of the variant
     *
     * @param  string  $title
     */
    public function setTitle($title);

    /**
     * returns alias name of the variant
     * 
     * @return  string
     */
    public function getAlias();

    /**
     * check whether the variant is an enforcing variant
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  boolean
     */
    public function isEnforcing(stubSession $session, stubRequest $request);

    /**
     * return the forced variant
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  stubVariant
     */
    public function getEnforcingVariant(stubSession $session, stubRequest $request);

    /**
     * return the variant
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request
     * @return  stubVariant
     */
    public function getVariant(stubSession $session, stubRequest $request);

    /**
     * check whether the conditions for this variant are met
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  boolean
     */
    public function conditionsMet(stubSession $session, stubRequest $request);

    /**
     * check whether the variant is valid
     * 
     * @param   stubSession  $session  access to session
     * @param   stubRequest  $request  access to request parameters
     * @return  boolean
     */
    public function isValid(stubSession $session, stubRequest $request);

    /**
     * assign that this variant has been choosen
     * 
     * @param  stubSession  $session  access to session
     * @param  stubRequest  $request  access to request parameters
     */
    public function assign(stubSession $session, stubRequest $request);

    /**
     * returns parent variant
     * 
     * @return  stubVariant
     */
    public function getParent();

    /**
     * set parent variant
     * 
     * @param  stubVariant  $parent
     */
    public function setParent(stubVariant $parent = null);

    /**
     * check whether the variant has a parent variant
     * 
     * @return  boolean
     */
    public function hasParent();

    /**
     * return child variants of this variant
     * 
     * @return  array<stubVariant>
     */
    public function getChildren();

    /**
     * add a child variant
     * 
     * @param  stubVariant  $child
     */
    public function addChild(stubVariant $child);

    /**
     * remove a child variant
     * 
     * @param  stubblesVariant  $child
     */
    public function removeChild(stubVariant $child);
}
?>