<?php

namespace ZF2Assetic;

use Assetic\AssetManager;

trait AssetManagerAwareTrait
{
    /**
     * @var Assetic\AssetManager;
     */
    protected $assetManager;

    /**
     * Set asset manager
     *
     * @param AssetManager $assetManager
     * @return mixed
     */
    public function setAssetManager(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
        return $this;
    }
}
