<?php

use CodeIgniter\Boot;
use Config\Paths;

// Minimum PHP Version
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    echo "PHP version must be {$minPhpVersion} or higher.";
    exit(1);
}

// ---------------------------------------------------------
// AUTO DETECT ENVIRONMENT (LOCAL XAMPP / HOSTING CPANEL)
// ---------------------------------------------------------

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

$isLocal = PHP_OS_FAMILY === 'Windows';

// LOCAL (XAMPP)
if ($isLocal) {
    $pathsPath = realpath(FCPATH . '/../app/Config/Paths.php');
}
// HOSTING (CPANEL)
else {
    $pathsPath = realpath(FCPATH . '/../ci4_app/app/Config/Paths.php');
}

// Fallback jika tidak ditemukan
if (!is_file($pathsPath)) {
    $pathsPath = realpath(FCPATH . '/../app/Config/Paths.php');
}

// Load Paths.php
require $pathsPath;

// Buat instance Paths
$paths = new Paths();

// ---------------------------------------------------------
// Load CodeIgniter
// ---------------------------------------------------------

// LOCAL (vendor ada di root)
if ($isLocal) {
    require realpath(FCPATH . '/../vendor/codeigniter4/framework/system/Boot.php');
}
// HOSTING (vendor ada di ci4_app/vendor)
else {
    require realpath(FCPATH . '/../ci4_app/vendor/codeigniter4/framework/system/Boot.php');
}

exit(Boot::bootWeb($paths));
