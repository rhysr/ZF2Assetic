<?php

namespace ZF2Assetic\View\Helper;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class AssetPathFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $viewHelperManager)
    {
        $serviceManager = $viewHelperManager->getServiceLocator();
        $config         = $serviceManager->get('Config');

        $helper         = new AssetPath(
            $serviceManager->get('AsseticAssetManager'),
            $serviceManager->get('router'),
            $config['zf2_assetic']['controllerRouteName']
        );
        return $helper;
    }
}

