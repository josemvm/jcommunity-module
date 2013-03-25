<?php



$xml = simplexml_load_file(__DIR__.'/modules/jcommunity/module.xml');
$version = (string)$xml->info[0]->version;
exec('zip -r jcommunity-'.$version.'.zip modules/');


