<?php
/**
 * Tests for net::stubbles::util::xjconf::stubXJConfProxy.
 *
 * @author      Frank Kleine <mikey@stubbles.net>
 * @package     stubbles
 * @subpackage  util_xjconf_test
 */
stubClassLoader::load('net::stubbles::util::xjconf::stubXJConfProxy');
/**
 * Tests for net::stubbles::util::xjconf::stubXJConfProxy.
 *
 * @package     stubbles
 * @subpackage  util_xjconf_test
 * @group       util_xjconf
 */
class stubXJConfProxyTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @var  stubXJConfProxy
     */
    protected $xjConfProxy;
    /**
     * a mocked initializer
     *
     * @var  PHPUnit_Framework_MockObject_MockObject
     */
    protected $xjConfInitializer;
    /**
     * path to files
     *
     * @var  string
     */
    protected $dir;
    /**
     * the config file
     *
     * @var  string
     */
    protected $configFile;
    /**
     * list of source files
     *
     * @var  array<string>
     */
    protected $sourceFiles = array();
    /**
     * the cache file
     *
     * @var  string
     */
    protected $cacheFile;

    /**
     * set up test environment
     */
    public function setUp()
    {
        if (file_exists($this->configFile) == true) {
            unlink($this->configFile);
        }
        
        if (file_exists($this->cacheFile) == true) {
            unlink($this->cacheFile);
        }
        
        $this->xjConfInitializer = $this->getMock('stubXJConfInitializer');
        $this->xjConfInitializer->expects($this->any())->method('getDescriptor')->will($this->returnValue('config'));
        $this->xjConfInitializer->expects($this->any())->method('getAdditionalDefinitions')->will($this->returnValue(array()));
        $this->dir               = dirname(__FILE__);
        $this->configFile        = $this->dir . '/config.xml';
        $this->sourceFiles       = array($this->dir . '/config1.xml', $this->dir . '/config2.xml');
        $this->cacheFile         = $this->dir . '/config.cache';
        $this->xjConfProxy       = new stubXJConfProxy($this->xjConfInitializer, $this->dir, $this->dir);
        file_put_contents($this->configFile, "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<xj:configuration
    xmlns:xj=\"http://xjconf.net/XJConf\"
    xmlns=\"http://stubbles.net/lang/registry\">
  <config name=\"net.stubbles.mode\" value=\"test\" />
</xj:configuration>");
        file_put_contents($this->sourceFiles[0], "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<xj:configuration
    xmlns:xj=\"http://xjconf.net/XJConf\"
    xmlns=\"http://stubbles.net/lang/registry\">
  <config name=\"net.stubbles.mode\" value=\"test\" />
</xj:configuration>");
        file_put_contents($this->sourceFiles[1], "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<xj:configuration
    xmlns:xj=\"http://xjconf.net/XJConf\"
    xmlns=\"http://stubbles.net/lang/registry\">
  <config name=\"net.stubbles.mode\" value=\"test\" />
</xj:configuration>");
    }

    /**
     * clean up test environment
     */
    public function tearDown()
    {
        if (file_exists($this->configFile) == true) {
            unlink($this->configFile);
        }
        
        foreach ($this->sourceFiles as $sourceFile) {
            if (file_exists($sourceFile) == true) {
                unlink($sourceFile);
            }
        }
        
        if (file_exists($this->cacheFile) == true) {
            unlink($this->cacheFile);
        }
    }

    /**
     * assure that values are taken from cache is cache file is newer than data file
     *
     * @test
     */
    public function isCachedAndCacheIsNewer()
    {
        file_put_contents($this->cacheFile, serialize(array('foo' => 'bar')));
        touch($this->configFile, (time() - 100));
        touch($this->cacheFile, time());
        $this->xjConfInitializer->expects($this->once())->method('setCacheData')->with($this->equalTo(array('foo' => 'bar')));
        $this->xjConfInitializer->expects($this->never())->method('getCacheData');
        $this->xjConfInitializer->expects($this->never())->method('getExtensions');
        $this->xjConfProxy->process();
    }

    /**
     * assure that values are taken from cache is cache file is newer than source files
     *
     * @test
     */
    public function isCachedAndCacheIsNewerWithSourceFiles()
    {
        file_put_contents($this->cacheFile, serialize(array('foo' => 'bar')));
        foreach ($this->sourceFiles as $sourceFile) {
            touch($sourceFile, (time() - 100));
        }
        touch($this->cacheFile, time());
        $this->xjConfInitializer->expects($this->once())->method('setCacheData')->with($this->equalTo(array('foo' => 'bar')));
        $this->xjConfInitializer->expects($this->never())->method('getCacheData');
        $this->xjConfInitializer->expects($this->never())->method('getExtensions');
        $this->xjConfProxy->process($this->sourceFiles);
    }

    /**
     * assure that values are created and cached if the cache file is older than the data file
     *
     * @test
     */
    public function isCachedAndCacheIsOlder()
    {
        file_put_contents($this->cacheFile, serialize(array('foo' => 'bar')));
        touch($this->configFile, (time()));
        touch($this->cacheFile, time() - 100);
        $this->xjConfInitializer->expects($this->never())->method('setCacheData');
        $this->xjConfInitializer->expects($this->once())->method('loadData');
        $this->xjConfInitializer->expects($this->once())->method('getCacheData')->will($this->returnValue(array('baz' => 'bar')));
        $this->xjConfInitializer->expects($this->once())->method('getExtensions')->will($this->returnValue(array()));
        $this->xjConfProxy->process();
        $this->assertEquals(array('baz' => 'bar'), unserialize(file_get_contents($this->cacheFile)));
    }

    /**
     * assure that values are taken from cache is cache file is newer than source files
     *
     * @test
     */
    public function isCachedAndCacheIsOlderWithSourceFiles()
    {
        file_put_contents($this->cacheFile, serialize(array('foo' => 'bar')));
        foreach ($this->sourceFiles as $sourceFile) {
            touch($sourceFile, (time()));
        }
        touch($this->cacheFile, time() - 100);
        $this->xjConfInitializer->expects($this->never())->method('setCacheData');
        $this->xjConfInitializer->expects($this->exactly(2))->method('loadData');
        $this->xjConfInitializer->expects($this->once())->method('getCacheData')->will($this->returnValue(array('baz' => 'bar')));
        $this->xjConfInitializer->expects($this->once())->method('getExtensions')->will($this->returnValue(array()));
        $this->xjConfProxy->process($this->sourceFiles);
        $this->assertEquals(array('baz' => 'bar'), unserialize(file_get_contents($this->cacheFile)));
    }

    /**
     * assure that values are created and cached if they were not cached before
     *
     * @test
     */
    public function isNotCached()
    {
        $this->xjConfInitializer->expects($this->never())->method('setCacheData');
        $this->xjConfInitializer->expects($this->once())->method('loadData');
        $this->xjConfInitializer->expects($this->once())->method('getCacheData')->will($this->returnValue(array('baz' => 'bum')));
        $this->xjConfInitializer->expects($this->once())->method('getExtensions')->will($this->returnValue(array()));
        $this->xjConfProxy->process();
        $this->assertEquals(array('baz' => 'bum'), unserialize(file_get_contents($this->cacheFile)));
    }

    /**
     * assure that values are created and cached if they were not cached before
     *
     * @test
     */
    public function isNotCachedWithSourceFiles()
    {
        $this->xjConfInitializer->expects($this->never())->method('setCacheData');
        $this->xjConfInitializer->expects($this->exactly(2))->method('loadData');
        $this->xjConfInitializer->expects($this->once())->method('getCacheData')->will($this->returnValue(array('baz' => 'bum')));
        $this->xjConfInitializer->expects($this->once())->method('getExtensions')->will($this->returnValue(array()));
        $this->xjConfProxy->process($this->sourceFiles);
        $this->assertEquals(array('baz' => 'bum'), unserialize(file_get_contents($this->cacheFile)));
    }
}
?>