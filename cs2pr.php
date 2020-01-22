#!/usr/bin/env php
<?php

$version = '1.0-dev';

if ($argc === 1) {
    $xml = stream_get_contents(STDIN);
} elseif ($argc === 2 && file_exists($argv[1])) {
    $xml = file_get_contents($argv[1]);
} else {
    echo "cs2pr $version\n";
    echo "Annotate a Github Pull Request based on a Checkstyle XML-report.\n";
    echo "Usage: php ". $argv[0] ." <filename>\n";
    exit(1);
}

$root = simplexml_load_string($xml);

foreach($root as $file) {
    $filename = (string)$file['name'];

    foreach($file as $error) {
        $type = (string) $error['severity'];
        $line = (string) $error['line'];
        $message = (string) $error['message'];

        annotateCheck($type, $filename, $line, $message);
    }
}

/**
 * @param 'error'|'warning' $type
 * @param string $filename
 * @param int $line
 * @param string $message
 */
function annotateCheck($type, $filename, $line, $message) {
    echo "::{$type} file={$filename},line={$line}::{$message}\n";
}