<?php
/**
 * Created by PhpStorm.
 * User: sadooghi
 * Date: 7/24/17
 * Time: 12:21 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$current = '1.2';

$a = file_get_contents("http://localhost/updater/update.json");
$new = json_decode($a, true);
$c = version_compare($new['latest']['version'], $current);
//var_dump($c);
//var_dump($new);

if ($c > 0){
    //get update
    $updateFilename = basename($new['latest']['file']);
    var_dump($updateFilename);
    $updateContent= file_get_contents($new['latest']['file']);
    file_put_contents($updateFilename, $updateContent);


    $zip = new ZipArchive;
    if ($zip->open($updateFilename) === TRUE) {
        $zip->extractTo('.');
        $zip->close();
        echo 'ok';
    } else {
        echo 'failed';
    }
}