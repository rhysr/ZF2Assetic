<?php

namespace ZF2AsseticTest\Controller;

use ZF2Assetic\Controller\AssetController;

use Zend\Http\Request,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Router\RouteMatch;

use PHPUnit_Framework_TestCase as TestCase;

class AssetControllerTestCase extends TestCase
{
    private $controller;

    public function setUp()
    {
        $this->controller = new AssetController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'assetic', 'action' => 'index'));
        $this->event      = new MvcEvent();

        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }

    public function testUnknownAssetReturns404()
    {
        $this->routeMatch->setParam('collection', 'does-not-exist.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
}

