ZF2Assetic
==========

Assetic module for Zend Framework 2

This module generates in two ways either by creating the asset each time it is requested via the controller or by generating the asset to disc by using a script and then letting the webserver handle it

## Config

all config lives in the zf2_assetic array
``` php

return array(
   'useAssetController' => true, //use controller for asset, false means read from disk, recommend true for dev
   'assetManifestPath' => __DIR__ . '/../data/asset-manifest.json', //path to a json file that maps the assetName to it's path on disk, generated by build script
   'collections' => array(
       //array of all defined asset collections the module will provide

   'base_css' => array(
           'root' => __DIR__ . '/../assets/', //source directory for this collection's assets
           'assets' => array( //list of assets to be compiled into collection
               'css/test.css',
           ),
           'filters' => array(
               'AsseticCssRewriteFilter', //service manager name of assetic filters to apply
           ),
           'options' => array(
               'output' => 'base.css', //public path to collection
           ),
    ),

 )
);
```

## Precompiling assets

when useAssetController is true the assetPath view helper when generate paths to the disk based assets.
Disk based assets need to be generated via the command line

``` sh
php public/index.php assets dump
```
This will generate a json manifest file containing a map of asset collection name to path which the view helper uses.
When serving compiled assets from disk Assetic is not used in anyway.


## View helper

The module has two asset path helpers for generating paths. The useAssetController flag determines which view helper is created.
One serves urls to the asset controller, the other gets paths from the json asset manifest. 

``` php
   <link href="<?= $this->assetPath('base_css') ?>" media="screen" rel="stylesheet" type="text/css">
```

## TODO

 - console controller needs tests
 - factories for all the assetic filters
 - params for filter factories e.g. node binary path
