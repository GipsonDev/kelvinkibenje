<?php
/**
 * ==============================================================================
 * CMS BOOK EDITOR (admin/book-edit.php)
 * Add or edit book metadata, pricing, description & cover image upload
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$id = (int)($_GET['id'] ?? 0);
$book = [
    'id' => 0,
    'title' => '',
    'slug' => '',
    'subtitle' => '',
    'cover_image' => '',
    'short_description' => '',
    'long_description' => '',
    'price_tsh' => 20000,
    'is_available' => 1,
    'is_featured' => 1,
    'order_index' => 1
];

$page_title = "Add New Book";
if ($id > 0) {
    $existing = db_fetch_one("SELECT * FROM books WHERE id = ?", [$id]);
    if ($existing) {
        $book = $existing;
        $page_title = "Edit Book: " . $book['title'];
    }
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please try again.";
    } else {
        $book['title'] = trim($_POST['title'] ?? '');
        $book['slug'] = trim($_POST['slug'] ?? '');
        $book['subtitle'] = trim($_POST['subtitle'] ?? '');
        $book['short_description'] = trim($_POST['short_description'] ?? '');
        $book['long_description'] = trim($_POST['long_description'] ?? '');
        $book['price_tsh'] = (int)($_POST['price_tsh'] ?? 20000);
        $book['is_available'] = isset($_POST['is_available']) ? 1 : 0;
        $book['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
        $book['order_index'] = (int)($_POST['order_index'] ?? 1);

        if (empty($book['slug']) && !empty($book['title'])) {
            $book['slug'] = slugify($book['title']);
        }

        if (empty($book['title'])) {
            $error_msg = "Book title is required.";
        } else {
            // Handle cover image upload
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $uploaded_path = upload_file($_FILES['cover_image'], 'books');
                if ($uploaded_path) {
                    $book['cover_image'] = $uploaded_path;
                } else {
                    $error_msg = "Failed to upload cover image. Ensure JPG, PNG, or WEBP under 5MB.";
                }
            }

            if (empty($book['cover_image'])) {
                $book['cover_image'] = '/images/avatar.jpg';
            }

            if (empty($error_msg)) {
                try {
                    if ($id > 0) {
                        db_execute(
                            "UPDATE books SET title=?, slug=?, subtitle=?, cover_image=?, short_description=?, long_description=?, price_tsh=?, is_available=?, is_featured=?, order_index=? WHERE id=?",
                            [
                                $book['title'],
                                $book['slug'],
                                $book['subtitle'],
                                $book['cover_image'],
                                $book['short_description'],
                                $book['long_description'],
                                $book['price_tsh'],
                                $book['is_available'],
                                $book['is_featured'],
                                $book['order_index'],
                                $id
                            ]
                        );
                    } else {
                        db_execute(
                            "INSERT INTO books (title, slug, subtitle, cover_image, short_description, long_description, price_tsh, is_available, is_featured, order_index, created_at) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                            [
                                $book['title'],
                                $book['slug'],
                                $book['subtitle'],
                                $book['cover_image'],
                                $book['short_description'],
                                $book['long_description'],
                                $book['price_tsh'],
                                $book['is_available'],
                                $book['is_featured'],
                                $book['order_index']
                            ]
                        );
                    }
                    header('Location: /admin/books.php?msg=saved');
                    exit;
                } catch (Exception $e) {
                    $error_msg = "Could not save book. Please ensure the URL slug is unique.";
                }
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="admin-page-title"><?= h($page_title) ?></h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Add or edit book description, pricing in TSh, and cover photo.</p>
    </div>
    <a href="/admin/books.php" class="btn btn-outline-gold">← Back to Books</a>
</div>

<?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger"><?= h($error_msg) ?></div>
<?php endif; ?>

<form action="/admin/book-edit.php<?= $id > 0 ? '?id=' . $id : '' ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
        <div class="admin-card">
            <div class="admin-form-group">
                <label class="admin-label">Book Title *</label>
                <input type="text" name="title" value="<?= h($book['title']) ?>" required class="admin-input" placeholder="e.g. China to Tanzania">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">URL Slug</label>
                <input type="text" name="slug" value="<?= h($book['slug']) ?>" class="admin-input" placeholder="china-to-tanzania">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Subtitle / Catchphrase</label>
                <input type="text" name="subtitle" value="<?= h($book['subtitle']) ?>" class="admin-input" placeholder="e.g. Sourcing Products, Safe Payments, and Maximizing Profit">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Price in Tanzanian Shillings (TSh) *</label>
                <input type="number" name="price_tsh" value="<?= (int)$book['price_tsh'] ?>" required min="0" step="1000" class="admin-input" style="max-width: 250px;">
                <small style="color: #94a3b8; font-size: 12px;">e.g. 30000 for TSh 30,000</small>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Short Description (Summary for Shop Grid)</label>
                <textarea name="short_description" rows="3" class="admin-input"><?= h($book['short_description']) ?></textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Full Long Description &amp; Table of Contents (HTML Supported)</label>
                <textarea name="long_description" rows="10" class="admin-input"><?= h($book['long_description']) ?></textarea>
            </div>
        </div>

        <div>
            <!-- Cover Photo Card -->
            <div class="admin-card">
                <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                    Cover Image
                </h3>

                <?php if (!empty($book['cover_image'])): ?>
                    <div style="margin-bottom: 14px; text-align: center;">
                        <img src="<?= h($book['cover_image']) ?>" alt="" style="max-height: 260px; margin: 0 auto; border-radius: 4px; border: 1px solid rgba(255,255,255,0.15);">
                    </div>
                <?php endif; ?>

                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label class="admin-label">Upload New Cover Photo</label>
                    <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp" class="admin-input" style="padding: 8px;">
                </div>
            </div>

            <!-- Display Options Card -->
            <div class="admin-card">
                <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                    Shop Settings
                </h3>

                <div class="admin-form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #fff;">
                        <input type="checkbox" name="is_available" value="1" <?= $book['is_available'] ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                        <span>Available in Shop (In Stock)</span>
                    </label>
                </div>

                <div class="admin-form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #fff;">
                        <input type="checkbox" name="is_featured" value="1" <?= $book['is_featured'] ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                        <span>Featured Book (Show Bestseller badge)</span>
                    </label>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Display Order Index</label>
                    <input type="number" name="order_index" value="<?= (int)$book['order_index'] ?>" class="admin-input" style="max-width: 120px;">
                </div>

                <button type="submit" class="btn btn-gold btn-block" style="padding: 14px; font-size: 16px;">
                    <?= $id > 0 ? 'Update Book' : 'Save Book' ?>
                </button>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
