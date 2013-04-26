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
        $config['zf2_assetic']['collections']['base_css']['filters'][] = 'AsseticCssRewriteFilter';
        $serviceManager = $this->createServiceManager($config);
        $filter         = new CssRewriteFilter();
        $serviceManager->setService('AsseticCssRewriteFilter', $filter);
        $assetManager   = $this->factory->createService($serviceManager);
        $asset          = $assetManager->get('base_css');

        $filters        = $asset->getFilters();
        $this->assertCount(1, $filters);
        $this->assertSame($filter, $filters[0]);
    }

    public function testCanCreateAssetsWhichDependOnOtherAssets()
    {
        $config         = $this->getDependentConfig();
        $serviceManager = $this->createServiceManager($config);
        $assetManager   = $this->factory->createService($serviceManager);
        $asset          = $assetManager->get('dependent_js');
        $this->assertCount(2, $asset->all());
    }


    protected function getBasicConfig()
    {
        return array(
            'zf2_assetic' => array(
                'collections' => array(
                    'base_css' => array(
                        'root' => __DIR__ . '/../assets/',
                        'assets' => array(
                            'css/test.css',
                        ),
                        'options' => array(
                            'output' => 'base.css',
                        ),
                    ),
                    'base_js' => array(
                        'collectionName' => 'base_js',
                        'root' => __DIR__ . '/../assets/',
                        'assets' => array(
                            'js/test.js',
                        ),
                        'options' => array(
                            'output' => 'base.js',
                        ),
                    ),
                ),
            ),
        );
    }

    protected function getDependentConfig()
    {
        $config = $this->getBasicConfig();
        $config['zf2_assetic']['collections']['dependent_js'] = array(
            'root' => __DIR__ . '/../assets/',
            'assets' => array(
                '@base_js',
                'js/test.js',
            ),
            'options' => array(
                'output' => 'dependent.js',
            ),
        );
        return $config;
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

