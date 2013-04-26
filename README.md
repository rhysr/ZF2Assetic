ZF2Assetic
==========

Assetic module for Zend Framework 2

## TODO

 - asset controller
  - Custom header injection in collection config
  - If-Modified-Since support
  - Expires
  - ~~Content-Type~~
  - ~~Last-Modified~~
  - Etags
 - asset dump script
 - better asset factory config
 - ~~get filter manager~~
   - ~~not needed, use the service manager~~
 - Investigate feasiblity of creating AsseticFilterPluginManager
 - ~~resolve *.{css,js} collection route param into collection name (which doesn't like a dot in the name)~~
 - view helper
 - handle paths inside route
   - cssrewritefilter url() might have a deeper path
   - /asset/bootstrap/img/glyphicons-halflings.png
