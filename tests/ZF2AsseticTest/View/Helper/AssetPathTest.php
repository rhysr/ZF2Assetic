<?php

namespace ZF2AsseticTest\View\Helper;

use ZF2Assetic\View\Helper\AssetPath;

use Assetic\Asset\FileAsset,
    Assetic\AssetManager;

use Zend\Mvc\Router\Http\Regex AS RegexRoute,
    Zend\Mvc\Router\Http\TreeRouteStack;

use PHPUnit_Framework_TestCase as TestCase;

class AssetPathTest extends TestCase
{
    private $helper;

    public function setUp()
    {
        $this->assetManager = $this->createAssetManager();
        $this->router       = $this->createRouter();
        $this->helper       = new AssetPath($this->assetManager, $this->router, 'asset');
    }

    public function testThrowsExceptionIfUnableToFindAsset()
    {
        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $helper = $this->helper;
        $helper('unknown_asset');
    }

    public function testReturnsUrlForFoundAsset()
    {
        $helper = $this->helper;
        $this->assertEquals('/assets/css/test.css', $helper('test_css'));
    }

    public function testHelperCanBeTestedToSeeIfItHasAsset()
    {
        $this->assertFalse($this->helper->has('unknown_asset'));
    }


    public function testInvokingHelperReturnsSameAsGetAssetPathMethod()
    {
        $helper = $this->helper;
        $this->assertEquals($helper('test_css'), $this->helper->getAssetPath('test_css'));
    }

    public function testRoutingFails()
    {
        $this->setExpectedException('\\ZF2Assetic\\RuntimeException');
        $this->helper->setRouteName('unknown_route');
        $this->helper->getAssetPath('test_css');
    }

    protected function createAssetManager()
    {
        $asset = new FileAsset(__DIR__ . '/../../../tests/test.css');
        $asset->setTargetPath('css/test.css');
        $assetManager = new AssetManager();
        $assetManager->set('test_css', $asset);
        return $assetManager;
    }

    protected function createRouter()
    {
        $route = new RegexRoute('/assets/(?<resourcePath>.*)', '/assets/%resourcePath%');
        $router = new TreeRouteStack();
        $router->addRoute('asset', $route);
        return $router;
    }
}

