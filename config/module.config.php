<?php
return array(
    'router' => array(
        'routes' => array(
            'asset' => array(
                'type' => 'regex',
                'options' => array(
                    'regex' => '/asset/(?<resourcePath>.*)',
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

