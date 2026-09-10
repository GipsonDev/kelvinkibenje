<?php
/**
 * ==============================================================================
 * CMS ADMIN BLOG MANAGEMENT (admin/blog.php)
 * List, filter, duplicate, edit & delete blog posts
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Manage Blog & Articles";

// Handle duplication action
if (isset($_GET['duplicate']) && verify_csrf($_GET['csrf_token'] ?? '')) {
    $id_to_dup = (int)$_GET['duplicate'];
    $post = db_fetch_one("SELECT * FROM posts WHERE id = ?", [$id_to_dup]);
    if ($post) {
        $new_title = $post['title'] . " (Copy)";
        $new_slug = $post['slug'] . "-copy-" . rand(100, 999);
        db_execute(
            "INSERT INTO posts (title, slug, excerpt, content, featured_image, category_id, status, published_at, seo_title, seo_description, views, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, 'draft', NOW(), ?, ?, 0, NOW())",
            [
                $new_title,
                $new_slug,
                $post['excerpt'],
                $post['content'],
                $post['featured_image'],
                $post['category_id'],
                $post['seo_title'],
                $post['seo_description']
            ]
        );
        header('Location: /admin/blog.php?msg=duplicated');
        exit;
    }
}

// Handle filtering
$status_filter = trim($_GET['status'] ?? '');
$category_filter = (int)($_GET['category'] ?? 0);

$sql = "SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];

if (!empty($status_filter)) {
    $sql .= " AND p.status = ?";
    $params[] = $status_filter;
}

if ($category_filter > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_filter;
}

$sql .= " ORDER BY p.published_at DESC";

$posts = db_fetch_all($sql, $params);
$categories = db_fetch_all("SELECT * FROM categories ORDER BY name ASC");

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="admin-page-title">Blog &amp; Articles CMS</h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Create, edit, and publish financial education articles.</p>
    </div>
    <a href="/admin/blog-edit.php" class="btn btn-gold">✍️ Write New Article</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php if ($_GET['msg'] === 'saved') echo "Article saved successfully!"; ?>
        <?php if ($_GET['msg'] === 'deleted') echo "Article deleted successfully."; ?>
        <?php if ($_GET['msg'] === 'duplicated') echo "Article duplicated as a Draft."; ?>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="admin-card" style="padding: 18px 24px; margin-bottom: 24px;">
    <form action="/admin/blog.php" method="GET" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
        <div>
            <label style="font-size: 12px; color: #94a3b8; display: block;">Status Filter:</label>
            <select name="status" class="admin-input" style="padding: 6px 12px; width: 150px;">
                <option value="">All Statuses</option>
                <option value="published" <?= $status_filter === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft" <?= $status_filter === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>

        <div>
            <label style="font-size: 12px; color: #94a3b8; display: block;">Category Filter:</label>
            <select name="category" class="admin-input" style="padding: 6px 12px; width: 180px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                        <?= h($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-top: 18px;">
            <button type="submit" class="btn btn-outline-gold" style="padding: 6px 16px;">Filter</button>
            <a href="/admin/blog.php" style="color: #94a3b8; font-size: 13px; margin-left: 10px;">Reset</a>
        </div>
    </form>
</div>

<!-- Articles Table -->
<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Featured Image</th>
                <th>Title &amp; Slug</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Publish Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td style="width: 70px;">
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?= h($post['featured_image']) ?>" alt="" style="width: 60px; height: 44px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 60px; height: 44px; background: #1c3268; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #94a3b8;">No Img</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong style="font-size: 15px; color: #fff;"><?= h($post['title']) ?></strong>
                            <div style="font-size: 12px; color: #94a3b8;">/post.php?slug=<?= h($post['slug']) ?></div>
                        </td>
                        <td><?= h($post['category_name'] ?: 'Uncategorized') ?></td>
                        <td>
                            <span class="status-badge status-<?= strtolower($post['status']) ?>">
                                <?= h($post['status']) ?>
                            </span>
                        </td>
                        <td><?= number_format($post['views']) ?></td>
                        <td><?= format_date($post['published_at'], 'M d, Y') ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/blog-edit.php?id=<?= (int)$post['id'] ?>" class="btn btn-outline-gold" style="padding: 4px 10px; font-size: 12px;" title="Edit">
                                    Edit
                                </a>
                                <a href="/admin/blog.php?duplicate=<?= (int)$post['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" class="btn btn-outline-gold" style="padding: 4px 10px; font-size: 12px; border-color: #cbd5e1; color: #cbd5e1;" title="Duplicate">
                                    Duplicate
                                </a>
                                <a href="/admin/blog-delete.php?id=<?= (int)$post['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" class="btn btn-outline-gold confirm-delete" style="padding: 4px 10px; font-size: 12px; border-color: #ef4444; color: #f87171;" title="Delete" data-confirm-message="Are you sure you want to permanently delete '<?= h($post['title']) ?>'?">
                                    Delete
                                </a>
                                <a href="/post.php?slug=<?= h($post['slug']) ?>" target="_blank" style="color: #d4af37; font-size: 14px; display: flex; align-items: center;" title="View Live">↗</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 32px; color: #94a3b8;">No blog articles found. Click "Write New Article" above to create one.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
