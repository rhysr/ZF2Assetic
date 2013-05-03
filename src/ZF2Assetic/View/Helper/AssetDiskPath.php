<?php

namespace ZF2Assetic\View\Helper;

use Zend\View\Helper\AbstractHelper;

use ZF2Assetic\InvalidArgumentException;

class AssetDiskPath extends AbstractHelper
{
    /**
     * Mapping of asset name to public relative path
     *
     * @var array
     */
    private $assetPathMap = array(
        'test_css' => '/assets/css/test.css',
    );

    /**
     * @see getAssetPath
     *
     * @param string $assetName
     * @return string
     *
     * @throws \ZF2Assetic\InvalidArgumentException
     */
    public function __invoke($assetName)
    {
        return $this->getAssetPath($assetName);
    }

    /**
     * Get public relative path for asset name
     *
     * @param string $assetName
     * @return string
     *
     * @throws \ZF2Assetic\InvalidArgumentException
     */
    public function getAssetPath($assetName)
    {
        if (!$this->has($assetName)) {
            throw new InvalidArgumentException('Unable to find asset ' . var_export($assetName, true));
        }
        return $this->assetPathMap[$assetName];
    }

    /**
     * Check if view helper can get path for asset
     *
     * @param string $assetName
     * @return bool
     */
    public function has($assetName)
    {
        return isset($this->assetPathMap[$assetName]);
    }


    /**
     * Mapping of asset name to public relative asset path
     *
     * @param string $assetName
     * @param string $assetPath
     * @return \ZF2Assetic\View\Helper\AssetDiskPath
     */
    public function addMapping($assetName, $assetPath)
    {
        $this->assetPathMap[$assetName] = $assetPath;
        return $this;
    }
}

