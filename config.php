<?php
// Enable error display so we can see the crash
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load the actual configuration
require_once __DIR__ . '/config/config.php';
?>
