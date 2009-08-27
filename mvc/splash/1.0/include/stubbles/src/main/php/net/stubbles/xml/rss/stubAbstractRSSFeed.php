<?php
/**
 * Abstract base implementation for rss feeds.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_rss
 */
stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator',
                     'net::stubbles::xml::rss::stubRSSFeed'
);
/**
 * Abstract base implementation for rss feeds.
 *
 * @package     stubbles
 * @subpackage  xml_rss
 */
abstract class stubAbstractRSSFeed extends stubBaseObject implements stubRSSFeed
{
    /**
     * request instance
     *
     * @var  stubRequest
     */
    protected $request;
    /**
     * rss feed caption
     *
     * @var  string
     */
    protected $title;
    /**
     * rss feed description
     *
     * @var  string
     */
    protected $description;
    /**
     * rss feed http base link
     *
     * @var  string
     */
    protected $link = null;
    /**
     * rss feed copyright clause
     *
     * @var  string
     */
    protected $copyright;

    /**
     * sets the request instance
     *
     * @param  stubRequest  $request
     * @Inject
     */
    public function setRequest(stubRequest $request)
    {
        $this->request = $request;
    }

    /**
     * creates the rss feed
     *
     * This method creates a new RSS feed generator or pass-through the
     * optional given instance to fill and manipulate feed contents. The
     * create lifecycle is seperated in three internal build steps:
     *
     * - before (before feed contents exists)
     * - create (fill feed contents)
     * - after  (after feed contents filled)
     *
     * @param   stubRSSFeedGenerator  $rssFeedGenerator  optional
     * @return  stubRSSFeedGenerator  pass-through after filled with content
     * @see     doBefore()
     * @see     doCreate()
     * @see     doAfter()
     */
    public function create(stubRSSFeedGenerator $rssFeedGenerator = null)
    {
        if ($rssFeedGenerator === null) {
            $rssFeedGenerator = new stubRSSFeedGenerator($this->getTitle(), $this->getLink(), $this->getDescription());
            $rssFeedGenerator->setCopyright($this->getCopyright())
                             ->setLanguage($this->getLanguage());
        }

        return $this->doAfter($this->doCreate($this->doBefore($rssFeedGenerator)));
    }

    /**
     * returns the title of the feed
     *
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * returns the link for the feed
     *
     * @return  string
     */
    public function getLink()
    {
        if (null === $this->link && null !== $this->request) {
            $this->link = sprintf('http://%s/', $this->request->getValidatedValue(new stubPassThruValidator(), 'SERVER_NAME', stubRequest::SOURCE_HEADER));
        }
        
        return $this->link;
    }

    /**
     * returns the description for the feed
     *
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * returns the detect language from config as current feed language
     *
     * @return  string
     * @see     stubRegistry::getConfig()
     */
    public function getLanguage()
    {
        return stubRegistry::getConfig('net.stubbles.language', 'en_EN');
    }

    /**
     * returns the copyright for the feed
     *
     * @return  string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * creation pre-interceptor
     *
     * Overwrite this method to hook into the process before the feed contents create.
     *
     * @param   stubRSSFeedGenerator  $rssFeedGenerator
     * @return  stubRSSFeedGenerator  pass-through given feed generator
     */
    protected function doBefore(stubRSSFeedGenerator $rssFeedGenerator)
    {
        return $rssFeedGenerator;
    }

    /**
     * creation post-interceptor
     *
     * @param   stubRSSFeedGenerator  $rssFeedGenerator
     * @return  stubRSSFeedGenerator  pass-through given feed generator
     */
    protected function doAfter(stubRSSFeedGenerator $rssFeedGenerator)
    {
        return $rssFeedGenerator;
    }

    /**
     * does the real creation of the feed's contents
     *
     * @param   stubRSSFeedGenerator  $rssFeedGenerator
     * @return  stubRSSFeedGenerator  pass-through given feed generator
     */
    protected abstract function doCreate(stubRSSFeedGenerator $rssFeedGenerator);
}
?>