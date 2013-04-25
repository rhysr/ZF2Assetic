<?php
return array(
    'service_manager' => array(
        'factories' => array(
            //TODO: create factories
            'AsseticCssRewriteFilter' => 'ZF2Assetic\Factory\CssRewriteFilterFactory',
        ),
    ),
    'zf2_assetic' => array(
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
            ),
        ),
    ),
);

