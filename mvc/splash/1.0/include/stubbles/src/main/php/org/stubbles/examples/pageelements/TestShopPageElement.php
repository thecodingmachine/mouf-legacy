<?php
/**
 * Page element to process changes to a shop.
 *
 * @author      Richard Sternagel <richard.sternagel@1und1.de>
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
require_once('Shop.php');

stubClassLoader::load('net::stubbles::ipo::request::validator::stubPassThruValidator',
                      'net::stubbles::websites::stubAbstractPageElement',
                      'net::stubbles::websites::xml::page::stubXMLPageElement'
);
/**
 * Page element to process changes to a shop.
 *
 * @package     stubbles_examples
 * @subpackage  pageelements
 */
class TestShopPageElement extends stubAbstractPageElement implements stubXMLPageElement
{

    /**
     * processes the page element
     *
     * @return  mixed
     */
    public function process()
    {
        $shop = new Shop();
        $shop->setId('42');
        $shop->setTitle('Captian Jack Shop');
        $shop->setURL('http://www.example.org/');
        $shop->setStatus('enabled');
        return array($shop);
    }
    
        /**
     * returns a list of form values
     *
     * @return  array<string,string>
     */
    public function getFormValues()
    {
        $data      = array();
        $validator = new stubPassThruValidator();
        if ($this->request->hasValue('id') === true) {
            $data['id'] = $this->request->getValidatedValue($validator, 'id');
        }
        
        if ($this->request->hasValue('title') === true) {
            $data['title'] = $this->request->getValidatedValue($validator, 'title');
        }
        
        if ($this->request->hasValue('url') === true) {
            $data['url'] = $this->request->getValidatedValue($validator, 'url');
        }
        
        return $data;
    }
}
?>