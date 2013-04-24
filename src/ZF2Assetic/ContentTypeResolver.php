<?php

namespace ZF2Assetic;

class ContentTypeResolver
{
    private $map = array();

    public function resolve($extension)
    {
        $extension = strtolower($extension);
        if (isset($this->map[$extension])) {
            return $this->map[$extension];
        }
        throw new UnknownContentTypeException($extension);
    }

    public function addMapping($extension, $contentType)
    {
        $this->map[strtolower($extension)] = $contentType;
    }
}

class UnknownContentTypeException extends \Exception {}

