<?php

$baseDir = __DIR__ . '/../public/';
if (($_SERVER['SCRIPT_NAME'] !== '/') &&
    (file_exists($baseDir . $_SERVER['SCRIPT_NAME']))) {
    return false;
}

require $baseDir . 'index.php';
