<?php

return array(
    'zf2_assetic' => array(
        'collections' => array(
            'base_css' => array(
                'root' => __DIR__ . '/../assets/',
                'assets' => array(
                    'css/test.css',
                ),
                'options' => array(
                    'output' => 'base.css',
                ),
            ),
            'filtered_css' => array(
                'root' => __DIR__ . '/../assets/',
                'assets' => array(
                    'css/test.css',
                ),
                'filters' => array(
                    'AsseticCssRewriteFilter',
                ),
                'options' => array(
                    'output' => 'dependent.js',
                ),
            ),
            'base_js' => array(
                'collectionName' => 'base_js',
                'root' => __DIR__ . '/../assets/',
                'assets' => array(
                    'js/test.js',
                ),
                'options' => array(
                    'output' => 'base.js',
                ),
            ),
            'dependent_js' => array(
                'root' => __DIR__ . '/../assets/',
                'assets' => array(
                    '@base_js',
                    'js/test.js',
                ),
                'options' => array(
                    'output' => 'dependent.js',
                ),
            ),
        ),
        'contentTypeMap' => array(
            'css' => 'text/css',
            'js'  => 'application/js',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
        ),
    ),
);

