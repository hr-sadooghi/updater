# PHP Application Updater
This is updater script for PHP code.
With this script you can build your web application that support online update so your PHP application can easily updated.

To update you PHP application run `check-update.php`.

First you need to specify information about new version package in `updates/update.json` file.

This file can be replaced and modify to any file that return our standard JSON structure.

Sample code for `update.json`:
```json
{
  "latest": {
    "version": "1.3.1",
    "issue": "2017-8-17 12:24:00",
    "requirement": {
      "php": ">5.5.6",
      "mysql": ">5.6"
    },
    "file": "http://localhost/updater/updates/1.3.1/package.zip",
    "checksum":""
  }
}
```

Structure of `update.json`:
```json
{
  "latest": {
    "version": "new version number like x.y.z",
    "issue": "new version release date and time",
    "requirement": {
      "php": ">5.5.6",
      "mysql": ">5.6"
    },
    "file": "full URL for packaged files of new version in the zip file",
    "checksum":"Update package md5 checksum"
  }
}
```