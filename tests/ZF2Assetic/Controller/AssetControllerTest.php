<?php

namespace ZF2AsseticTest\Controller;

use ZF2Assetic\Controller\AssetController;

use Assetic\AssetManager,
    Assetic\Asset\StringAsset;

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

    public function testKnownAssetReturnsContent()
    {
        $css          = '.hidden {display: none;}';
        $asset        = new StringAsset($css);
        $lastModified = strtotime('2013-04-22 18:12:23');
        $asset->setLastModified($lastModified);
        $this->assetManager->set('base_css', $asset);

        $this->routeMatch->setParam('collection', 'base_css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($css, $response->getContent());

        $headers = $response->getHeaders();
        $this->assertTrue($headers->has('Last-Modified'));

        $header = $headers->get('Last-Modified');
        $this->assertEquals(strtotime($header->getFieldValue()), $lastModified);
    }
}

