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

        foreach ($assetConfig['collections'] as $assetName => $collectionConfig) {

            $assetFactory = new \Assetic\Factory\AssetFactory($collectionConfig['root']);
            $assetFactory->setAssetManager($assetManager);
            if ($this->canUseCacheBusterForCollection($assetConfig, $collectionConfig)) {
                $assetFactory->addWorker($serviceLocator->get('AsseticCacheBuster'));
            }
            $assets  = isset($collectionConfig['assets'])  ? $collectionConfig['assets']  : array();
            $options = isset($collectionConfig['options']) ? $collectionConfig['options'] : array();

            if (empty($options['output'])) {
                throw new InvalidArgumentException('Asset: ' . $assetName . ' is missing output config');
            }
            $asset = $assetFactory->createAsset($assets, array(), $options);

            if (isset($collectionConfig['filters'])) {
                foreach ($collectionConfig['filters'] as $filterService) {
                    $filter = $serviceLocator->get($filterService);
                    $asset->ensureFilter($filter);
                }
            }

            $assetManager->set($assetName, $asset);
        }
        return $assetManager;
    }

    protected function canUseCacheBusterForCollection($asseticConfig, $collectionConfig)
    {
        if (isset($collectionConfig['useCacheBuster']) && true === $collectionConfig['useCacheBuster']) {
            return true;
        }

        if (isset($asseticConfig['useCacheBuster']) && $asseticConfig['useCacheBuster']) {
            if (!isset($collectionConfig['useCacheBuster']) || true === $collectionConfig['useCacheBuster']) {
                return true;
            }
        }
        return false;
    }
}

