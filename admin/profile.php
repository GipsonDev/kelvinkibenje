<?php
/**
 * ==============================================================================
 * CMS ADMIN PROFILE & PASSWORD MANAGER (admin/profile.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Admin Profile & Password";
$logged_admin = get_logged_in_admin();

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please try again.";
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email)) {
            $error_msg = "Name and Email are required.";
        } else {
            try {
                // Check if email already used by another admin
                $check = db_fetch_one("SELECT id FROM admins WHERE email = ? AND id != ?", [$email, $logged_admin['id']]);
                if ($check) {
                    $error_msg = "That email address is already used by another admin account.";
                } else {
                    if (!empty($new_password)) {
                        if ($new_password !== $confirm_password) {
                            $error_msg = "New password and confirmation do not match.";
                        } elseif (strlen($new_password) < 8) {
                            $error_msg = "Password must be at least 8 characters long.";
                        } else {
                            $hash = password_hash($new_password, PASSWORD_DEFAULT);
                            db_execute(
                                "UPDATE admins SET name = ?, email = ?, password_hash = ? WHERE id = ?",
                                [$name, $email, $hash, $logged_admin['id']]
                            );
                            $success_msg = "Profile and password updated successfully!";
                        }
                    } else {
                        db_execute(
                            "UPDATE admins SET name = ?, email = ? WHERE id = ?",
                            [$name, $email, $logged_admin['id']]
                        );
                        $success_msg = "Profile details updated successfully!";
                    }

                    if (empty($error_msg)) {
                        $logged_admin = get_logged_in_admin();
                    }
                }
            } catch (Exception $e) {
                $error_msg = "Could not update profile at this time.";
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<h1 class="admin-page-title">Admin Account Profile</h1>
<p class="admin-page-subtitle">Manage your login credentials and security.</p>

<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success"><?= h($success_msg) ?></div>
<?php endif; ?>

<?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger"><?= h($error_msg) ?></div>
<?php endif; ?>

<div class="admin-card" style="max-width: 600px;">
    <form action="/admin/profile.php" method="POST">
        <?= csrf_field() ?>

        <div class="admin-form-group">
            <label class="admin-label">Username (Login ID)</label>
            <input type="text" value="<?= h($logged_admin['username']) ?>" disabled class="admin-input" style="opacity: 0.6; cursor: not-allowed;">
            <small style="color:#94a3b8; font-size:12px;">Usernames cannot be changed.</small>
        </div>

        <div class="admin-form-group">
            <label class="admin-label">Role</label>
            <input type="text" value="<?= h(strtoupper($logged_admin['role'])) ?>" disabled class="admin-input" style="opacity: 0.6; cursor: not-allowed;">
        </div>

        <div class="admin-form-group">
            <label class="admin-label">Your Full Name *</label>
            <input type="text" name="name" value="<?= h($logged_admin['name']) ?>" required class="admin-input">
        </div>

        <div class="admin-form-group">
            <label class="admin-label">Your Email Address *</label>
            <input type="email" name="email" value="<?= h($logged_admin['email']) ?>" required class="admin-input">
        </div>

        <hr style="border:none; border-top:1px solid rgba(255,255,255,0.1); margin:24px 0;">

        <h3 style="margin-top:0; color:#fff; font-size:18px; margin-bottom:16px;">
            Change Password (Optional)
        </h3>

        <div class="admin-form-group">
            <label class="admin-label">New Password</label>
            <input type="password" name="new_password" class="admin-input" placeholder="Leave blank to keep current password">
        </div>

        <div class="admin-form-group">
            <label class="admin-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="admin-input" placeholder="Re-enter new password">
        </div>

        <button type="submit" class="btn btn-gold" style="padding:12px 28px;">
            Update Account Profile
        </button>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
