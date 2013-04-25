<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            //TODO: create factories
            'AsseticCssRewriteFilter' => 'Assetic\Filter\CssRewriteFilter',
        ),
    ),
    'zf2_assetic' => array(
        'collections' => array(
            'base.css' => array(
                'collectionName' => 'base_css',
                'root' => __DIR__ . '/../assets/',
                'assets' => array(
                    'css/test.css',
                ),
                'filters' => array(
                    //service manager name
                    'AsseticCssRewriteFilter',
                ),
            ),
        ),
    ),
);

