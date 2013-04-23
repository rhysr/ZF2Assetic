<?php

namespace ZF2Assetic;

use Assetic\AssetManager;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class AssetManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        if (!isset($config['zf2_assetic'])) {
            throw new \RuntimeException('Missing zf2assetic config key');
        }
        $assetConfig = $config['zf2_assetic'];

        $assetManager = new AssetManager();
        if (!isset($assetConfig['collections']) || !is_array($assetConfig['collections'])) {
            return $assetManager;
        }

        foreach ($assetConfig['collections'] as $collectionName => $collectionConfig) {

            $assetFactory = new \Assetic\Factory\AssetFactory($collectionConfig['root']);
            $assets  = isset($collectionConfig['assets'])  ? $collectionConfig['assets']  : array();
            $filters = isset($collectionConfig['filters']) ? $collectionConfig['filters'] : array();
            $options = isset($collectionConfig['options']) ? $collectionConfig['options'] : array();

            $asset = $assetFactory->createAsset($assets, $filters, $options);
            $assetManager->set($collectionName, $asset);
        }
        return $assetManager;
    }
}

