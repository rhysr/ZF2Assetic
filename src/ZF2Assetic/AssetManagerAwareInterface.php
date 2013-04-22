<?php

namespace ZF2Assetic;

use Assetic\AssetManager;

interface AssetManagerAwareInterface
{
    /**
     * Set Assetic asset manager
     *
     * @param AssetManager $assetManager
     */
    public function setAssetManager(AssetManager $assetManager);
}

