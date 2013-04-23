<?php
return array(
    'router' => array(
        'routes' => array(
            'asset' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/asset/:collection',
                    'defaults' => array(
                        'controller' => 'asset',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'AsseticAssetManager' => 'ZF2Assetic\AssetManagerFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'asset'         => 'ZF2Assetic\Controller\AssetController',
        ),
    ),
    'zf2_assetic' => array(
    ),
);

