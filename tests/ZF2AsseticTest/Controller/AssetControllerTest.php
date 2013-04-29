<?php

namespace ZF2AsseticTest\Controller;

use ZF2Assetic\Controller\AssetController,
    ZF2Assetic\ContentTypeResolver;

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

        $contentTypeResolver = new ContentTypeResolver();
        $contentTypeResolver->addMapping('css', 'text/css');
        $contentTypeResolver->addMapping('js', 'application/javascript');
        $contentTypeResolver->addMapping('png', 'image/png');
        $this->controller->setContentTypeResolver($contentTypeResolver);
    }

    public function testUnknownAssetReturns404()
    {
        $this->routeMatch->setParam('resourcePath', 'does-not-exist.css');
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
        $asset = $this->createSimpleTestAsset();
        $this->assetManager->set('base_css', $asset);

        $this->routeMatch->setParam('resourcePath', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($asset->dump(), $response->getContent());
    }


    public function testResourcePathCanBeInSubDirectory()
    {
        $asset = $this->createSimpleTestAsset();
        $asset->setTargetPath('css/some.css');
        $this->assetManager->set('some_css', $asset);

        $this->routeMatch->setParam('resourcePath', 'css/some.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers  = $response->getHeaders();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($asset->dump(), $response->getContent());
    }


    public function testLastModifiedHeaderIsAddedIfAvailable()
    {
        $asset        = $this->createSimpleTestAsset();
        $lastModified = strtotime('2013-04-22 18:12:23');
        $asset->setLastModified($lastModified);
        $this->assetManager->set('base_css', $asset);

        $this->routeMatch->setParam('resourcePath', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers  = $response->getHeaders();

        $this->assertTrue($headers->has('Last-Modified'));
        $header = $headers->get('Last-Modified');
        $this->assertEquals(strtotime($header->getFieldValue()), $lastModified);

    }

    public function testCanGuessContentTypeFromExtension()
    {
        $asset = $this->createSimpleTestAsset();
        $this->assetManager->set('base_css', $asset);

        $this->routeMatch->setParam('resourcePath', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers  = $response->getHeaders();

        $this->assertTrue($headers->has('Content-Type'));
        $header = $headers->get('Content-Type');
        $this->assertEquals('text/css', $header->getFieldValue());
    }


    public function testAssetMissingTargetPathThrowsException()
    {
        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $asset = $this->createSimpleTestAsset();
        $asset->setTargetPath(null);
        $this->assetManager->set('base_css', $asset);

        $this->routeMatch->setParam('resourcePath', 'base.css');
        $this->controller->dispatch($this->request);
    }

    private function createSimpleTestAsset()
    {
        $asset = new StringAsset('.hidden {display: none;}');
        $asset->setTargetPath('base.css');
        return $asset;
    }
}

