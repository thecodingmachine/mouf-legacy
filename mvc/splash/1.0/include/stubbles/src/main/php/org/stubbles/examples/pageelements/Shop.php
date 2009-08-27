<?php
/**
 * 'shrinked' (contains formerly extended stuff) dummy shop Container for a shop.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
stubClassLoader::load('net::stubbles::peer::http::stubHTTPURL',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException'
);
/**
 * 'shrinked' (contains formerly extended stuff) dummy shop Container for a shop.
 *
 * @package     stubbles_examples
 * @subpackage  pageelements
 * @Entity()
 * @XMLTag(tagName='analyzable')
 */

class Shop extends stubBaseObject
{
    /**
     * analyzable status: enabled
     */
    const STATUS_ENABLED  = 'enabled';
    /**
     * analyzable status: disabled
     */
    const STATUS_DISABLED = 'disabled';
    /**
     * regular expression to detect correct shop ids
     */
    const ID_REGEX        = '/^[A-Z]{2,3}\.[A-Z]{2,3}\.[A-Z]{2}$/';
    /**
     * id of the shop
     *
     * @var  string
     */
    protected $id;
    /**
     * title of the shop
     *
     * @var  string
     */
    protected $title;
    /**
     * analyzable status
     *
     * @var  string
     */
    protected $status = self::STATUS_ENABLED;
    /**
     * URL of the shop
     *
     * @var  stubHTTPURL
     */
    protected $url;

    /**
     * sets the id of the shop
     *
     * @param  string  $id
     * @Filter[StringFilter](fieldName='shop_id', regex=Shop::ID_REGEX)
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * returns the id of the analyzable
     *
     * @return  string
     * @Id();
     * @DBColumn(name='shop_id', setterMethod='setId');
     * @XMLAttribute(attributeName='id');
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * sets the URL of the shop
     *
     * @param   string|stubHTTPURL  $url
     * @throws  stubMalformedURLException
     * @Filter[HTTPURLFilter](fieldName='url') 
     */
    public function setURL($url)
    {
        if (($url instanceof stubHTTPURL) === false) {
            $url = stubHTTPURL::fromString($url);
        }
        
        $this->url = $url;
    }

    /**
     * returns the URL of the shop
     *
     * @return  stubHTTPURL
     * @Transient()
     * @XMLIgnore()
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * returns the URL of the shop as string
     *
     * @return  string
     * @DBColumn(name='url', setterMethod='setURL')
     * @XMLAttribute(attributeName='url');
     */
    public function getURLAsString()
    {
        if (null !== $this->url) {
            return $this->url->get();
        }
        
        return null;
    }
    
    /**
     * sets the title of the analyzable
     *
     * @param  string  $title
     * @Filter[StringFilter](fieldName='title', regex='/(.*)/', minLength=5, maxLength=255)
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * returns the title of the analyzable
     *
     * @return  string
     * @XMLAttribute(attributeName='title')
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * sets the status of the analyzable
     *
     * @param  string  $status
     * @Filter[StringFilter](fieldName='status', regex='/enabled|disabled/', required=false)
     */
    public function setStatus($status)
    {
        if (in_array($status, array(self::STATUS_ENABLED, self::STATUS_DISABLED, null)) === false) {
            throw new stubIllegalArgumentException('Status must be enabled or disabled.');
        }
        
        $this->status = $status;
    }

    /**
     * status of the analyzable
     *
     * @return  string
     * @DBColumn(name='status', defaultValue=5)
     * @XMLAttribute(attributeName='status');
     */
    public function getStatus()
    {
        return $this->status;
    }
}
?>