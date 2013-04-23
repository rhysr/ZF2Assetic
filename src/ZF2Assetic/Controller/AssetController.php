<?php

namespace ZF2Assetic\Controller;

use ZF2Assetic\AssetManagerAwareTrait,
    ZF2Assetic\AssetManagerAwareInterface;

use Assetic\AssetManager;

use Zend\Mvc\Controller\AbstractActionController;


class AssetController extends AbstractActionController implements AssetManagerAwareInterface
{
    protected $assetManager;

    public function indexAction()
    {
        $response = $this->getResponse();

        $collectionName = $this->params()->fromRoute('collection');
        if (!$this->assetManager->has($collectionName)) {
            $response->setStatusCode(404);
            return;
        }

        $collection = $this->assetManager->get($collectionName);
        $response->setContent($collection->dump());
        $response->getHeaders()->addHeaderLine('Last-Modified', '@' . $collection->getLastModified());


        return $response;
    }

    public function setAssetManager(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
        return $this;
    }
}

