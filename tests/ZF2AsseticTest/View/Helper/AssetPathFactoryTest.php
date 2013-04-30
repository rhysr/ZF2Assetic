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

    private $serviceManager;

    private $pluginManager;

    private $assetManager;

    private $config = array(
        'zf2_assetic' => array(
            'controllerRouteName' => 'assetic',
        ),
    );
    public function setUp()
    {
        $this->factory        = new AssetPathFactory();
        $this->serviceManager = new ServiceManager();
        $this->pluginManager  = new HelperPluginManager();
        $this->assetManager   = new AssetManager();
        $this->router         = new TreeRouteStack();
        $this->routeName      = 'assetRoute';

        $this->serviceManager->setService('AsseticAssetManager', $this->assetManager);
        $this->serviceManager->setService('router', $this->router);
        $this->pluginManager->setServiceLocator($this->serviceManager);

        $this->serviceManager->setService('Configuration', $this->config);
        $this->serviceManager->setAlias('Config', 'Configuration');
    }

    public function testFactoryCreatesViewHelper()
    {
        $helper = $this->factory->createService($this->pluginManager);
        $this->assertInstanceOf('\\ZF2Assetic\\View\\Helper\\AssetPath', $helper);
    }

    public function testRouteNameCorrectlySet()
    {
        $helper = $this->factory->createService($this->pluginManager);
        $this->assertEquals('assetic', $helper->getRouteName());
    }
}

