<?php
/**
 * ==============================================================================
 * SINGLE BLOG POST PAGE (post.php)
 * Full article reader, author box & related articles
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
    header('Location: /blog.php');
    exit;
}

$post = false;
$related_posts = [];
try {
    $post = db_fetch_one(
        "SELECT p.*, c.name as category_name, c.slug as category_slug 
         FROM posts p 
         LEFT JOIN categories c ON p.category_id = c.id 
         WHERE p.slug = ? AND p.status = 'published'",
        [$slug]
    );

    if ($post) {
        // Increment view count
        db_execute("UPDATE posts SET views = views + 1 WHERE id = ?", [$post['id']]);

        // Fetch related articles from same category
        $related_posts = db_fetch_all(
            "SELECT * FROM posts WHERE category_id = ? AND id != ? AND status = 'published' LIMIT 2",
            [$post['category_id'], $post['id']]
        );
    }
} catch (Exception $e) {
    // Fallback
}

if (!$post) {
    header('Location: /blog.php');
    exit;
}

$page_title = h($post['seo_title'] ?: $post['title']) . " | Kelvin Kibenje";
$page_description = h($post['seo_description'] ?: $post['excerpt']);
$page_image = !empty($post['featured_image']) ? SITE_URL . $post['featured_image'] : SITE_URL . '/images/hero.jpg';

include __DIR__ . '/includes/header.php';
?>

<!-- ARTICLE BREADCRUMB -->
<section class="top-bar" style="background: var(--navy-900); padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.08);">
    <div class="container">
        <a href="/blog.php" style="font-size: 14px; color: var(--slate-400);">← Back to Financial Articles</a>
    </div>
</section>

<!-- ARTICLE BODY -->
<section class="section" style="padding: 40px 0 60px;">
    <div class="container" style="max-width: 820px;">
        <!-- Article Header -->
        <header style="margin-bottom: 32px; text-align: center;">
            <?php if (!empty($post['category_name'])): ?>
                <a href="/blog.php?category=<?= h($post['category_slug']) ?>" class="hero-badge" style="margin-bottom: 16px;">
                    <?= h($post['category_name']) ?>
                </a>
            <?php endif; ?>

            <h1 style="font-family: var(--font-heading); font-size: 38px; color: var(--white); line-height: 1.25; margin-bottom: 16px;">
                <?= h($post['title']) ?>
            </h1>

            <div style="font-size: 14px; color: var(--slate-400); display: flex; justify-content: center; gap: 16px; align-items: center;">
                <span>By <strong>Kelvin Kibenje Kenedy Kyaluoko</strong></span>
                <span>•</span>
                <span>Published on <?= format_date($post['published_at']) ?></span>
                <span>•</span>
                <span><?= (int)$post['views'] ?> views</span>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if (!empty($post['featured_image'])): ?>
            <div style="margin-bottom: 36px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid rgba(255,255,255,0.15);">
                <img src="<?= h($post['featured_image']) ?>" alt="<?= h($post['title']) ?>" style="width: 100%; max-height: 480px; object-fit: cover;">
            </div>
        <?php endif; ?>

        <!-- Content Body -->
        <div class="article-content" style="font-size: 17px; color: var(--slate-200); line-height: 1.8;">
            <?= $post['content'] ?>
        </div>

        <!-- Share & WhatsApp Action -->
        <div style="margin: 48px 0; padding: 24px; background: var(--navy-800); border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; border: 1px solid rgba(255,255,255,0.08);">
            <div>
                <strong style="color: var(--white); display: block;">Enjoyed this article?</strong>
                <span style="font-size: 14px; color: var(--slate-400);">Share it with your colleagues and friends on WhatsApp.</span>
            </div>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode("Check out this article by Kelvin Kibenje: \"" . $post['title'] . "\" - " . SITE_URL . "/post.php?slug=" . $post['slug']) ?>" 
               target="_blank" class="btn btn-gold">
                📱 Share on WhatsApp
            </a>
        </div>

        <!-- Author Bio Box -->
        <div style="background: var(--navy-950); border: 1px solid var(--gold-500); border-radius: var(--radius-lg); padding: 32px; display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
            <img src="/images/avatar.jpg" alt="Kelvin Kibenje" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--gold-500);">
            <div style="flex-grow: 1;">
                <div style="font-size: 12px; color: var(--gold-400); font-weight: 700; text-transform: uppercase;">About the Author</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin: 4px 0 8px;">
                    Kelvin Kibenje Kenedy Kyaluoko
                </h3>
                <p style="font-size: 14px; color: var(--slate-300); margin-bottom: 12px;">
                    Certified Financial Educator by the Bank of Tanzania (BOT) and two-time Golden Man of the Year Award recipient. Kelvin helps dreamers become doers, and doers become winners through books, seminars, and corporate mentorship.
                </p>
                <div style="display: flex; gap: 12px;">
                    <a href="/about.php" style="font-size: 14px; color: var(--gold-400); font-weight: 600;">Read Full Bio →</a>
                    <a href="/books.php" style="font-size: 14px; color: var(--slate-300); font-weight: 600;">Explore His Books →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- RELATED POSTS -->
<?php if (!empty($related_posts)): ?>
<section class="section section-alt">
    <div class="container" style="max-width: 900px;">
        <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 24px; margin-bottom: 24px;">
            Related Financial Articles
        </h3>
        <div class="blog-grid">
            <?php foreach ($related_posts as $rel): ?>
                <article class="post-card">
                    <div class="post-content">
                        <div class="post-meta"><?= format_date($rel['published_at']) ?></div>
                        <h4 style="font-family: var(--font-heading); font-size: 18px; color: var(--white); margin: 8px 0;">
                            <a href="/post.php?slug=<?= h($rel['slug']) ?>"><?= h($rel['title']) ?></a>
                        </h4>
                        <p class="post-excerpt"><?= h(truncate_text($rel['excerpt'], 100)) ?></p>
                        <a href="/post.php?slug=<?= h($rel['slug']) ?>" class="read-more-link">Read Article →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
