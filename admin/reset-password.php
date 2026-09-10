<?php
/**
 * ==============================================================================
 * CMS ADMIN PASSWORD RESET HANDLER (admin/reset-password.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';

$token = trim($_GET['token'] ?? ($_POST['token'] ?? ''));
$success_msg = '';
$error_msg = '';
$valid_token = false;
$email = '';

if (!empty($token)) {
    try {
        $reset = db_fetch_one("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()", [$token]);
        if ($reset) {
            $valid_token = true;
            $email = $reset['email'];
        } else {
            $error_msg = "This password reset link is invalid or has expired.";
        }
    } catch (Exception $e) {
        $error_msg = "Database error.";
    }
} else {
    $error_msg = "No reset token provided.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch.";
    } else {
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (strlen($new_password) < 8) {
            $error_msg = "Password must be at least 8 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $error_msg = "Passwords do not match.";
        } else {
            try {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                db_execute("UPDATE admins SET password_hash = ? WHERE email = ?", [$hash, $email]);
                db_execute("DELETE FROM password_resets WHERE token = ?", [$token]);

                $success_msg = "Your password has been reset successfully! You can now log in.";
                $valid_token = false;
            } catch (Exception $e) {
                $error_msg = "Could not update password.";
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
    <title>Reset Password - Kelvin Kibenje CMS</title>
    <link rel="stylesheet" href="/assets/css/style.css?v=2026.1">
    <style>
        body { background-color: #050a17; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .login-box { background: #132247; border: 1px solid #d4af37; border-radius: 12px; padding: 40px; width: 100%; max-width: 440px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); }
    </style>
</head>
<body>
<div class="login-box">
    <h2 style="font-family: 'Georgia', serif; color: #fff; text-align: center; margin-top: 0;">Create New Password</h2>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success">
            <?= h($success_msg) ?><br><br>
            <a href="/admin/login.php" class="btn btn-gold btn-block">Go to Admin Login</a>
        </div>
    <?php elseif ($valid_token): ?>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger"><?= h($error_msg) ?></div>
        <?php endif; ?>

        <form action="/admin/reset-password.php" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= h($token) ?>">

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" required class="form-control" placeholder="At least 8 characters">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" required class="form-control" placeholder="Re-enter new password">
            </div>

            <button type="submit" class="btn btn-gold btn-block" style="margin-top: 16px;">Set New Password</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger"><?= h($error_msg) ?></div>
        <div style="text-align: center; margin-top: 16px;">
            <a href="/admin/forgot-password.php" style="color: #d4af37; font-size: 13px;">Request New Reset Link</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
