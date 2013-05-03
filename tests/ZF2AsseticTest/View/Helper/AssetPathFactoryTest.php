<?php

namespace ZF2AsseticTest\View\Helper;

use Assetic\AssetManager;

use ZF2Assetic\View\Helper\AssetPathFactory;

use Zend\View\HelperPluginManager,
    Zend\Mvc\Router\Http\TreeRouteStack,
    Zend\ServiceManager\ServiceManager,
    Zend\ServiceManager\Config;

use PHPUnit_Framework_TestCase as TestCase;

class AssetPathFactoryTest extends TestCase
{
    private $factory;

    private $assetManager;

    private $router;

    private $routeName;

    private $controllerModeConfig = array(
        'zf2_assetic' => array(
            'useAssetController' => true,
            'controllerRouteName' => 'assetic',
        ),
    );

    private $diskModeConfig = array(
        'zf2_assetic' => array(
            'useAssetController' => false,
        ),
    );

    public function setUp()
    {
        $this->factory        = new AssetPathFactory();
        $this->assetManager   = new AssetManager();
        $this->router         = new TreeRouteStack();
        $this->routeName      = 'assetRoute';
    }

    public function testFactoryRequiresModeConfig()
    {
        $serviceManager = $this->createServiceManager(array(
            'zf2_assetic' => array(
            )
        ));
        $pluginManager = $this->createPluginManager($serviceManager);

        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $helper = $this->factory->createService($pluginManager);
    }

    public function testControllerModeFactoryCreatesControllerViewHelper()
    {
        $serviceManager = $this->createServiceManager($this->controllerModeConfig);
        $pluginManager = $this->createPluginManager($serviceManager);

        $helper = $this->factory->createService($pluginManager);
        $this->assertInstanceOf('\\ZF2Assetic\\View\\Helper\\AssetPath', $helper);
    }

    public function testRouteNameCorrectlySetForControllerMode()
    {
        $serviceManager = $this->createServiceManager($this->controllerModeConfig);
        $pluginManager = $this->createPluginManager($serviceManager);

        $helper = $this->factory->createService($pluginManager);
        $this->assertEquals('assetic', $helper->getRouteName());
    }

    public function testThrowsExceptionIfControllerRouteNameNotSet()
    {
        $serviceManager = $this->createServiceManager(array());
        $pluginManager = $this->createPluginManager($serviceManager);

        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');

        $helper = $this->factory->createService($pluginManager);
    }

    public function testOnDiskModeDiskHelper()
    {
        $serviceManager = $this->createServiceManager($this->diskModeConfig);
        $pluginManager = $this->createPluginManager($serviceManager);

        $helper = $this->factory->createService($pluginManager);
        $this->assertInstanceOf('\\ZF2Assetic\\View\\Helper\\AssetDiskPath', $helper);
    }

    public function testAssetDiskModePopulatedFromAssetManifest()
    {
        $serviceManager = $this->createServiceManager($this->diskModeConfig);
        $pluginManager = $this->createPluginManager($serviceManager);

        $helper = $this->factory->createService($pluginManager);
        $this->assertEquals('/assets/css/test.css', $helper->getAssetPath('test_css'));
    }


    public function createServiceManager($config)
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('AsseticAssetManager', $this->assetManager);
        $serviceManager->setService('router', $this->router);
        $serviceManager->setService('Configuration', $config);
        $serviceManager->setAlias('Config', 'Configuration');
        $serviceManager->setService('AssetManifest', $this->createAssetManifest($config));
        return $serviceManager;
    }

    public function createPluginManager(ServiceManager $serviceManager)
    {
        $pluginManager  = new HelperPluginManager();
        $pluginManager->setServiceLocator($serviceManager);
        return $pluginManager;
    }

    public function createAssetManifest($config)
    {
        return array(
            'assets' => array(
                'test_css' => '/assets/css/test.css',
                'test_js'  => '/assets/js/test.js',
            )
        );
    }
}

