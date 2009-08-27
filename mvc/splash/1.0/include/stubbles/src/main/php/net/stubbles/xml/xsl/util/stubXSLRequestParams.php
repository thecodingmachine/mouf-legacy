<?php
/**
 * Class to transfer the query string into an xml document.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
stubClassLoader::load('net::stubbles::ipo::request::stubRequest',
                      'net::stubbles::ipo::request::validator::stubPassThruValidator',
                      'net::stubbles::xml::xsl::util::stubXSLAbstractCallback'
);
/**
 * Class to transfer the query string into an xml document.
 *
 * @package     stubbles
 * @subpackage  xml_xsl_util
 */
class stubXSLRequestParams extends stubXSLAbstractCallback
{
    /**
     * request instance
     *
     * @var  stubRequest
     */
    protected $request;

    /**
     * sets the request instance to be used
     *
     * @param  stubRequest  $request  request instance
     * @Inject
     */
    public function setRequest(stubRequest $request)
    {
        $this->request = $request;
    }

    /**
     * returns the query string within a dom document
     * 
     * @return  DOMDocument
     * @XSLMethod
     */
    public function getQueryString()
    {
        $queryString = $this->request->getValidatedValue(new stubPassThruValidator(),
                                                         'QUERY_STRING',
                                                         stubRequest::SOURCE_HEADER
                       );
        $this->xmlStreamWriter->writeElement('requestParams',
                                             array(),
                                             $this->filterQueryString($queryString)
        );
        
        return $this->createDomDocument();
    }

    /**
     * filters processor and page out of query string
     *
     * @param   string  $queryString
     * @return  string
     */
    protected function filterQueryString($queryString)
    {
        $return = $queryString;
        $data   = array();
        parse_str($queryString, $data);
        foreach ($data as $key => $value) {
            if ('processor' === $key || 'page' === $key) {
                $return = str_replace($key . '=' . $value, '', $return);
            }
        }
        
        return str_replace('&=', '', str_replace('&&', '&', $return));
    }
}
?>