<?php
/**
 * ==============================================================================
 * CUSTOM ADMIN PANEL - SHARED HEADER (admin/includes/header.php)
 * Clean, WordPress-like custom CMS interface for Kelvin Kibenje
 * ==============================================================================
 */

require_once __DIR__ . '/../../includes/functions.php';
require_admin_login();

$logged_admin = get_logged_in_admin();
$current_admin_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title ?? 'Admin Dashboard') ?> - Kelvin Kibenje CMS</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=2026.1">
    <link rel="stylesheet" href="/assets/admin/admin.css?v=2026.1">
    <!-- Quill.js WYSIWYG CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
</head>
<body class="admin-body">

<!-- Admin Navigation Bar -->
<header class="admin-topbar">
    <div class="admin-topbar-left">
        <a href="/admin/index.php" class="admin-brand">
            <span class="admin-brand-logo">KELVIN KIBENJE</span>
            <span class="admin-brand-tag">CMS Admin</span>
        </a>
        <a href="/" target="_blank" class="btn btn-outline-gold" style="padding: 4px 12px; font-size: 12px;">
            ↗ View Live Website
        </a>
    </div>

    <div class="admin-topbar-right">
        <span class="admin-user-name">
            Welcome, <strong><?= h($logged_admin['name'] ?? 'Admin') ?></strong>
            <span class="admin-role-badge"><?= h(strtoupper($logged_admin['role'] ?? 'ADMIN')) ?></span>
        </span>
        <a href="/admin/profile.php" class="admin-link">Profile &amp; Password</a>
        <a href="/admin/logout.php" class="btn btn-gold" style="padding: 4px 12px; font-size: 12px;">Logout</a>
    </div>
</header>

<div class="admin-layout">
    <!-- Admin Sidebar Navigation -->
    <aside class="admin-sidebar">
        <nav class="admin-nav">
            <a href="/admin/index.php" class="admin-nav-item <?= $current_admin_page === 'index' ? 'active' : '' ?>">
                📊 Dashboard
            </a>
            <a href="/admin/blog.php" class="admin-nav-item <?= in_array($current_admin_page, ['blog', 'blog-edit']) ? 'active' : '' ?>">
                ✍️ Blog &amp; Articles
            </a>
            <a href="/admin/books.php" class="admin-nav-item <?= in_array($current_admin_page, ['books', 'book-edit']) ? 'active' : '' ?>">
                📚 Books (Shop)
            </a>
            <a href="/admin/orders.php" class="admin-nav-item <?= in_array($current_admin_page, ['orders', 'order-detail']) ? 'active' : '' ?>">
                🛒 Book Orders
            </a>
            <a href="/admin/inquiries.php" class="admin-nav-item <?= $current_admin_page === 'inquiries' ? 'active' : '' ?>">
                💬 Inquiries &amp; Bookings
            </a>
            <a href="/admin/subscribers.php" class="admin-nav-item <?= $current_admin_page === 'subscribers' ? 'active' : '' ?>">
                ✉️ Newsletter List
            </a>
            <a href="/admin/settings.php" class="admin-nav-item <?= $current_admin_page === 'settings' ? 'active' : '' ?>">
                ⚙️ Site Settings
            </a>
            <a href="/admin/profile.php" class="admin-nav-item <?= $current_admin_page === 'profile' ? 'active' : '' ?>">
                🔒 Admin Account
            </a>
        </nav>
    </aside>

    <!-- Admin Main Content Area -->
    <main class="admin-content">
        <div class="admin-container">
