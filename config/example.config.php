<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            //TODO: create factories
            'AsseticCssRewriteFilter' => 'Assetic\Filter\CssRewriteFilter',
        ),
    ),
    'zf2_assetic' => array(
        'useAssetController' => false,
        'collections' => array(
            'base_css' => array(
                'root' => __DIR__ . '/../assets/',
                'assets' => array(
                    'css/test.css',
                ),
                'filters' => array(
                    //service manager name
                    'AsseticCssRewriteFilter',
                ),
                'options' => array(
                    'output' => 'base.css',
                ),
            ),
        ),
    ),
);

