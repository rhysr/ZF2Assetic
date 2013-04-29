<?php

namespace ZF2AsseticTest;

use ZF2Assetic\ContentTypeResolver;

use PHPUnit_Framework_TestCase as TestCase;

class ContentTypeResolverTest extends TestCase
{
    private $resolver;

    public function setUp()
    {
        $this->resolver = new ContentTypeResolver();
        $this->resolver->addMapping('css', 'text/css');
        $this->resolver->addMapping('js', 'application/javascript');
    }

    public function testThrowExceptionIfCantResolve()
    {
        $this->setExpectedException('\\ZF2Assetic\\InvalidArgumentException');
        $this->resolver->resolve('unknown');
    }

    public function testCanResolveCssContentType()
    {
        $contentType = $this->resolver->resolve('css');
        $this->assertEquals('text/css', $contentType);
    }

    public function testCanResolveJsContentType()
    {
        $contentType = $this->resolver->resolve('js');
        $this->assertEquals('application/javascript', $contentType);
    }

    public function testHasMappingIsTrueWhenMappingExists()
    {
        $this->assertTrue($this->resolver->hasMapping('css'));
    }

    public function testHasMappingIsFalseWhenNoMappingExists()
    {
        $this->assertFalse($this->resolver->hasMapping('png'));
    }

    public function testCanDynamicallyAddMapping()
    {
        $this->assertFalse($this->resolver->hasMapping('png'));
        $this->resolver->addMapping('png', 'image/png');
        $this->assertTrue($this->resolver->hasMapping('png'));

        $contentType = $this->resolver->resolve('png');
        $this->assertEquals('image/png', $contentType);
    }

    public function testExtensionResolvingIsCaseInsensitive()
    {
        $contentType = $this->resolver->resolve('CSS');
        $this->assertEquals('text/css', $contentType);
    }

    public function testMapResolvingIsCaseInsensitive()
    {
        $this->resolver->addMapping('PNG', 'image/png');
        $contentType = $this->resolver->resolve('png');
        $this->assertEquals('image/png', $contentType);
    }
}

