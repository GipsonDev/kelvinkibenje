<?php
/**
 * ==============================================================================
 * SHARED SITE HEADER & NAVIGATION (header.php)
 * Responsive HTML5 Header with Golden Accent & Mobile Menu
 * ==============================================================================
 */

require_once __DIR__ . '/functions.php';

// Determine active page for dynamic nav highlight
$current_page = basename($_SERVER['PHP_SELF'], '.php');
if ($current_page === 'index') {
    $current_page = 'home';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($page_title ?? 'Kelvin Kibenje | Speaker • Author • Investor') ?></title>
    <meta name="description" content="<?= h($page_description ?? get_setting('site_about_summary')) ?>">
    <meta name="keywords" content="Kelvin Kibenje, Financial Literacy Tanzania, China to Tanzania, BOT Certified Financial Educator, Books, Investing, Dar es Salaam">
    
    <!-- Open Graph / Facebook & WhatsApp Share Tags -->
    <meta property="og:title" content="<?= h($page_title ?? 'Kelvin Kibenje | Speaker • Author • Investor') ?>">
    <meta property="og:description" content="<?= h($page_description ?? get_setting('site_about_summary')) ?>">
    <meta property="og:image" content="<?= h($page_image ?? SITE_URL . '/images/hero.jpg') ?>">
    <meta property="og:url" content="<?= h(SITE_URL . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:type" content="website">

    <!-- Favicon / Touch Icon -->
    <link rel="icon" type="image/jpg" href="/images/avatar.jpg">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/style.css?v=2026.1">
    <!-- Font Awesome / Icon Fonts (Vendored clean SVG icons used in CSS for zero external dependency breakage) -->
</head>
<body>

<!-- Top Notification Bar (BOT Certified Disclaimer & Instagram Proof) -->
<div class="top-bar">
    <div class="container top-bar-inner">
        <div class="top-bar-left">
            <span class="badge-bot">✓ Certified Financial Educator by BOT (Bank of Tanzania)</span>
            <span class="badge-award">🏆 Two-Time Golden Man of the Year (22/23)</span>
        </div>
        <div class="top-bar-right">
            <a href="<?= h(get_setting('instagram_url', 'https://instagram.com/kelvinkibenje')) ?>" target="_blank" rel="noopener" class="top-social-link">
                <span>499K+ Followers on @kelvinkibenje</span>
                <span class="instagram-icon">IG</span>
            </a>
        </div>
    </div>
</div>

<!-- Main Header & Navigation -->
<header class="main-header" id="mainHeader">
    <div class="container header-inner">
        <!-- Logo -->
        <a href="/" class="brand-logo">
            <span class="logo-name">KELVIN KIBENJE</span>
            <span class="logo-tagline">Speaker • Author • Investor</span>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="nav-links" id="navLinks">
            <a href="/" class="nav-link <?= $current_page === 'home' ? 'active' : '' ?>">Home</a>
            <a href="/about.php" class="nav-link <?= $current_page === 'about' ? 'active' : '' ?>">About</a>
            <a href="/speaking.php" class="nav-link <?= $current_page === 'speaking' ? 'active' : '' ?>">Speaking &amp; Impact</a>
            <a href="/books.php" class="nav-link <?= in_array($current_page, ['books', 'book']) ? 'active' : '' ?>">Books (Shop)</a>
            <a href="/blog.php" class="nav-link <?= in_array($current_page, ['blog', 'post']) ? 'active' : '' ?>">Blog</a>
            <a href="/contact.php" class="nav-link <?= $current_page === 'contact' ? 'active' : '' ?>">Contact</a>
            
            <!-- Mobile Only CTA inside Nav -->
            <div class="mobile-only-cta">
                <a href="/books.php" class="btn btn-gold btn-block">Order Books</a>
                <a href="/speaking.php" class="btn btn-outline-gold btn-block mt-2">Book to Speak</a>
            </div>
        </nav>

        <!-- Right CTA Button (Desktop) -->
        <div class="header-cta">
            <a href="/books.php" class="btn btn-gold">Order Books</a>
        </div>

        <!-- Mobile Menu Hamburger Button -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation">
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
    </div>
</header>

<!-- Main Page Content Wrapper -->
<main class="main-content">
