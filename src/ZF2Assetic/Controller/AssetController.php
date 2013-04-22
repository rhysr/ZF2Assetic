<?php

namespace ZF2Assetic\Controller;

use ZF2Assetic\AssetManagerAwareTrait,
    ZF2Assetic\AssetManagerAwareInterface;

use Zend\Mvc\Controller\AbstractActionController;


class AssetController extends AbstractActionController implements AssetManagerAwareInterface
{
    use AssetManagerAwareTrait;

    public function indexAction()
    {
        $collectionName = $this->params()->fromRoute('collection');
        if (!$this->assetManager->has($collectionName)) {
            $response = $this->getResponse();
            $response->setStatusCode(404);
        }
    }
}


