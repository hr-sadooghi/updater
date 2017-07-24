<?php
/**
 * Created by PhpStorm.
 * User: sadooghi
 * Date: 7/24/17
 * Time: 12:21 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
var_dump(__DIR__);
exit;
//find current version
list($channel, $current) = explode('-', file_get_contents('.version'));

//get update information
$a = file_get_contents("http://updater.dev/updates/update.json");
$updateInfo = json_decode($a, true);

foreach ($updateInfo['channels'] as $key => $item) {
    $latestVersion = $updateInfo['latest'][$key];
    $c = version_compare($latestVersion, $current);
    if ($c > 0) {

        echo "<p>$key($item)";
        $latestVersionInfo = $updateInfo[$key][$latestVersion];
//    var_dump($latestVersionInfo);
        echo "Version: <b>" . $latestVersion . "</b><br>";
        echo "Released at: <b>" . $latestVersionInfo['issue'] . "</b><br>";
        echo "Released notes: <b>" . $latestVersionInfo['description'] . "</b><br>";
        echo "<a href=\"check-update.php?start-update=$key-$latestVersion\"><i>Update</i></a><br>";
        echo "</p>";
    }
}

if (array_key_exists('start-update', $_GET)) {
    list($updateChannel, $updateVersion) = explode('-', $_GET['start-update']);
//    var_dump($updateChannel, $updateVersion);

    //get update
    $latestVersionInfo = $updateInfo[$updateChannel][$updateVersion];
    $updateFilename = basename($latestVersionInfo['file']);
    var_dump($updateFilename);
    $updateContent = file_get_contents($latestVersionInfo['file']);
    file_put_contents($updateFilename, $updateContent);
    $md5 = md5_file($updateFilename);
//    var_dump($md5);
    if ($md5 !== $latestVersionInfo['md5']) {
        die('checksum error!');
    }

//    exit;
    $zip = new ZipArchive;
    if ($zip->open($updateFilename) === TRUE) {
        $zip->extractTo('.');
        $zip->close();

        var_dump($latestVersionInfo);
        if(array_key_exists('after-update', $latestVersionInfo) && file_exists($latestVersionInfo['after-update'])){
            require_once $latestVersionInfo['after-update'];
        }

        echo 'Update success';
    } else {
        echo 'UPDATE failed';
    }
}