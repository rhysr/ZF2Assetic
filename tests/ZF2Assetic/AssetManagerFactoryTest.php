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

    public function testManagerGetsAssetPopulateFromAsset()
    {
        $assetManager = $this->factory->createService($this->createServiceManager(
            $this->getBasicConfig()
        ));
        $this->assertTrue($assetManager->has('base_css'));
    }

    public function testManagerGetFiltersFromServiceManager()
    {
        $config = $this->getBasicConfig();
        $config['zf2_assetic']['collections']['base.css']['filters'][] = 'AsseticCssRewriteFilter';
        $serviceManager = $this->createServiceManager($config);
        $filter = new CssRewriteFilter();
        $serviceManager->setService('AsseticCssRewriteFilter', $filter);
        $assetManager = $this->factory->createService($serviceManager);
        $asset = $assetManager->get('base_css');

        $filters = $asset->getFilters();
        $this->assertCount(1, $filters);
        $this->assertSame($filter, $filters[0]);
    }


    protected function getBasicConfig()
    {
        return array(
            'zf2_assetic' => array(
                'collections' => array(
                    'base.css' => array(
                        'collectionName' => 'base_css',
                        'root' => __DIR__ . '/../assets/',
                        'assets' => array(
                            'css/test.css',
                        ),
                    ),
                ),
            ),
        );
    }

    protected function createServiceManager(array $config)
    {
        $serviceManager = new ServiceManager(new Config($config));
        $serviceManager->setService('Configuration', $config);
        $serviceManager->setAlias('Config', 'Configuration');
        $serviceManager->setService('AsseticAssetFactory', new AssetFactory(__DIR__));
        return $serviceManager;
    }
}

