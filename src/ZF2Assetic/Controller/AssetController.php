<?php

namespace ZF2Assetic\Controller;

use ZF2Assetic\AssetManagerAwareTrait,
    ZF2Assetic\AssetManagerAwareInterface,
    ZF2Assetic\ContentTypeResolver;

use Assetic\AssetManager;

use Zend\Mvc\Controller\AbstractActionController;


class AssetController extends AbstractActionController implements AssetManagerAwareInterface
{
    protected $assetManager;

    protected $config = array();

    protected $contentTypeMap = array();

    public function indexAction()
    {
        $response = $this->getResponse();

        $resourceName = $collectionName = $this->params()->fromRoute('collection');
        $collectionConfig = array();

        if (!isset($this->config[$resourceName])) {
            $response->setStatusCode(404);
            return;
        }

        $collectionConfig = $this->config[$resourceName];
        $collectionName   = $collectionConfig['collectionName'];

        $collection = $this->assetManager->get($collectionName);
        $response->setContent($collection->dump());
        $headers = $response->getHeaders();
        if (null !== $collection->getLastModified()) {
            $headers->addHeaderLine('Last-Modified', '@' . $collection->getLastModified());
        }

        if (isset($collectionConfig['Content-Type'])) {
            $headers->addHeaderLine(
                'Content-Type',
                $collectionConfig['Content-Type']
            );
        } else {
            $extension = pathinfo($resourceName, PATHINFO_EXTENSION);
            try {
                if ($extension) {
                    $contentType = $this->contentTypeMap->resolve($extension);
                    $headers->addHeaderLine(
                        'Content-Type',
                        $contentType
                    );
                }
            } catch (\Exception $e) {
            }
        }

        return $response;
    }

    public function setAssetManager(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
        return $this;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }


    public function setContentTypeMap(ContentTypeResolver $contentTypeMap)
    {
        $this->contentTypeMap = $contentTypeMap;
        return $this;
    }
}

