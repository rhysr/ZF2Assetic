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
            'AsseticAssetManager' => 'ZF2Assetic\AssetManagerFactory',
            //TODO: move this to factory class with unit test
            'AsseticContentTypeResolver' => function ($sm) {
                $resolver = new \ZF2Assetic\ContentTypeResolver();
                $resolver->addMapping('css', 'text/css');
                $resolver->addMapping('js', 'application/javascript');
                $resolver->addMapping('png', 'image/png');

                return $resolver;
            },
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

