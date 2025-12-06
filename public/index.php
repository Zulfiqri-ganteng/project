<?php

use CodeIgniter\Boot;
use Config\Paths;

// -----------------------------------------
//  Minimal PHP Version
// -----------------------------------------
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    echo "PHP version must be {$minPhpVersion} or higher.";
    exit(1);
}

// -----------------------------------------
//  Base Path
// -----------------------------------------
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// -----------------------------------------
//  Detect LOCAL (XAMPP) vs HOSTING (cPanel)
// -----------------------------------------
$isLocal = false;

if (
    isset($_SERVER['SERVER_NAME']) &&
    (
        strpos($_SERVER['SERVER_NAME'], 'localhost') !== false ||
        strpos($_SERVER['SERVER_NAME'], '127.0.0.1') !== false
    )
) {
    $isLocal = true;
}

// -----------------------------------------
//  Set Path to Paths.php
// -----------------------------------------
// LOCAL → pakai struktur default CodeIgniter
if ($isLocal) {
    $pathsPath = realpath(FCPATH . '/../app/Config/Paths.php');
}
// HOSTING → pakai folder ci4_app
else {
    $pathsPath = realpath(FCPATH . '/../ci4_app/app/Config/Paths.php');
}

// fallback jika file tidak ditemukan
if (!is_file($pathsPath)) {
    $pathsPath = realpath(FCPATH . '/../app/Config/Paths.php');
}

// load Paths.php
require $pathsPath;

$paths = new Paths();

// -----------------------------------------
//  Load Boot.php berdasarkan LOCAL/HOSTING
// -----------------------------------------
if ($isLocal) {
    // LOCAL (pakai vendor lokal)
    $boot = realpath(FCPATH . '/../vendor/codeigniter4/framework/system/Boot.php');
} else {
    // HOSTING (vendor dipindah ke ci4_app/vendor)
    $boot = realpath(FCPATH . '/../ci4_app/vendor/codeigniter4/framework/system/Boot.php');
}

require $boot;

// -----------------------------------------
//  Jalankan aplikasi
// -----------------------------------------
exit(Boot::bootWeb($paths));
