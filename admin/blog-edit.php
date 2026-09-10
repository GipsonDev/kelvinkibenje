<?php
/**
 * ==============================================================================
 * CMS BLOG POST EDITOR (admin/blog-edit.php)
 * Quill.js WYSIWYG editor, image uploader, auto-slug & SEO metadata
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$id = (int)($_GET['id'] ?? 0);
$post = [
    'id' => 0,
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'featured_image' => '',
    'category_id' => 1,
    'status' => 'published',
    'published_at' => date('Y-m-d H:i:s'),
    'seo_title' => '',
    'seo_description' => ''
];

$page_title = "Write New Article";
if ($id > 0) {
    $existing = db_fetch_one("SELECT * FROM posts WHERE id = ?", [$id]);
    if ($existing) {
        $post = $existing;
        $page_title = "Edit Article: " . $post['title'];
    }
}

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error_msg = "Security token mismatch. Please try again.";
    } else {
        $post['title'] = trim($_POST['title'] ?? '');
        $post['slug'] = trim($_POST['slug'] ?? '');
        $post['excerpt'] = trim($_POST['excerpt'] ?? '');
        $post['content'] = $_POST['content'] ?? '';
        $post['category_id'] = (int)($_POST['category_id'] ?? 1);
        $post['status'] = $_POST['status'] === 'draft' ? 'draft' : 'published';
        $post['published_at'] = trim($_POST['published_at'] ?? date('Y-m-d H:i:s'));
        $post['seo_title'] = trim($_POST['seo_title'] ?? '');
        $post['seo_description'] = trim($_POST['seo_description'] ?? '');

        if (empty($post['slug']) && !empty($post['title'])) {
            $post['slug'] = slugify($post['title']);
        }

        if (empty($post['title'])) {
            $error_msg = "Article title is required.";
        } else {
            // Handle image upload if provided
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $uploaded_path = upload_file($_FILES['featured_image'], 'blog');
                if ($uploaded_path) {
                    $post['featured_image'] = $uploaded_path;
                } else {
                    $error_msg = "Failed to upload image. Please ensure it is a JPG, PNG, or WEBP under 5MB.";
                }
            }

            if (empty($error_msg)) {
                try {
                    if ($id > 0) {
                        db_execute(
                            "UPDATE posts SET title=?, slug=?, excerpt=?, content=?, featured_image=?, category_id=?, status=?, published_at=?, seo_title=?, seo_description=? WHERE id=?",
                            [
                                $post['title'],
                                $post['slug'],
                                $post['excerpt'],
                                $post['content'],
                                $post['featured_image'],
                                $post['category_id'],
                                $post['status'],
                                $post['published_at'],
                                $post['seo_title'],
                                $post['seo_description'],
                                $id
                            ]
                        );
                    } else {
                        db_execute(
                            "INSERT INTO posts (title, slug, excerpt, content, featured_image, category_id, status, published_at, seo_title, seo_description, views, created_at) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW())",
                            [
                                $post['title'],
                                $post['slug'],
                                $post['excerpt'],
                                $post['content'],
                                $post['featured_image'],
                                $post['category_id'],
                                $post['status'],
                                $post['published_at'],
                                $post['seo_title'],
                                $post['seo_description']
                            ]
                        );
                    }
                    header('Location: /admin/blog.php?msg=saved');
                    exit;
                } catch (Exception $e) {
                    $error_msg = "Could not save article. Please check if the slug '" . h($post['slug']) . "' is already in use.";
                }
            }
        }
    }
}

$categories = db_fetch_all("SELECT * FROM categories ORDER BY name ASC");

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="admin-page-title"><?= h($page_title) ?></h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Use the rich WYSIWYG editor below to craft your article.</p>
    </div>
    <a href="/admin/blog.php" class="btn btn-outline-gold">← Back to Blog List</a>
</div>

<?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger"><?= h($error_msg) ?></div>
<?php endif; ?>

<form action="/admin/blog-edit.php<?= $id > 0 ? '?id=' . $id : '' ?>" method="POST" enctype="multipart/form-data" id="postForm">
    <?= csrf_field() ?>

    <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 32px; align-items: start;">
        <!-- Left Main Form Area -->
        <div class="admin-card">
            <div class="admin-form-group">
                <label class="admin-label">Article Title *</label>
                <input type="text" name="title" id="post_title" value="<?= h($post['title']) ?>" required class="admin-input" placeholder="e.g. 5 Rules for Safe China Importing" autofocus>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">URL Slug (Auto-generated from title, editable)</label>
                <input type="text" name="slug" id="post_slug" value="<?= h($post['slug']) ?>" class="admin-input" placeholder="5-rules-for-safe-china-importing" data-auto-slug="<?= $id === 0 ? 'true' : 'false' ?>">
                <small style="color: #94a3b8; font-size: 12px;">This forms the URL: /post.php?slug=your-slug</small>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Short Excerpt (Summary for Cards &amp; Search)</label>
                <textarea name="excerpt" rows="3" class="admin-input" placeholder="Write a 2-3 sentence summary that encourages visitors to read more..."><?= h($post['excerpt']) ?></textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Full Article Content (WYSIWYG Editor) *</label>
                <!-- Hidden textarea to receive Quill content on submit -->
                <textarea name="content" id="post_content" style="display: none;"><?= h($post['content']) ?></textarea>
                <!-- Quill container -->
                <div id="quill-editor-container"></div>
            </div>
        </div>

        <!-- Right Publishing Sidebar -->
        <div>
            <!-- Publish Settings Card -->
            <div class="admin-card">
                <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                    Publishing
                </h3>

                <div class="admin-form-group">
                    <label class="admin-label">Status</label>
                    <select name="status" class="admin-input">
                        <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Category</label>
                    <select name="category_id" class="admin-input">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= h($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Publish Date &amp; Time</label>
                    <input type="text" name="published_at" value="<?= h($post['published_at']) ?>" class="admin-input" placeholder="YYYY-MM-DD HH:MM:SS">
                </div>

                <button type="submit" class="btn btn-gold btn-block" style="padding: 14px; font-size: 16px;">
                    <?= $id > 0 ? 'Update Article' : 'Publish Article' ?>
                </button>
            </div>

            <!-- Featured Image Upload Card -->
            <div class="admin-card">
                <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                    Featured Image
                </h3>

                <?php if (!empty($post['featured_image'])): ?>
                    <div style="margin-bottom: 12px;">
                        <img src="<?= h($post['featured_image']) ?>" alt="" style="width: 100%; border-radius: 6px; border: 1px solid rgba(255,255,255,0.15);">
                    </div>
                <?php endif; ?>

                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label class="admin-label">Upload New Image (JPG, PNG, WEBP)</label>
                    <input type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp" class="admin-input" style="padding: 8px;">
                    <small style="color: #94a3b8; font-size: 12px; display: block; margin-top: 6px;">Recommended width: 800px - 1200px</small>
                </div>
            </div>

            <!-- SEO Metadata Card -->
            <div class="admin-card">
                <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                    SEO Metadata
                </h3>

                <div class="admin-form-group">
                    <label class="admin-label">SEO Meta Title</label>
                    <input type="text" name="seo_title" value="<?= h($post['seo_title']) ?>" class="admin-input" placeholder="Leave blank to use Article Title">
                </div>

                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label class="admin-label">SEO Meta Description</label>
                    <textarea name="seo_description" rows="3" class="admin-input" placeholder="Summary for Google search results..."><?= h($post['seo_description']) ?></textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
