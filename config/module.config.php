<?php
return array(
    'router' => array(
        'routes' => array(
            'asset' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/assets/(?<resourcePath>.*)',
                    'defaults' => array(
                        'controller' => 'asset',
                        'action'     => 'index',
                    ),
                    'spec' => '/assets/%resourcePath%',
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'asset-dump' => array(
                    'type' => 'simple',
                    'options' => array(
                        'route' => 'assets dump',
                        'defaults' => array(
                            'controller' => 'assetconsole',
                            'action'     => 'dump',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'AssetManifest'              => 'ZF2Assetic\AssetManifestFactory',
            'AsseticAssetManager'        => 'ZF2Assetic\AssetManagerFactory',
            'AsseticContentTypeResolver' => 'ZF2Assetic\ContentTypeResolverFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'asset'         => 'ZF2Assetic\Controller\AssetController',
            'assetconsole'  => 'ZF2Assetic\Controller\AssetConsoleController',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'assetPath' => 'ZF2Assetic\View\Helper\AssetPathFactory',
        ),
    ),
    'zf2_assetic' => array(
        'controllerRouteName' => 'asset',
        'contentTypeMap'      => array(
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'png'  => 'image/png',
        ),
        'useAssetController' => true,
    ),
);

