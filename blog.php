<?php
/**
 * ==============================================================================
 * BLOG & ARTICLES PAGE (blog.php)
 * Category filtering, search & dynamically loaded articles
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$page_title = "Financial Education Articles | Kelvin Kibenje";
$page_description = "Read Kelvin Kibenje's latest articles on China-to-Tanzania importing, budgeting, avoiding bad debt, and safe investment compounding.";

$selected_category = trim($_GET['category'] ?? '');
$search_query = trim($_GET['q'] ?? '');

$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM posts p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'published'";
$params = [];

if (!empty($selected_category)) {
    $sql .= " AND c.slug = ?";
    $params[] = $selected_category;
}

if (!empty($search_query)) {
    $sql .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
    $like = '%' . $search_query . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= " ORDER BY p.published_at DESC";

$posts = [];
$categories = [];
try {
    $posts = db_fetch_all($sql, $params);
    $categories = db_fetch_all("SELECT * FROM categories ORDER BY name ASC");
} catch (Exception $e) {
    // Fallback
}

include __DIR__ . '/includes/header.php';
?>

<!-- BLOG HERO -->
<section class="section section-alt" style="padding: 60px 0 50px;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 30px;">
            <div class="section-subtitle">Financial Articles &amp; Insights</div>
            <h1 class="section-title">The Classroom of Action</h1>
            <p class="section-desc">
                Actionable advice on money management, East African trade, and compounding investments.
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; max-width: 900px; margin: 0 auto; background: var(--navy-950); padding: 16px 24px; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.1);">
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="/blog.php" class="btn <?= empty($selected_category) ? 'btn-gold' : 'btn-outline-gold' ?>" style="padding: 6px 16px; font-size: 13px;">All Articles</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/blog.php?category=<?= h($cat['slug']) ?>" 
                       class="btn <?= $selected_category === $cat['slug'] ? 'btn-gold' : 'btn-outline-gold' ?>" 
                       style="padding: 6px 16px; font-size: 13px;">
                        <?= h($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <form action="/blog.php" method="GET" style="display: flex; gap: 8px;">
                <input type="text" name="q" value="<?= h($search_query) ?>" placeholder="Search articles..." class="form-control" style="padding: 6px 12px; width: 180px; font-size: 13px;">
                <button type="submit" class="btn btn-outline-gold" style="padding: 6px 14px; font-size: 13px;">Search</button>
            </form>
        </div>
    </div>
</section>

<!-- BLOG LISTING -->
<section class="section">
    <div class="container">
        <?php if (!empty($search_query)): ?>
            <p style="margin-bottom: 24px; color: var(--slate-300);">
                Showing search results for: <strong>"<?= h($search_query) ?>"</strong> 
                (<a href="/blog.php" style="color: var(--gold-400);">clear search</a>)
            </p>
        <?php endif; ?>

        <div class="blog-grid">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <div class="post-img-wrapper">
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?= h($post['featured_image']) ?>" alt="<?= h($post['title']) ?>" class="post-img">
                            <?php else: ?>
                                <img src="/images/event-seminar.jpg" alt="<?= h($post['title']) ?>" class="post-img">
                            <?php endif; ?>
                            <?php if (!empty($post['category_name'])): ?>
                                <a href="/blog.php?category=<?= h($post['category_slug']) ?>" class="post-category-tag">
                                    <?= h($post['category_name']) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="post-content">
                            <div class="post-meta">
                                <span><?= format_date($post['published_at']) ?></span>
                                <span> • <?= (int)$post['views'] ?> views</span>
                            </div>
                            <h2 class="post-title" style="font-size: 20px;">
                                <a href="/post.php?slug=<?= h($post['slug']) ?>"><?= h($post['title']) ?></a>
                            </h2>
                            <p class="post-excerpt"><?= h(truncate_text($post['excerpt'], 140)) ?></p>
                            <a href="/post.php?slug=<?= h($post['slug']) ?>" class="read-more-link">
                                Read Full Article →
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; grid-column: 1 / -1; padding: 60px 20px;">
                    <h3 style="color: var(--white); margin-bottom: 8px;">No Articles Found</h3>
                    <p style="color: var(--slate-400);">We couldn't find any articles matching your filter. Check back soon!</p>
                    <a href="/blog.php" class="btn btn-gold" style="margin-top: 16px;">View All Articles</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
