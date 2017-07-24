<?php
/**
 * Created by PhpStorm.
 * User: hamid
 * Date: 7/24/2017 AD
 * Time: 22:51
 */

namespace Hamsal;


class Updater
{
    const DEFAULT_UPDATE_URL = 'http://updater.dev/updates/update.json';

    protected $appBaseDir;
    protected $updateURL;
    protected $updateInfo;

    public function __construct($appBaseDir, $updateURL = self::DEFAULT_UPDATE_URL)
    {
        $this->updateURL = $updateURL;
        $this->appBaseDir = $appBaseDir;
    }

    public function getCurrentVersion()
    {
        //find current version
        list($channel, $current) = explode('-', file_get_contents('.version'));
        return [$channel, $current];
    }

    public function getUpdateInfo()
    {
        //load update information
        $a = file_get_contents($this->updateURL);
        $this->updateInfo = json_decode($a, true);
        return $this->updateInfo;
    }

    public function hasNewerUpdateInCurrentChannel()
    {
    }

    public function doUpdate($updateChannel, $updateVersion)
    {
        //get update
        $latestVersionInfo = $this->updateInfo[$updateChannel][$updateVersion];

        //take file name to store update package
        $updateFilename = basename($latestVersionInfo['file']);
        var_dump($updateFilename);

        //download update package
        $updateContent = file_get_contents($latestVersionInfo['file']);

        //store update package on disk
        file_put_contents($updateFilename, $updateContent);

        //calculate update package checksum
        $md5 = md5_file($updateFilename);

        //verify checksum
        if ($md5 !== $latestVersionInfo['md5']) {
            die('checksum error!');
        }

        $zip = new \ZipArchive;
        if ($zip->open($updateFilename) === TRUE) {
            $zip->extractTo($this->appBaseDir);
            $zip->close();

            var_dump($latestVersionInfo);
            if (array_key_exists('after-update', $latestVersionInfo) && file_exists($latestVersionInfo['after-update'])) {
                require_once $latestVersionInfo['after-update'];
            }

            echo 'Update success';
        } else {
            echo 'UPDATE failed';
        }
    }

}