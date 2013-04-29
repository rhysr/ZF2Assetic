<?php

namespace ZF2Assetic\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\Mvc\Router\RouteStackInterface;

use ZF2Assetic\InvalidArgumentException,
    ZF2Assetic\RuntimeException;

use Assetic\AssetManager;

class AssetPath extends AbstractHelper
{
    /**
     * Asset manager
     *
     * @var \Asssetic\AssetManager
     */
    protected $assetManager;

    /**
     * Router
     *
     * @var \Zend\Mvc\Router\Http\RouteStackInterface
     */
    protected $router;

    /**
     * Asset controller route name
     *
     * @var string
     */
    protected $routeName;

    /**
     * @param \Assetic\AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager, RouteStackInterface $router, $routeName)
    {
        $this->assetManager = $assetManager;
        $this->router       = $router;
        $this->routeName    = $routeName;
    }

    public function __invoke($assetName)
    {
        return $this->getAssetPath($assetName);
    }

    public function has($assetName)
    {
        return $this->assetManager->has($assetName);
    }

    public function getAssetPath($assetName)
    {
        if (!$this->assetManager->has($assetName)) {
            throw new InvalidArgumentException('Unknown asset ' . var_export($assetName, true));
        }
        $asset = $this->assetManager->get($assetName);

        try {
            $url = $this->router->assemble(array(
                'resourcePath' => $asset->getTargetPath()
            ), array(
                'name' => $this->routeName
            ));
        } catch (\Zend\Mvc\Router\Exception\RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }

        //route encodes slashes in url separators
        return rawurldecode($url);
    }

    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
        return $this;
    }
}

