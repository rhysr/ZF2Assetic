<?php

namespace ZF2Assetic\Controller;

use ZF2Assetic\InvalidArgumentException,
    ZF2Assetic\ContentTypeResolverAwareInterface,
    ZF2Assetic\AssetManagerAwareInterface,
    ZF2Assetic\ContentTypeResolver;

use Assetic\AssetManager;

use Zend\Mvc\Controller\AbstractActionController;


class AssetController extends AbstractActionController implements
    AssetManagerAwareInterface,
    ContentTypeResolverAwareInterface
{
    protected $assetManager;

    /**
     * Resolve file extension to http Content-Type header value
     *
     * @var \ZF2Assetic\ContentTypeResolver
     */
    protected $contentTypeResolver;


    public function indexAction()
    {
        $response = $this->getResponse();

        $resourcePath = $this->params()->fromRoute('resourcePath');
        $asset = $this->findAsset($resourcePath);
        if (!$asset) {
            $response->setStatusCode(404);
            return;
        }

        $response->setContent($asset->dump());
        $headers = $response->getHeaders();
        if (null !== $asset->getLastModified()) {
            $headers->addHeaderLine('Last-Modified', '@' . $asset->getLastModified());
        }

        $extension = pathinfo($resourcePath, PATHINFO_EXTENSION);
        if ($extension && $this->contentTypeResolver->hasMapping($extension)) {
            $headers->addHeaderLine(
                'Content-Type',
                $this->contentTypeResolver->resolve($extension)
            );
        }

        return $response;
    }

    protected function findAsset($resourcePath)
    {
        foreach ($this->assetManager->getNames() as $name) {
            $asset = $this->assetManager->get($name);
            if (!$asset->getTargetPath()) {
                throw new InvalidArgumentException('Asset ' . $name . ' has no target path');
            }

            if ($resourcePath == $asset->getTargetPath()) {
                return $asset;
            }
        }
    }

    public function setAssetManager(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
        return $this;
    }

    public function setContentTypeResolver(ContentTypeResolver $contentTypeResolver)
    {
        $this->contentTypeResolver = $contentTypeResolver;
        return $this;
    }
}

