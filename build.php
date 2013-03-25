<?php



function buildPackage($dir, $name) {
    $xml = simplexml_load_file($dir.'module.xml');
    $version = (string)$xml->info[0]->version;

    echo $version;
    echo "\n";
    
    chdir($dir.'../');
    exec('zip -r ../'.$name.'-'.$version.'.zip '.$name);
}


buildPackage(__DIR__.'/modules/jcommunity/', 'jcommunity');
buildPackage(__DIR__.'/modules/jcommunity_phorum/', 'jcommunity_phorum');
buildPackage(__DIR__.'/modules/jmessenger/', 'jmessenger');


