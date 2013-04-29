<?php

namespace ZF2AsseticTest;

use ZF2Assetic\ContentTypeResolverFactory;

use Zend\ServiceManager\ServiceManager,
    Zend\ServiceManager\Config;

use PHPUnit_Framework_TestCase as TestCase;

class ContentTypeResolverFactoryTest extends TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = new ContentTypeResolverFactory();
    }

    public function testCreatesContentTypeResolver()
    {
        $contentTypeResolver = $this->factory->createService($this->createServiceManager(
            $this->getBasicConfig()
        ));
        $this->assertInstanceOf('\\ZF2Assetic\\ContentTypeResolver', $contentTypeResolver);
    }

    public function testModuleConfigKeyIsRequired()
    {
        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $assetManager = $this->factory->createService($this->createServiceManager(
            array()
        ));
    }

    public function testConsumesMappingFromContentTypeMapArray()
    {
        $contentTypeResolver = $this->factory->createService($this->createServiceManager(
            $this->getBasicConfig()
        ));
        $this->assertTrue($contentTypeResolver->hasMapping('css'));
        $this->assertEquals('text/css', $contentTypeResolver->resolve('css'));
    }


    protected function getBasicConfig()
    {
        return require __DIR__ . '/../config/basic.config.php';
    }

    protected function createServiceManager(array $config)
    {
        $serviceManager = new ServiceManager(new Config($config));
        $serviceManager->setService('Configuration', $config);
        $serviceManager->setAlias('Config', 'Configuration');
        return $serviceManager;
    }
}

