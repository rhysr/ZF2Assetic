<?php

namespace ZF2Assetic;

use ZF2Assetic\InvalidArgumentException,
    ZF2Assetic\RuntimeException;

use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Config\Reader\Json;

class AssetManifestFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $config = $serviceManager->get('Config');
        if (!isset($config['zf2_assetic'])) {
            throw new InvalidArgumentException('Missing zf2_assetic config key');
        }

        if (!isset($config['zf2_assetic']['assetManifestPath'])) {
            throw new InvalidArgumentException('Missing assetManifestPath config key');
        }

        $reader = new Json();
        try {
            $data = $reader->fromFile($config['zf2_assetic']['assetManifestPath']);
        } catch (\Zend\Config\Exception\RuntimeException $e) {
            throw new RuntimeException('Can\'t load asset manifest file', null, $e);
        }
        if (!isset($data['assets'])) {
            throw new RuntimeException('Asset manifest missing \'assets\' key');
        }
        return $data;
    }
}

