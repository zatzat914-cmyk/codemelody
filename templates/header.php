<?php
$currentPath = trim(str_replace('\\', '/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
$m = metrics($pdo);
$role = $user['role'] ?? 'student';

$nav = [
    'admin' => [
        ['dashboard/admin.php', 'fa-th-large', 'Dashboard', null],
        ['courses/index.php', 'fa-book-open', 'Courses', $m['courses']],
        ['courses/create.php', 'fa-plus-circle', 'New Course', null],
        ['lecturers/index.php', 'fa-chalkboard-user', 'Lecturers', null],
        ['students/index.php', 'fa-users', 'Students', $m['students']],
        ['students/create.php', 'fa-user-plus', 'New Student', null],
    ],
    'lecturer' => [
        ['dashboard/lecturer.php', 'fa-th-large', 'Dashboard', null],
        ['courses/index.php', 'fa-book-open', 'My Courses', $m['courses']],
        ['courses/create.php', 'fa-plus-circle', 'New Course', null],
        ['students/index.php', 'fa-users', 'Students', $m['students']],
    ],
    'student' => [
        ['dashboard/student.php', 'fa-th-large', 'Dashboard', null],
        ['courses/index.php', 'fa-compass', 'Explore Courses', $m['courses']],
        ['students/edit.php', 'fa-user-pen', 'My Profile', null],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?> - CodeMelody</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo app_url('assets/css/dashboard.css'); ?>">
    <script>const APP_BASE = '<?php echo APP_BASE; ?>';</script>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">C</div>
        <div class="logo-text">Code<span>Melody</span></div>
    </div>
    <div class="nav-section">
        <div class="nav-label"><?php echo ucfirst(htmlspecialchars($role)); ?> Menu</div>
        <?php foreach ($nav[$role] as $item): ?>
            <?php $active = str_ends_with($currentPath, $item[0]); ?>
            <a href="<?php echo app_url($item[0]); ?>" class="nav-item <?php echo $active ? 'active' : ''; ?>">
                <i class="fas <?php echo $item[1]; ?>"></i><span><?php echo htmlspecialchars($item[2]); ?></span>
                <?php if ($item[3] !== null): ?><span class="nav-badge"><?php echo (int)$item[3]; ?></span><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="user-profile" onclick="window.location='<?php echo app_url('auth/logout.php'); ?>'">
        <div class="user-avatar"><?php echo htmlspecialchars($user['avatar_initials'] ?? avatar_initials($user['full_name'] ?? 'User')); ?></div>
        <div class="user-info">
            <h4><?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?></h4>
            <p><?php echo ucfirst(htmlspecialchars($role)); ?></p>
        </div>
        <i class="fas fa-chevron-right" style="color: var(--gray); font-size: 12px;"></i>
    </div>
</aside>
<main class="main-content">
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="search-bar" id="globalSearch">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search courses..." id="searchInput" autocomplete="off">
                <div class="search-dropdown" id="searchDropdown"></div>
            </div>
        </div>
        <div class="header-right">
            <div class="notification-wrapper" id="notificationWrapper">
                <button class="header-btn" id="notificationBell" onclick="toggleNotifications()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot" id="notificationDot"></span>
                    <span class="notification-badge" id="notificationBadge" style="display:none;">0</span>
                </button>
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-dropdown-body" id="notificationList">
                    </div>
                </div>
            </div>
            <?php if ($role !== 'student'): ?>
                <button class="create-btn" onclick="window.location='<?php echo app_url('courses/create.php'); ?>'"><i class="fas fa-plus"></i>Create Course</button>
            <?php endif; ?>
        </div>
    </header>
    <div class="content" id="<?php echo htmlspecialchars($contentId ?? ''); ?>">
