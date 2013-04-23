<?php

namespace ZF2AsseticTest;

use ZF2Assetic\AssetManagerFactory;

use Assetic\Factory\AssetFactory;

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


    protected function getBasicConfig()
    {
        return array(
            'zf2_assetic' => array(
                'collections' => array(
                    'base_css' => array(
                        'assets' => array(
                            'css/test.css',
                        ),
                        'options' => array(
                            'root' => __DIR__ . '/../assets/',
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

