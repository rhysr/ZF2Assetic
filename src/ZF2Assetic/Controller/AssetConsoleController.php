<?php

namespace ZF2Assetic\Controller;

use ZF2Assetic\InvalidArgumentException,
    ZF2Assetic\AssetManagerAwareInterface;

use Assetic\AssetManager,
    Assetic\AssetWriter;

use Zend\Mvc\Controller\AbstractActionController;

class AssetConsoleController extends AbstractActionController implements
    AssetManagerAwareInterface
{
    protected $assetManager;

    public function dumpAction()
    {
        $appConfig = $this->locate('Configuration');

        $writer    = new AssetWriter($appConfig['zf2_assetic']['assetDumpPath']);
        $writer->writeManagerAssets($this->assetManager);

        $httpRouter = $this->locate('httpRouter');
        $assetManifest   = array('assets' => array());
        foreach ($this->assetManager->getNames() as $name) {
            $asset = $this->assetManager->get($name);
            $url = $httpRouter->assemble(array(
                'resourcePath' => $asset->getTargetPath()
            ), array(
                'name' => $appConfig['zf2_assetic']['controllerRouteName'],
            ));
            $assetManifest['assets'][$name] = $url;
        }

        $manifestDir = dirname($appConfig['zf2_assetic']['assetManifestPath']);
        if (!is_dir($manifestDir)) {
            mkdir($manifestDir, 755, true);
        }

        if (false === file_put_contents($appConfig['zf2_assetic']['assetManifestPath'], json_encode($assetManifest))) {
            throw new \Exception('Unable to write asset manifest');
        }
    }


    public function setAssetManager(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
        return $this;
    }

}
