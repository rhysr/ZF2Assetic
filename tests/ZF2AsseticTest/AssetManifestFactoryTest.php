<?php

namespace ZF2AsseticTest;


use ZF2Assetic\AssetManifestFactory;

use Zend\ServiceManager\ServiceManager;

use PHPUnit_Framework_TestCase as TestCase;

class AssetManifestFactoryTest extends TestCase
{
    private $factory;

    private $config = array(
        'zf2_assetic' => array(
            'useAssetController' => false,
        ),
    );

    public function setUp()
    {
        $this->config['zf2_assetic']['assetManifestPath'] = __DIR__ . '/../config/asset-manifest.json';
        $this->factory = new AssetManifestFactory();
    }

    public function testNeedZf2AsseticKey()
    {
        $serviceManager = $this->createServiceManager(array());

        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $this->factory->createService($serviceManager);
    }

    public function testNeedManifestPathKey()
    {
        $serviceManager = $this->createServiceManager(array(
            'zf2_assetic' => array()
        ));

        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $this->factory->createService($serviceManager);
    }

    public function testManifestFileMustExistAndBeReadable()
    {
        $config = $this->config;
        $config['zf2_assetic']['assetManifestPath'] = __DIR__ . '/../config/doesnt-exist';
        $serviceManager = $this->createServiceManager($config);

        $this->setExpectedException('\\ZF2Assetic\\RuntimeException');
        $this->factory->createService($serviceManager);
    }

    public function testManifestFileMustBeRecognisableJson()
    {
        $config = $this->config;
        $config['zf2_assetic']['assetManifestPath'] = __DIR__ . '/../config/bad-manifest';
        $serviceManager = $this->createServiceManager($config);

        $this->setExpectedException('\\ZF2Assetic\\RuntimeException');
        $this->factory->createService($serviceManager);
    }

    public function testManifestIsArray()
    {
        $config = $this->config;
        $serviceManager = $this->createServiceManager($config);

        $assetManifest = $this->factory->createService($serviceManager);
        $this->assertInternalType('array', $assetManifest);
    }

    public function testManifestContainsAssetArray()
    {
        $config = $this->config;
        $serviceManager = $this->createServiceManager($config);

        $assetManifest = $this->factory->createService($serviceManager);
        $this->assertArrayHasKey('assets', $assetManifest);
        $this->assertInternalType('array', $assetManifest['assets']);
    }

    public function testManifestContainsManifestMapping()
    {
        $config = $this->config;
        $serviceManager = $this->createServiceManager($config);

        $assetManifest = $this->factory->createService($serviceManager);
        $assets = $assetManifest['assets'];
        $this->assertArrayHasKey('test_css', $assets);
        $this->assertEquals('/assets/css/test.css', $assets['test_css']);
        $this->assertArrayHasKey('test_js', $assets);
        $this->assertEquals('/assets/js/test.js', $assets['test_js']);
    }

    public function createServiceManager($config)
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('Configuration', $config);
        $serviceManager->setAlias('Config', 'Configuration');
        return $serviceManager;
    }
}

