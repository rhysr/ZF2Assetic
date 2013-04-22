<?php

namespace ZF2Assetic\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class AssetController extends AbstractActionController
{
    public function indexAction()
    {
        $response = $this->getResponse();
        $response->setStatusCode(404);
    }
}


