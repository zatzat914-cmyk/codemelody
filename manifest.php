<?php
require_once __DIR__ . '/config/bootstrap.php';

header('Content-Type: application/json');

$base = rtrim(APP_BASE, '/');

echo json_encode([
    "name" => "CodeMelody Learning Platform",
    "short_name" => "CodeMelody",
    "description" => "Online course management and learning platform",
    "start_url" => $base . '/index.php',
    "display" => "standalone",
    "background_color" => "#f8fafc",
    "theme_color" => "#4A90E2",
    "orientation" => "portrait-primary",
    "icons" => [
        [
            "src" => $base . "/assets/images/icon-192.png",
            "sizes" => "192x192",
            "type" => "image/png",
            "purpose" => "any maskable"
        ],
        [
            "src" => $base . "/assets/images/icon-512.png",
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "any maskable"
        ]
    ]
]);
