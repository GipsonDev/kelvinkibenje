<?php
/**
 * ==============================================================================
 * CMS BOOK SHOP MANAGEMENT (admin/books.php)
 * Manage self-published books, covers, pricing & availability
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$page_title = "Manage Bookshop";

$books = [];
try {
    $books = db_fetch_all("SELECT * FROM books ORDER BY order_index ASC, id ASC");
} catch (Exception $e) {
    // Fallback
}

include __DIR__ . '/includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="admin-page-title">Books &amp; Guides CMS</h1>
        <p class="admin-page-subtitle" style="margin-bottom: 0;">Manage book details, cover photos, and TSh pricing.</p>
    </div>
    <a href="/admin/book-edit.php" class="btn btn-gold">📚 Add New Book</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php if ($_GET['msg'] === 'saved') echo "Book saved successfully!"; ?>
        <?php if ($_GET['msg'] === 'deleted') echo "Book deleted successfully."; ?>
    </div>
<?php endif; ?>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title &amp; Subtitle</th>
                <th>Price (TSh)</th>
                <th>Availability</th>
                <th>Featured</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td style="width: 80px;">
                            <img src="<?= h($book['cover_image']) ?>" alt="" style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(255,255,255,0.15);">
                        </td>
                        <td>
                            <strong style="font-size: 16px; color: #fff; display: block; margin-bottom: 4px;"><?= h($book['title']) ?></strong>
                            <span style="font-size: 13px; color: #d4af37;"><?= h($book['subtitle']) ?></span>
                            <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">/book.php?slug=<?= h($book['slug']) ?></div>
                        </td>
                        <td>
                            <strong style="font-size: 16px; color: #fff;"><?= format_tsh($book['price_tsh']) ?></strong>
                        </td>
                        <td>
                            <?= $book['is_available'] ? '<span class="status-badge status-published">Available</span>' : '<span class="status-badge status-draft">Hidden</span>' ?>
                        </td>
                        <td>
                            <?= $book['is_featured'] ? '<span style="color: #d4af37; font-weight: bold;">⭐ Yes</span>' : '<span style="color: #94a3b8;">No</span>' ?>
                        </td>
                        <td><?= (int)$book['order_index'] ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="/admin/book-edit.php?id=<?= (int)$book['id'] ?>" class="btn btn-outline-gold" style="padding: 6px 12px; font-size: 12px;">
                                    Edit
                                </a>
                                <a href="/admin/book-delete.php?id=<?= (int)$book['id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" class="btn btn-outline-gold confirm-delete" style="padding: 6px 12px; font-size: 12px; border-color: #ef4444; color: #f87171;" data-confirm-message="Are you sure you want to delete '<?= h($book['title']) ?>'?">
                                    Delete
                                </a>
                                <a href="/book.php?slug=<?= h($book['slug']) ?>" target="_blank" style="color: #d4af37; font-size: 14px; display: flex; align-items: center;" title="View in Shop">↗</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 32px; color: #94a3b8;">No books found. Click "Add New Book" above.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
