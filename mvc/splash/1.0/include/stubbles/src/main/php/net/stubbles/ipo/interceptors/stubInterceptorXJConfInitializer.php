<?php
/**
 * Class for initializing the interceptors via XJConf.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
stubClassLoader::load('net::stubbles::ipo::interceptors::stubInterceptorInitializer',
                      'net::stubbles::lang::exceptions::stubIllegalArgumentException',
                      'net::stubbles::util::xjconf::xjconf'
);
/**
 * Class for initializing the interceptors via XJConf.
 *
 * @package     stubbles
 * @subpackage  ipo_interceptors
 */
class stubInterceptorXJConfInitializer extends stubXJConfAbstractInitializer implements stubInterceptorInitializer
{
    /**
     * descriptor that identifies the initializer
     *
     * @var  string
     */
    protected $descriptor       = 'interceptors';
    /**
     * list of pre interceptors
     *
     * @var  array<stubPreInterceptor>
     */
    protected $preInterceptors  = array();
    /**
     * list of post interceptors
     *
     * @var  array<stubPostInterceptor>
     */
    protected $postInterceptors = array();

    /**
     * sets the descriptor that identifies this initializer
     *
     * @param  string  $descriptor
     */
    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    /**
     * returns the descriptor that identifies the initializer
     *
     * @param   string  $type  type of descriptor: config or definition
     * @return  string
     * @throws  stubIllegalArgumentException
     */
    public function getDescriptor($type)
    {
        switch ($type) {
            case stubXJConfInitializer::DESCRIPTOR_CONFIG:
                return $this->descriptor;
            
            case stubXJConfInitializer::DESCRIPTOR_DEFINITION:
                return 'interceptors';
            
            default:
                // intentionally empty
        }
        
        throw new stubIllegalArgumentException('Invalid descriptor type.');
    }

    /**
     * returns the data to cache
     *
     * @return  array
     */
    public function getCacheData()
    {
        $cacheData = array('preInterceptors' => array(), 'postInterceptors' => array());
        foreach ($this->preInterceptors as $preInterceptor) {
            if ($preInterceptor instanceof stubSerializable) {
                $cacheData['preInterceptors'][] = $preInterceptor->getSerialized();
            } else {
                $cacheData['preInterceptors'][] = $preInterceptor->getClassName();
            }
        }

        foreach ($this->postInterceptors as $postInterceptor) {
            if ($postInterceptor instanceof stubSerializable) {
                $cacheData['postInterceptors'][] = $postInterceptor->getSerialized();
            } else {
                $cacheData['postInterceptors'][] = $postInterceptor->getClassName();
            }
        }

        return $cacheData;
    }

    /**
     * sets the data from the cache
     *
     * @param  array  $cacheData
     */
    public function setCacheData(array $cacheData)
    {
        foreach ($cacheData['preInterceptors'] as $preInterceptor) {
            if ($preInterceptor instanceof stubSerializedObject) {
                $this->preInterceptors[] = $preInterceptor->getUnserialized();
            } else {
                $nqClassName = stubClassLoader::getNonQualifiedClassName($preInterceptor);
                if (null == $nqClassName) {
                    $nqClassName = $preInterceptor;
                }
                if (class_exists($nqClassName, false) == false) {
                    stubClassLoader::load($preInterceptor);
                }

                $this->preInterceptors[] = new $nqClassName();
            }
        }

        foreach ($cacheData['postInterceptors'] as $postInterceptor) {
            if ($postInterceptor instanceof stubSerializedObject) {
                $this->postInterceptors[] = $postInterceptor->getUnserialized();
            } else {
                $nqClassName = stubClassLoader::getNonQualifiedClassName($postInterceptor);
                if (null == $nqClassName) {
                    $nqClassName = $postInterceptor;
                }
                if (class_exists($nqClassName, false) == false) {
                    stubClassLoader::load($postInterceptor);
                }

                $this->postInterceptors[] = new $nqClassName();
            }
        }
    }

    /**
     * will be called in case the stubXJConfProxy did not find the data in the
     * cache and the initializer has to load values from the facade
     *
     * @param  stubXJConfFacade  $xjconf
     */
    public function loadData(stubXJConfFacade $xjconf)
    {
        $this->preInterceptors  = $xjconf->getConfigValue('preInterceptors');
        $this->postInterceptors = $xjconf->getConfigValue('postInterceptors');
    }

    /**
     * sets the list of pre interceptors
     *
     * @param  array<stubPreInterceptor>  $preInterceptors
     */
    public function setPreInterceptors(array $preInterceptors)
    {
        $this->preInterceptors = $preInterceptors;
    }

    /**
     * returns the list of pre interceptors
     *
     * @return  array<stubPreInterceptor>
     */
    public function getPreInterceptors()
    {
        return $this->preInterceptors;
    }

    /**
     * sets the list of pre interceptors
     *
     * @param  array<stubPostInterceptor>  $postInterceptors
     */
    public function setPostInterceptors(array $postInterceptors)
    {
        $this->postInterceptors = $postInterceptors;
    }

    /**
     * returns the list of post interceptors
     *
     * @return  array<stubPostInterceptor>
     */
    public function getPostInterceptors()
    {
        return $this->postInterceptors;
    }
}
?>