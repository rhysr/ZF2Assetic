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
    'service_manager' => array(
        'factories' => array(
            'AsseticAssetManager'        => 'ZF2Assetic\AssetManagerFactory',
            'AsseticContentTypeResolver' => 'ZF2Assetic\ContentTypeResolverFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'asset'         => 'ZF2Assetic\Controller\AssetController',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'assetPath' => 'ZF2Assetic\View\Helper\AssetPathFactory',
        ),
    ),
    'zf2_assetic' => array(
        'contentTypeMap' => array(
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'png'  => 'image/png',
        ),
    ),
);

