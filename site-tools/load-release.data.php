#!/usr/bin/env php
<?php

/*
 * This loads the release data for a file. Doesn't set the documentation
 * page, third party download details, or release status.
 *
 * File format is:
 *
 * Download page URL
 *
 * Output of sha256sum
 *
 * For example:

https://sourceforge.net/projects/boost/files/boost/1.62.0/

b91c2cda8bee73ea613130e19e72c9589e9ef0357c4c5cc5f7523de82cce11f7  boost_1_62_0.7z
36c96b0f6155c98404091d8ceb48319a28279ca0333fba1ad8611eb90afb2ca0  boost_1_62_0.tar.bz2
440a59f8bc4023dbe6285c9998b0f7fa288468b889746b1ef00e8b36c559dce1  boost_1_62_0.tar.gz
084b2e0638bbe0975a9e43e21bc9ceae33ef11377aecab3268a57cf41e405d4e  boost_1_62_0.zip

 */

require_once(__DIR__.'/../common/code/bootstrap.php');

function main() {
    $args = $_SERVER['argv'];

    if (count ($args) != 2) {
        echo "Usage: load-release-data.php path\n";
        exit(1);
    }

    $path = realpath($args[1]);
    if (!$path) {
        echo "Unable to find release file: {$args[1]}\n";
        exit(1);
    }

    $release_details = file_get_contents($path);
    if (!$release_details) {
        echo "Error reading release file: {$args[1]}\n";
        exit(1);
    }

    $releases = new BoostReleases(__DIR__.'/../generated/state/release.txt');
    $releases->loadReleaseInfo($release_details);
    $releases->save();
}

main();
