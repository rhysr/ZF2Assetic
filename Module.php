<?php

namespace ZF2Assetic;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\ModuleManager\Feature\ControllerProviderInterface;

class Module
    implements
        AutoloaderProviderInterface,
        ConfigProviderInterface,
        ControllerProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__ . '/'
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function ($controller, $pluginManager) {
                    if ($controller instanceof AssetManagerAwareInterface) {
                        $controller->setAssetManager($pluginManager->getServiceLocator()->get('AsseticAssetManager'));
                        $config = $pluginManager->getServiceLocator()->get('config');
                        $controller->setConfig($config['zf2_assetic']['collections']);
                    }
                    if ($controller instanceof ContentTypeResolverAwareInterface) {
                        $controller->setContentTypeResolver($pluginManager->getServiceLocator()->get('AsseticContentTypeResolver'));
                    }
                },
            ),
        );
    }
}

