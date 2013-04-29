<?php

namespace ZF2AsseticTest;

use ZF2Assetic\AssetManagerFactory;

use Assetic\Factory\AssetFactory,
    Assetic\Filter\CssRewriteFilter;

use Zend\ServiceManager\ServiceManager,
    Zend\ServiceManager\Config;

use PHPUnit_Framework_TestCase as TestCase;

class AssetManagerFactoryTest extends TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = new AssetManagerFactory();
    }

    public function testCreatesAssetManager()
    {
        $assetManager = $this->factory->createService($this->createServiceManager(
            $this->getBasicConfig()
        ));
        $this->assertInstanceOf('Assetic\AssetManager', $assetManager);
    }

    public function testModuleConfigKeyIsRequired()
    {
        $this->setExpectedException('RuntimeException');
        $assetManager = $this->factory->createService($this->createServiceManager(
            array()
        ));
    }

    public function testCreatesEmptyAssetManagerIfNoAssetsDefined()
    {
        $config = $this->getBasicConfig();
        unset($config['zf2_assetic']['collections']);
        $assetManager = $this->factory->createService($this->createServiceManager(
            $config
        ));
        $this->assertInstanceOf('Assetic\AssetManager', $assetManager);
        $this->assertCount(0, $assetManager->getNames());
    }

    public function testAssetConfigNeedsOutput()
    {
        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $config = $this->getBasicConfig();
        unset($config['zf2_assetic']['collections']['base_css']['options']['output']);
        $assetManager = $this->factory->createService($this->createServiceManager(
            $config
        ));
        $this->assertTrue($assetManager->has('base_css'));
    }

    public function testManagerGetsAssetPopulateFromAsset()
    {
        $assetManager = $this->factory->createService($this->createServiceManager(
            $this->getBasicConfig()
        ));
        $this->assertTrue($assetManager->has('base_css'));
    }

    public function testManagerGetFiltersFromServiceManager()
    {
        $config         = $this->getBasicConfig();
        $serviceManager = $this->createServiceManager($config);
        $filter         = $serviceManager->get('AsseticCssRewriteFilter');
        $assetManager   = $this->factory->createService($serviceManager);
        $asset          = $assetManager->get('filtered_css');

        $filters        = $asset->getFilters();
        $this->assertCount(1, $filters);
        $this->assertSame($filter, $filters[0]);
    }

    public function testCanCreateAssetsWhichDependOnOtherAssets()
    {
        $config         = $this->getBasicConfig();
        $serviceManager = $this->createServiceManager($config);
        $assetManager   = $this->factory->createService($serviceManager);
        $asset          = $assetManager->get('dependent_js');
        $this->assertCount(2, $asset->all());
    }


    protected function getBasicConfig()
    {
        return require __DIR__ . '/../config/basic.config.php';
    }

    protected function createServiceManager(array $config)
    {
        $serviceManager = new ServiceManager(new Config($config));
        $serviceManager->setService('Configuration', $config);
        $serviceManager->setAlias('Config', 'Configuration');
        $serviceManager->setService('AsseticAssetFactory', new AssetFactory(__DIR__));
        $serviceManager->setService('AsseticCssRewriteFilter', new CssRewriteFilter());
        return $serviceManager;
    }
}

