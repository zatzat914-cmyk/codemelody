<?php
require_once __DIR__ . '/config/bootstrap.php';

$user = require_login($pdo);
$__legacyMetrics = metrics($pdo);
$global_students_count = $__legacyMetrics['students'];
$global_courses_count = $__legacyMetrics['courses'];
$global_units_count = $__legacyMetrics['units'];
$global_hours_count = $__legacyMetrics['hours'];
