<?php

namespace ZF2Assetic\View\Helper;

interface AssetPathInterface
{
    /**
     * Get path to asset
     *
     * 'css/base.css' == $helper->getAssetPath('base_css');
     *
     * @param string $assetName
     * @return string
     */
    public function getAssetPath($assetName);
}

