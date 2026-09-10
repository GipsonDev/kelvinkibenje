<?php
/**
 * ==============================================================================
 * FORGOT PASSWORD RESET PAGE (admin/forgot-password.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please try again.";
    } else {
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $error_msg = "Please enter your admin email address.";
        } else {
            try {
                $user = db_fetch_one("SELECT * FROM admins WHERE email = ?", [$email]);
                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', time() + 3600);

                    db_execute("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)", [$email, $token, $expires]);

                    $reset_link = SITE_URL . "/admin/reset-password.php?token=" . $token;
                    $subject = "Password Reset - Kelvin Kibenje CMS";
                    $html_message = "<p>Hello " . h($user['name']) . ",</p>"
                                  . "<p>You requested a password reset for your CMS Admin account. Click below to reset your password:</p>"
                                  . "<p><a href='" . h($reset_link) . "' style='display:inline-block;padding:10px 20px;background:#d4af37;color:#0b1329;text-decoration:none;border-radius:4px;font-weight:bold;'>Reset Admin Password</a></p>"
                                  . "<p>This link expires in 1 hour.</p>";
                    send_site_email($email, $subject, $html_message);

                    $success_msg = "If an account matches that email, a password reset link has been sent.";
                } else {
                    // Show same message to prevent email enumeration
                    $success_msg = "If an account matches that email, a password reset link has been sent.";
                }
            } catch (Exception $e) {
                $error_msg = "Could not process request at this time.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Kelvin Kibenje CMS</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=2026.1">
    <style>
        body { background-color: #050a17; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .login-box { background: #132247; border: 1px solid #d4af37; border-radius: 12px; padding: 40px; width: 100%; max-width: 440px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); }
    </style>
</head>
<body>
<div class="login-box">
    <h2 style="font-family: 'Georgia', serif; color: #fff; text-align: center; margin-top: 0;">Reset Password</h2>
    <p style="font-size: 13px; color: #94a3b8; text-align: center; margin-bottom: 24px;">Enter your admin email address to receive a reset link.</p>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?= h($success_msg) ?></div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?= h($error_msg) ?></div>
    <?php endif; ?>

    <form action="/admin/forgot-password.php" method="POST">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label">Admin Email Address</label>
            <input type="email" name="email" required class="form-control" placeholder="e.g. kelvinkibenje@gmail.com">
        </div>
        <button type="submit" class="btn btn-gold btn-block" style="margin-top: 16px;">Send Reset Link</button>
    </form>
    <div style="text-align: center; margin-top: 20px;">
        <a href="/admin/login.php" style="color: #cbd5e1; font-size: 13px;">← Back to Admin Login</a>
    </div>
</div>
</body>
</html>
