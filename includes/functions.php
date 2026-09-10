<?php
/**
 * ==============================================================================
 * CORE HELPER & SECURITY FUNCTIONS (functions.php)
 * Includes XSS protection, CSRF validation, formatting, file upload, email & settings
 * ==============================================================================
 */

require_once __DIR__ . '/db.php';

/**
 * Escapes special characters for safe HTML output (prevents XSS attacks).
 *
 * @param string|null $string
 * @return string
 */
function h(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Generates an HTML hidden input field containing the CSRF token.
 *
 * @return string
 */
function csrf_field(): string {
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . h($token) . '">';
}

/**
 * Verifies a submitted CSRF token against the user session token.
 *
 * @param string|null $token
 * @return bool
 */
function verify_csrf(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Formats an amount into Tanzanian Shillings (e.g., 30000 -> TSh 30,000).
 *
 * @param int|float|string $amount
 * @return string
 */
function format_tsh($amount): string {
    return 'TSh ' . number_format((float)$amount, 0, '.', ',');
}

/**
 * Formats a MySQL datetime or date string into readable English date.
 *
 * @param string|null $date_str
 * @param string $format
 * @return string
 */
function format_date(?string $date_str, string $format = 'M d, Y'): string {
    if (empty($date_str)) {
        return '';
    }
    $timestamp = strtotime($date_str);
    return $timestamp ? date($format, $timestamp) : '';
}

/**
 * Converts a string into a URL-friendly slug.
 *
 * @param string $text
 * @return string
 */
function slugify(string $text): string {
    // Replace non letter or digits by hyphen
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim and lowercase
    $text = trim($text, '-');
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Truncates text cleanly to a maximum number of characters without breaking words.
 *
 * @param string $text
 * @param int $limit
 * @return string
 */
function truncate_text(string $text, int $limit = 140): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    $truncated = mb_substr($text, 0, $limit);
    $last_space = mb_strrpos($truncated, ' ');
    if ($last_space !== false) {
        $truncated = mb_substr($truncated, 0, $last_space);
    }
    return $truncated . '...';
}

/**
 * Retrieves a setting value from the database, with static caching per request.
 *
 * @param string $key
 * @param string $default
 * @return string
 */
function get_setting(string $key, string $default = ''): string {
    static $settings_cache = null;

    if ($settings_cache === null) {
        $settings_cache = [];
        try {
            $rows = db_fetch_all("SELECT setting_key, setting_value FROM settings");
            foreach ($rows as $row) {
                $settings_cache[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // If table doesn't exist yet, return default gracefully
        }
    }

    return $settings_cache[$key] ?? $default;
}

/**
 * Saves or updates a setting value in the database.
 *
 * @param string $key
 * @param string $value
 * @return bool
 */
function set_setting(string $key, string $value): bool {
    $sql = "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
    return db_execute($sql, [$key, $value]);
}

/**
 * Handles secure file uploads to an upload subfolder.
 *
 * @param array $file_input  The $_FILES['input_name'] array
 * @param string $subfolder  e.g., 'blog' or 'books'
 * @return string|false      Relative URL path to the uploaded file, or false on failure
 */
function upload_file(array $file_input, string $subfolder = 'general') {
    if (!isset($file_input['error']) || $file_input['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Check file size
    if ($file_input['size'] > MAX_UPLOAD_SIZE) {
        return false;
    }

    // Verify file extension
    $original_name = $file_input['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS, true)) {
        return false;
    }

    // Check MIME type via getimagesize for image security
    $img_info = @getimagesize($file_input['tmp_name']);
    if ($img_info === false) {
        return false;
    }

    // Ensure target folder exists
    $target_dir = UPLOAD_DIR . '/' . trim($subfolder, '/');
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Generate unique safe filename
    $unique_name = uniqid($subfolder . '_', true) . '.' . $extension;
    $target_file = $target_dir . '/' . $unique_name;

    if (move_uploaded_file($file_input['tmp_name'], $target_file)) {
        return '/uploads/' . trim($subfolder, '/') . '/' . $unique_name;
    }

    return false;
}

/**
 * Sends a clean HTML email notification to Kelvin or customers.
 * Uses PHP mail() with proper MIME headers, compatible with cPanel shared hosting.
 *
 * @param string $to
 * @param string $subject
 * @param string $html_message
 * @param string|null $reply_to
 * @param string|null $reply_to_name
 * @return bool
 */
function send_site_email(string $to, string $subject, string $html_message, ?string $reply_to = null, ?string $reply_to_name = null): bool {
    $site_name = get_setting('site_name', 'Kelvin Kibenje Website');
    $from_email = get_setting('contact_email', 'noreply@kelvinkibenje.com');

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: =?UTF-8?B?" . base64_encode($site_name) . "?= <" . $from_email . ">" . "\r\n";

    if ($reply_to) {
        $r_name = $reply_to_name ?: $reply_to;
        $headers .= "Reply-To: =?UTF-8?B?" . base64_encode($r_name) . "?= <" . $reply_to . ">" . "\r\n";
    }

    $headers .= "X-Mailer: PHP/" . phpversion();

    $email_body = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #1e293b; padding: 20px; }
            .card { background: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 8px; border-top: 4px solid #d4af37; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
            .header { border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; margin-bottom: 20px; }
            .header h2 { color: #0b1329; margin: 0; }
            .footer { margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; }
        </style>
    </head>
    <body>
        <div class="card">
            <div class="header">
                <h2>' . h($site_name) . ' Notification</h2>
            </div>
            <div class="content">
                ' . $html_message . '
            </div>
            <div class="footer">
                <p>This email was sent automatically from your official website at ' . h(SITE_URL) . '.</p>
            </div>
        </div>
    </body>
    </html>';

    return @mail($to, $subject, $email_body, $headers);
}

/**
 * Checks whether an admin user is currently authenticated in the session.
 *
 * @return bool
 */
function is_admin_logged_in(): bool {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Requires admin authentication. Redirects to /admin/login.php if not logged in.
 */
function require_admin_login(): void {
    if (!is_admin_logged_in()) {
        header('Location: /admin/login.php');
        exit;
    }
}

/**
 * Returns the currently logged in admin user array from session/database.
 *
 * @return array|null
 */
function get_logged_in_admin(): ?array {
    if (!is_admin_logged_in()) {
        return null;
    }
    return db_fetch_one("SELECT id, username, email, name, role, last_login FROM admins WHERE id = ?", [$_SESSION['admin_id']]);
}
