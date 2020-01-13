<?php

require_once __DIR__ . '/functions.php';

if (!isset($argv[1])) {
    echo "Missing input file name\n";
    exit(1);
}

define('PROJECT', 'chronext');

$htmlStructure = json_decode(file_get_contents($argv[1]));

anonymizeHtml($htmlStructure);

file_put_contents($argv[1], json_encode($htmlStructure, JSON_PRETTY_PRINT));
