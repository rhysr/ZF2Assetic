<?php

namespace ZF2Assetic;

class ContentTypeResolver
{
    private $map = array();

    public function resolve($extension)
    {
        $extension = $this->normaliseExtension($extension);
        if (isset($this->map[$extension])) {
            return $this->map[$extension];
        }
        throw new InvalidArgumentException(
            'Cannot resolve ' . var_export($extension, true) . ' to content type'
        );
    }

    public function addMapping($extension, $contentType)
    {
        $this->map[$this->normaliseExtension($extension)] = $contentType;
    }

    public function hasMapping($extension)
    {
        return isset($this->map[strtolower($extension)]);
    }

    private function normaliseExtension($extension)
    {
        return strtolower($extension);
    }
}

