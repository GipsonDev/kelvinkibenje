<?php
/**
 * ==============================================================================
 * KELVIN KIBENJE OFFICIAL PERSONAL BRAND & BOOKSHOP WEBSITE
 * Central Configuration File (config.php)
 * ==============================================================================
 * 
 * INSTRUCTIONS FOR cPanel / SHARED HOSTING SETUP:
 * 1. Open your cPanel -> MySQL Database Wizard.
 * 2. Create a database (e.g., yourusername_kelvin_db).
 * 3. Create a MySQL user and password, and attach the user to the database with ALL PRIVILEGES.
 * 4. Enter your Database Name, Username, and Password in the SECTION 1 below.
 * 5. In phpMyAdmin, select your new database and click "Import", then upload 'schema.sql'.
 * ==============================================================================
 */

// Prevent direct access to configuration file if included improperly
if (basename($_SERVER['PHP_SELF']) === 'config.php') {
    die('Direct access not permitted.');
}

// ------------------------------------------------------------------------------
// SECTION 1: DATABASE CREDENTIALS (EDIT THESE FOR YOUR cPANEL HOST)
// ------------------------------------------------------------------------------
define('DB_HOST', 'localhost');              // Usually 'localhost' on standard cPanel hosts
define('DB_NAME', 'kelvin_website_db');      // Replace with your actual cPanel database name
define('DB_USER', 'kelvin_db_user');         // Replace with your actual cPanel MySQL username
define('DB_PASS', 'YourSecurePasswordHere'); // Replace with your actual cPanel MySQL password
define('DB_CHARSET', 'utf8mb4');             // Standard UTF-8 character encoding

// ------------------------------------------------------------------------------
// SECTION 2: WEBSITE & ENVIRONMENT SETTINGS
// ------------------------------------------------------------------------------
// Automatically detect SITE_URL or specify explicitly if needed (e.g., 'https://yourdomain.com')
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || 
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('SITE_URL', $protocol . $host . ($dir === '' ? '' : $dir));

define('SITE_NAME', 'Kelvin Kibenje Kenedy Kyaluoko');
define('SITE_TAGLINE', 'Speaker | Author | Investor');
define('SITE_EMAIL', 'info@kelvinkibenje.com'); // Default notification email for Kelvin

// Set default timezone for Dar es Salaam, Tanzania
date_default_timezone_set('Africa/Dar_es_Salaam');

// ------------------------------------------------------------------------------
// SECTION 3: FILE UPLOAD DIRECTORIES
// ------------------------------------------------------------------------------
define('ROOT_DIR', __DIR__);
define('UPLOAD_DIR', ROOT_DIR . '/uploads');
define('UPLOAD_URL', SITE_URL . '/uploads');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB maximum upload file size
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// ------------------------------------------------------------------------------
// SECTION 4: SECURITY & SESSION CONFIGURATION
// ------------------------------------------------------------------------------
// Configure secure session handling
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    // Enable SameSite protection for cookies
    session_start();
}

// Generate CSRF token for forms if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Enable error reporting for development (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
