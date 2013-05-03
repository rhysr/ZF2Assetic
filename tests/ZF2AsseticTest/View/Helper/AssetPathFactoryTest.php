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
            'controllerRouteName' => 'assetic',
        ),
    );

    public function setUp()
    {
        $this->factory        = new AssetPathFactory();
        $this->assetManager   = new AssetManager();
        $this->router         = new TreeRouteStack();
        $this->routeName      = 'assetRoute';
    }

    public function testFactoryCreatesViewHelper()
    {
        $serviceManager = $this->createServiceManager($this->controllerModeConfig);
        $pluginManager = $this->createPluginManager($serviceManager);

        $helper = $this->factory->createService($pluginManager);
        $this->assertInstanceOf('\\ZF2Assetic\\View\\Helper\\AssetPath', $helper);
    }

    public function testRouteNameCorrectlySet()
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

    public function createServiceManager($config)
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService('AsseticAssetManager', $this->assetManager);
        $serviceManager->setService('router', $this->router);
        $serviceManager->setService('Configuration', $config);
        $serviceManager->setAlias('Config', 'Configuration');
        return $serviceManager;
    }

    public function createPluginManager(ServiceManager $serviceManager)
    {
        $pluginManager  = new HelperPluginManager();
        $pluginManager->setServiceLocator($serviceManager);
        return $pluginManager;
    }
}

