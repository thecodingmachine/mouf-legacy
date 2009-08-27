<?php
/**
 * Annotation to mark an entity as an item of an RSS feed.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_rss
 * @version     $Id: stubRSSFeedItemAnnotation.php 1827 2008-09-16 21:40:35Z mikey $
 */
stubClassLoader::load('net::stubbles::reflection::annotations::stubAbstractAnnotation');
/**
 * Annotation to mark an entity as an item of an RSS feed.
 *
 * @package     stubbles
 * @subpackage  xml_rss
 */
class stubRSSFeedItemAnnotation extends stubAbstractAnnotation
{
    /**
     * list of methods to retrieve rss feed item data from
     *
     * The key is the method of the stubRSSFeedItem class that needs to be called
     * to set the respective property. This does not apply to title, link and
     * description as these are set on construction via the constructor.
     *
     * The value is the name of the method of the entity marked with this
     * annotation. The method names here are the default ones, all of them may
     * be overwritten by the annotation on the entity class.
     *
     * @var  array<string,string>
     */
    protected $methods = array('title'                => 'getTitle',
                               'link'                 => 'getLink',
                               'description'          => 'getDescription',
                               'byAuthor'             => 'getAuthor',
                               'inCategories'         => 'getCategories',
                               'addCommentsAt'        => 'getCommentsURL',
                               'deliveringEnclosures' => 'getEnclosures',
                               'withGuid'             => 'getGuid',
                               'andGuidIsPermaLink'   => 'isPermaLink',
                               'publishedOn'          => 'getPubDate',
                               'inspiredBySources'    => 'getSources',
                               'withContent'          => 'getContent'
                         );

    /**
     * returns the target of the annotation as bitmap
     *
     * @return  int
     */
    public function getAnnotationTarget()
    {
        return stubAnnotation::TARGET_CLASS;
    }

    /**
     * returns list of methods to retrieve rss feed item data from
     *
     * @return  array<string,string>
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * sets the name of method to return the title of the item
     *
     * @param  string  $titleMethod
     */
    public function setTitleMethod($titleMethod)
    {
        $this->methods['title'] = $titleMethod;
    }

    /**
     * sets the name of method to return the URL of the item
     *
     * @param  string  $linkMethod
     */
    public function setLinkMethod($linkMethod)
    {
        $this->methods['link'] = $linkMethod;
    }

    /**
     * sets the name of method to return the item synopsis
     *
     * @param  string  $descriptionMethod
     */
    public function setDescriptionMethod($descriptionMethod)
    {
        $this->methods['description'] = $descriptionMethod;
    }

    /**
     * sets the name of method to return the email address of the author of the item
     *
     * @param  string  $authorMethod
     */
    public function setAuthorMethod($authorMethod)
    {
        $this->methods['byAuthor'] = $authorMethod;
    }

    /**
     * sets the name of method to return the categories where the item is included
     *
     * @param  string  $categoriesMethod
     */
    public function setCategoriesMethod($categoriesMethod)
    {
        $this->methods['inCategories'] = $categoriesMethod;
    }

    /**
     * sets the name of method to return the URL of a page for comments relating to the item
     *
     * @param  string  $commentsMethod
     */
    public function setCommentsMethod($commentsMethod)
    {
        $this->methods['addCommentsAt'] = $commentsMethod;
    }

    /**
     * sets the name of method to return the media object descriptions attached to the item
     *
     * @param  string  $enclosuresMethod
     */
    public function setEnclosuresMethod($enclosuresMethod)
    {
        $this->methods['deliveringEnclosures'] = $enclosuresMethod;
    }

    /**
     * sets the name of method to return the unique identifier for the item
     *
     * @param  string  $guidMethod
     */
    public function setGuidMethod($guidMethod)
    {
        $this->methods['withGuid'] = $guidMethod;
    }

    /**
     * sets the name of method to return whether the id may be interpreted as a permanent link or not
     *
     * @param  string  $isPermaLinkMethod
     */
    public function setIsPermaLinkMethod($isPermaLinkMethod)
    {
        $this->methods['andGuidIsPermaLink'] = $isPermaLinkMethod;
    }

    /**
     * sets the name of method to return the date when the item was published
     *
     * @param  string  $pubDateMethod
     */
    public function setPubDateMethod($pubDateMethod)
    {
        $this->methods['publishedOn'] = $pubDateMethod;
    }

    /**
     * sets the name of method to return where that the item came from
     *
     * @param  string  $sourcesMethod
     */
    public function setSourcesMethod($sourcesMethod)
    {
        $this->methods['inspiredBySources'] = $sourcesMethod;
    }

    /**
     * sets the name of method to return the content of rss feed item
     *
     * @param  string  $contentMethod
     */
    public function setContentMethod($contentMethod)
    {
        $this->methods['withContent'] = $contentMethod;
    }
}
?>