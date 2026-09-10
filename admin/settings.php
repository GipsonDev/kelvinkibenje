<?php
/**
 * ==============================================================================
 * CMS SITE SETTINGS MANAGER (admin/settings.php)
 * Edit tagline, contact details, mobile money payment numbers & social links
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Site Settings";
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please try again.";
    } else {
        $fields = [
            'site_name',
            'site_tagline',
            'site_about_summary',
            'contact_email',
            'contact_phone',
            'whatsapp_number',
            'instagram_url',
            'instagram_followers',
            'youtube_url',
            'facebook_url',
            'threads_url',
            'mpesa_name',
            'mpesa_number',
            'tigopesa_name',
            'tigopesa_number',
            'office_location',
            'footer_copyright'
        ];

        try {
            foreach ($fields as $key) {
                if (isset($_POST[$key])) {
                    set_setting($key, trim($_POST[$key]));
                }
            }
            $success_msg = "Site settings have been updated successfully!";
        } catch (Exception $e) {
            $error_msg = "Could not update settings at this time.";
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<h1 class="admin-page-title">Website Settings</h1>
<p class="admin-page-subtitle">Update Kelvin Kibenje's tagline, payment details, contact info, and social links without touching code.</p>

<?php if (!empty($success_msg)): ?>
    <div class="alert alert-success"><?= h($success_msg) ?></div>
<?php endif; ?>

<?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger"><?= h($error_msg) ?></div>
<?php endif; ?>

<form action="/admin/settings.php" method="POST">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: start;">
        <!-- Left Column: Branding & Contact -->
        <div>
            <!-- Brand Info Card -->
            <div class="admin-card">
                <h3 style="margin-top:0; color:#fff; font-size:18px; margin-bottom:16px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px;">
                    Branding &amp; Tagline
                </h3>

                <div class="admin-form-group">
                    <label class="admin-label">Site Name / Author Name</label>
                    <input type="text" name="site_name" value="<?= h(get_setting('site_name', 'Kelvin Kibenje Kenedy Kyaluoko')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Header Tagline</label>
                    <input type="text" name="site_tagline" value="<?= h(get_setting('site_tagline')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-label">About Summary (SEO Description &amp; Bio intro)</label>
                    <textarea name="site_about_summary" rows="4" class="admin-input"><?= h(get_setting('site_about_summary')) ?></textarea>
                </div>
            </div>

            <!-- Contact & Office Card -->
            <div class="admin-card">
                <h3 style="margin-top:0; color:#fff; font-size:18px; margin-bottom:16px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px;">
                    Contact Details
                </h3>

                <div class="admin-form-group">
                    <label class="admin-label">Official Email Address</label>
                    <input type="email" name="contact_email" value="<?= h(get_setting('contact_email', 'info@kelvinkibenje.com')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Phone Display (e.g. +255 677 853 595)</label>
                    <input type="text" name="contact_phone" value="<?= h(get_setting('contact_phone')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">WhatsApp Number (Digits only, e.g. 255677853595)</label>
                    <input type="text" name="whatsapp_number" value="<?= h(get_setting('whatsapp_number')) ?>" class="admin-input">
                    <small style="color:#94a3b8; font-size:12px;">Used for instant book ordering and message links.</small>
                </div>

                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-label">Office Location Address</label>
                    <input type="text" name="office_location" value="<?= h(get_setting('office_location')) ?>" class="admin-input">
                </div>
            </div>
        </div>

        <!-- Right Column: Payment & Social Media -->
        <div>
            <!-- Mobile Money Payment Details -->
            <div class="admin-card">
                <h3 style="margin-top:0; color:#fff; font-size:18px; margin-bottom:16px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px;">
                    📱 Mobile Money Payment Numbers
                </h3>
                <p style="font-size:13px; color:#94a3b8; margin-bottom:16px;">
                    Displayed on book checkout modals and order confirmation pages.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="admin-form-group">
                        <label class="admin-label">M-Pesa Number</label>
                        <input type="text" name="mpesa_number" value="<?= h(get_setting('mpesa_number')) ?>" class="admin-input">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">M-Pesa Account Name</label>
                        <input type="text" name="mpesa_name" value="<?= h(get_setting('mpesa_name')) ?>" class="admin-input">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="admin-form-group" style="margin-bottom:0;">
                        <label class="admin-label">Tigo Pesa Number</label>
                        <input type="text" name="tigopesa_number" value="<?= h(get_setting('tigopesa_number')) ?>" class="admin-input">
                    </div>
                    <div class="admin-form-group" style="margin-bottom:0;">
                        <label class="admin-label">Tigo Pesa Account Name</label>
                        <input type="text" name="tigopesa_name" value="<?= h(get_setting('tigopesa_name')) ?>" class="admin-input">
                    </div>
                </div>
            </div>

            <!-- Social Media Links Card -->
            <div class="admin-card">
                <h3 style="margin-top:0; color:#fff; font-size:18px; margin-bottom:16px; border-bottom:1px solid rgba(255,255,255,0.1); padding-bottom:10px;">
                    Social Media Profiles
                </h3>

                <div class="admin-form-group">
                    <label class="admin-label">Instagram URL (@kelvinkibenje)</label>
                    <input type="text" name="instagram_url" value="<?= h(get_setting('instagram_url')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Instagram Followers Text (e.g. 499K+)</label>
                    <input type="text" name="instagram_followers" value="<?= h(get_setting('instagram_followers')) ?>" class="admin-input" style="max-width: 150px;">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">YouTube URL</label>
                    <input type="text" name="youtube_url" value="<?= h(get_setting('youtube_url')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Facebook URL</label>
                    <input type="text" name="facebook_url" value="<?= h(get_setting('facebook_url')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Threads URL</label>
                    <input type="text" name="threads_url" value="<?= h(get_setting('threads_url')) ?>" class="admin-input">
                </div>

                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-label">Footer Copyright Notice</label>
                    <input type="text" name="footer_copyright" value="<?= h(get_setting('footer_copyright')) ?>" class="admin-input">
                </div>
            </div>

            <button type="submit" class="btn btn-gold btn-block" style="padding: 14px; font-size: 16px;">
                Save All Settings
            </button>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
