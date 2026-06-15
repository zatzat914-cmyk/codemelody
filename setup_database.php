<?php
require_once __DIR__ . '/config/config.php';

$sqlFile = __DIR__ . '/database/tables.sql';

if (!file_exists($sqlFile)) {
    die("Error: SQL file not found at " . $sqlFile);
}

$sql = file_get_contents($sqlFile);

try {
    $pdo->exec($sql);
    echo "Database tables created successfully!" . PHP_EOL;
} catch (PDOException $e) {
    echo "Failed to initialize database: " . $e->getMessage() . PHP_EOL;
}
