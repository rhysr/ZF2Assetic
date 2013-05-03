<?php

namespace ZF2Assetic\View\Helper;

use ZF2Assetic\InvalidArgumentException;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class AssetPathFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $viewHelperManager)
    {
        $serviceManager = $viewHelperManager->getServiceLocator();
        $config         = $serviceManager->get('Config');
        if (!isset($config['zf2_assetic']['useAssetController'])) {
            throw new InvalidArgumentException('Missing \'useAssetController\' config');
        }

        if ($config['zf2_assetic']['useAssetController']) {
            return $this->createAssetControllerViewHelper($viewHelperManager);
        } else {
            return $this->createDiskAssetViewHelper($viewHelperManager);
        }

    }

    protected function createAssetControllerViewHelper(ServiceLocatorInterface $viewHelperManager)
    {
        $serviceManager = $viewHelperManager->getServiceLocator();
        $config         = $serviceManager->get('Config');
        if (!isset($config['zf2_assetic']['controllerRouteName'])) {
            throw new InvalidArgumentException('Missing \'controllerRouteName\' config');
        }

        $helper = new AssetPath(
            $serviceManager->get('AsseticAssetManager'),
            $serviceManager->get('router'),
            $config['zf2_assetic']['controllerRouteName']
        );
        return $helper;
    }

    protected function createDiskAssetViewHelper(ServiceLocatorInterface $viewHelperManager)
    {
        $serviceManager = $viewHelperManager->getServiceLocator();
        $helper         = new AssetDiskPath();

        $manifest       = $serviceManager->get('AssetManifest');
        foreach ($manifest['assets'] as $assetName => $path) {
            $helper->addMapping($assetName, $path);
        }
        return $helper;
    }
}

