<?php

namespace ZF2AsseticTest\Controller;

use ZF2Assetic\Controller\AssetController;

use Assetic\AssetManager;

use Zend\Http\Request,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch;

use PHPUnit_Framework_TestCase as TestCase;

class AssetControllerTestCase extends TestCase
{
    private $controller;

    public function setUp()
    {
        $this->controller   = new AssetController();
        $this->request      = new Request();
        $this->routeMatch   = new RouteMatch(array('controller' => 'assetic', 'action' => 'index'));
        $this->event        = new MvcEvent();
        $this->assetManager = new AssetManager();

        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setAssetManager($this->assetManager);
    }

    public function testUnknownAssetReturns404()
    {
        $this->routeMatch->setParam('collection', 'does-not-exist.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }


    public function testControllerIsAssetManagerAware()
    {
        $this->assertInstanceOf('ZF2Assetic\AssetManagerAwareInterface', $this->controller);
    }
}

