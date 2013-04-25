<?php

namespace ZF2Assetic;

interface ContentTypeResolverAwareInterface
{
    /**
     * Set Content-Type resolver
     *
     * @param \ZF2Assetic\ContentTypeResolver
     */
    public function setContentTypeResolver(ContentTypeResolver $contentTypeResolver);
}

