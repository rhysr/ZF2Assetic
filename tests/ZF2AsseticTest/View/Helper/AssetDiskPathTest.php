<?php

namespace ZF2AsseticTest\View\Helper;

use ZF2Assetic\View\Helper\AssetDiskPath;

use PHPUnit_Framework_TestCase as TestCase;

class AssetDiskPathTest extends TestCase
{
    private $helper;

    public function setUp()
    {
        $this->helper = new AssetDiskPath();
    }

    public function testThrowsExceptionIfUnableToFindAsset()
    {
        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $this->helper->getAssetPath('unknown_asset');
    }

    public function testHelperCanBeTestedToSeeIfItHasAsset()
    {
        $this->assertFalse($this->helper->has('unknown_asset'));
    }

    public function testCanAddAssetNameToPathMapping()
    {
        $this->assertFalse($this->helper->has('test_js'));
        $this->helper->addMapping('test_js', '/assets/js/test.js');
        $this->assertTrue($this->helper->has('test_js'));
    }

    public function testReturnsUrlForFoundAsset()
    {
        $this->helper->addMapping('test_css', '/assets/css/test.css');
        $this->assertEquals('/assets/css/test.css', $this->helper->getAssetPath('test_css'));
    }

    public function testInvokingHelperReturnsSameAsGetAssetPathMethod()
    {
        $helper = $this->helper;
        $this->helper->addMapping('test_css', '/assets/css/test.css');
        $this->assertEquals($helper('test_css'), $this->helper->getAssetPath('test_css'));
    }
}

