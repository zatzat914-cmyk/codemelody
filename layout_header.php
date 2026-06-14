<?php
// layout_header.php - Shared HTML header and sidebar component

$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . " - EduLect" : "EduLect - Learning Platform"; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">E</div>
            <div class="logo-text">Edu<span>Lect</span></div>
        </div>
        <div class="nav-section">
            <div class="nav-label">Main Menu</div>
            <a href="dashboard.php" class="nav-item <?php echo ($currentPage === 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i><span>Dashboard</span>
            </a>
            <a href="my_courses.php" class="nav-item <?php echo ($currentPage === 'my_courses.php' || $currentPage === 'courses.php') ? 'active' : ''; ?>">
                <i class="fas fa-book-open"></i><span>My Courses</span><span class="nav-badge"><?php echo $global_courses_count; ?></span>
            </a>
            <a href="students.php" class="nav-item <?php echo ($currentPage === 'students.php') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i><span>Students</span><span class="nav-badge"><?php echo $global_students_count; ?></span>
            </a>
            <a href="analytics.php" class="nav-item <?php echo ($currentPage === 'analytics.php') ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i><span>Analytics</span>
            </a>
            <a href="live_sessions.php" class="nav-item <?php echo ($currentPage === 'live_sessions.php') ? 'active' : ''; ?>">
                <i class="fas fa-video"></i><span>Live Sessions</span><span class="nav-badge" style="background: var(--danger);">LIVE</span>
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Content</div>
            <a href="assignments.php" class="nav-item <?php echo ($currentPage === 'assignments.php') ? 'active' : ''; ?>">
                <i class="fas fa-tasks"></i><span>Assignments</span>
            </a>
            <a href="quizzes.php" class="nav-item <?php echo ($currentPage === 'quizzes.php') ? 'active' : ''; ?>">
                <i class="fas fa-question-circle"></i><span>Quizzes</span>
            </a>
            <a href="resources.php" class="nav-item <?php echo ($currentPage === 'resources.php') ? 'active' : ''; ?>">
                <i class="fas fa-folder-open"></i><span>Resources</span>
            </a>
        </div>
        <div class="user-profile" onclick="window.location='logout.php'">
            <div class="user-avatar"><?php echo htmlspecialchars($user['avatar_initials'] ?? 'JD'); ?></div>
            <div class="user-info">
                <h4><?php echo htmlspecialchars($user['full_name'] ?? 'Jane Doe'); ?></h4>
                <p><?php echo ucfirst(htmlspecialchars($user['role'] ?? 'lecturer')); ?></p>
            </div>
            <i class="fas fa-chevron-right" style="color: var(--gray); font-size: 12px;"></i>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search courses, students, or resources...">
                </div>
            </div>
            <div class="header-right">
                <button class="header-btn" onclick="showNotifications()"><i class="fas fa-bell"></i><span class="notification-dot"></span></button>
                <button class="header-btn" onclick="showToast('Inbox is clean!')"><i class="fas fa-envelope"></i></button>
                <button class="header-btn" onclick="showToast('Settings panel opened!')"><i class="fas fa-cog"></i></button>
                <button class="create-btn" onclick="openModal()"><i class="fas fa-plus"></i>Create New</button>
            </div>
        </header>

        <div class="content" id="<?php echo isset($contentId) ? htmlspecialchars($contentId) : ''; ?>">
