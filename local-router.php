<?php
// LOCAL ONLY (php -S ...). Do not upload to GoDaddy.
// Serves real static files (images/css/js) and sends everything else to Laravel's index.php.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . urldecode($path);

if ($path !== '/' && is_file($file)
    && pathinfo($file, PATHINFO_EXTENSION) !== 'php'
    && strpos(basename($file), '.') !== 0) {
    return false;
}

require_once __DIR__ . '/index.php';
