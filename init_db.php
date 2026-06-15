<?php
require_once __DIR__ . '/config.php';

try {
    $sqlFile = __DIR__ . '/database/schema.sql';

    if (!file_exists($sqlFile)) {
        die("Error: SQL file not found at " . $sqlFile);
    }

    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);

    echo "🎉 Database tables created successfully!";
} catch (PDOException $e) {
    echo "❌ Failed to initialize database: " . $e->getMessage();
}
