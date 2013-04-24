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

        $contenTypeResolver = new ContentTypeResolver();
        $contenTypeResolver->addMapping('css', 'text/css');
        $contenTypeResolver->addMapping('js', 'application/javascript');
        $contenTypeResolver->addMapping('png', 'image/png');
        $this->controller->setContentTypeMap($contenTypeResolver);
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
        $this->assetManager->set('base_css', $asset);
        $this->controller->setConfig(array(
            'base.css' => array(
                'collectionName' => 'base_css'
            )
        ));

        $this->routeMatch->setParam('collection', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($css, $response->getContent());
    }


    public function testLastModifiedHeaderIsAddedIfAvailable()
    {
        $asset        = new StringAsset('.hidden {display: none;}');
        $lastModified = strtotime('2013-04-22 18:12:23');
        $asset->setLastModified($lastModified);
        $this->assetManager->set('base_css', $asset);
        $this->controller->setConfig(array(
            'base.css' => array(
                'collectionName' => 'base_css'
            )
        ));

        $this->routeMatch->setParam('collection', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers = $response->getHeaders();

        $this->assertTrue($headers->has('Last-Modified'));
        $header = $headers->get('Last-Modified');
        $this->assertEquals(strtotime($header->getFieldValue()), $lastModified);

    }

    public function testCanGuessContentTypeFromExtension()
    {
        $asset        = new StringAsset('.hidden {display: none;}');
        $this->assetManager->set('base_css', $asset);

        $this->controller->setConfig(array(
            'base.css' => array(
                'collectionName' => 'base_css',
            )
        ));

        $this->routeMatch->setParam('collection', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers = $response->getHeaders();

        $this->assertTrue($headers->has('Content-Type'));
        $header = $headers->get('Content-Type');
        $this->assertEquals('text/css', $header->getFieldValue());
    }

    public function testCanSetContentType()
    {
        $asset        = new StringAsset('.hidden {display: none;}');
        $this->assetManager->set('base_css', $asset);

        $this->controller->setConfig(array(
            'base.css' => array(
                'collectionName' => 'base_css',
                'Content-Type' => 'text/css',
            )
        ));

        $this->routeMatch->setParam('collection', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers = $response->getHeaders();

        $this->assertTrue($headers->has('Content-Type'));
        $header = $headers->get('Content-Type');
        $this->assertEquals('text/css', $header->getFieldValue());
    }


    public function testManualContentTypeTakesPreference()
    {
        $asset        = new StringAsset('.hidden {display: none;}');
        $this->assetManager->set('base_css', $asset);

        $this->controller->setConfig(array(
            'base.css' => array(
                'collectionName' => 'base_css',
                'Content-Type' => 'image/png',
            )
        ));

        $this->routeMatch->setParam('collection', 'base.css');
        $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $headers = $response->getHeaders();

        $this->assertTrue($headers->has('Content-Type'));
        $header = $headers->get('Content-Type');
        $this->assertEquals('image/png', $header->getFieldValue());
    }
}

