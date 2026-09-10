<?php
/**
 * ==============================================================================
 * ABOUT PAGE (about.php)
 * Full Biography, Mission, BOT Credentials, Golden Man Awards & Photo Gallery
 * ==============================================================================
 */

require_once __DIR__ . '/includes/functions.php';

$page_title = "About Kelvin Kibenje | Certified Financial Educator & Author";
$page_description = "Read the official biography of Kelvin Kibenje Kenedy Kyaluoko—BOT Certified Financial Educator, two-time Golden Man of the Year, author, speaker, and investor in Dar es Salaam, Tanzania.";

include __DIR__ . '/includes/header.php';
?>

<!-- ABOUT HERO BANNER -->
<section class="section section-alt" style="padding: 60px 0 70px;">
    <div class="container">
        <div class="section-header" style="margin-bottom: 30px;">
            <div class="section-subtitle">Official Biography &amp; Mission</div>
            <h1 class="section-title">Kelvin Kibenje Kenedy Kyaluoko</h1>
            <p class="section-desc">
                Speaker | Author | Investor — Helps dreamers become doers, and doers become winners.
            </p>
        </div>
    </div>
</section>

<!-- FULL BIOGRAPHY & MISSION -->
<section class="section">
    <div class="container">
        <div class="about-preview-grid">
            <div class="about-text-content">
                <div class="section-subtitle">The Story Behind the Mission</div>
                <h3>From Everyday Discipline to Transformational Financial Freedom</h3>
                
                <p>
                    Based in Dar es Salaam, Tanzania, <strong>Kelvin Kibenje Kenedy Kyaluoko</strong> has emerged as one of East Africa's most respected and practical voices in financial literacy, business education, and safe investment.
                </p>
                <p>
                    With over <strong>499,000+ followers on Instagram (@kelvinkibenje)</strong> and a growing community of disciplined entrepreneurs nationwide, Kelvin’s philosophy is rooted in action: <em>"We must move from merely wishing for financial independence to implementing the daily disciplines that create it."</em>
                </p>
                <p>
                    As an official <strong>Certified Financial Educator by the Bank of Tanzania (BOT)</strong>, Kelvin combines institutional rigor with relatable, grassroots teaching. Whether mentoring corporate teams, training M-Pesa agents in Temeke, or coaching small business owners in Kariakoo and Arusha, his lessons simplify the complex laws of money.
                </p>

                <div style="background: var(--navy-800); border-left: 4px solid var(--gold-500); padding: 20px 24px; border-radius: 4px; margin: 28px 0;">
                    <p style="color: var(--white); font-style: italic; font-size: 17px; margin-bottom: 8px;">
                        "My mission is not just to teach you how to make money. My mission is to teach you how to keep it, how to protect it from bad debt, and how to make every Shilling multiply through smart business and compounding investment."
                    </p>
                    <span style="color: var(--gold-400); font-weight: 600; font-size: 14px;">— Kelvin Kibenje Kenedy Kyaluoko</span>
                </div>

                <h3>Official Credentials &amp; Recognition</h3>
                <ul class="credentials-list">
                    <li>
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Certified Financial Educator — Bank of Tanzania (BOT)</strong><br>
                            <span style="color: var(--slate-400); font-size: 14px;">Certified to deliver national financial literacy, banking, and safe investment curricula.</span>
                        </div>
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Two-Time "Golden Man of the Year" Award Recipient (2022 / 2023)</strong><br>
                            <span style="color: var(--slate-400); font-size: 14px;">Honored consecutively for outstanding impact in youth mentorship, economic empowerment, and business leadership.</span>
                        </div>
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        <div>
                            <strong>Author of Three Bestselling Self-Published Books</strong><br>
                            <span style="color: var(--slate-400); font-size: 14px;">Author of <em>China to Tanzania</em>, <em>Elimu ya Fedha na Biashara</em>, and <em>M-Wekeza</em>.</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="about-img-group">
                <img src="/images/about-2.jpg" alt="Kelvin Kibenje standing at Girls Roundtable event" class="about-main-img" style="margin-bottom: 24px;">
                <img src="/images/about-3.jpg" alt="Kelvin Kibenje mentoring in Tanzania" class="about-main-img">
            </div>
        </div>
    </div>
</section>

<!-- CORE PILLARS OF EDUCATION -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">What Kelvin Teaches</div>
            <h2 class="section-title">The Four Pillars of Financial Empowerment</h2>
            <p class="section-desc">
                Everything Kelvin teaches across seminars, workshops, and books is built upon four interconnected disciplines.
            </p>
        </div>

        <div class="books-grid">
            <div class="book-card" style="padding: 32px;">
                <div style="font-size: 32px; color: var(--gold-500); margin-bottom: 16px;">01</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin-bottom: 12px;">
                    Practical Budgeting &amp; Debt Control
                </h3>
                <p style="color: var(--slate-300); font-size: 15px;">
                    How to budget effectively on any income level, separate business working float from personal expenditure, and permanently break free from destructive consumer debt and high-interest mobile loans.
                </p>
            </div>

            <div class="book-card" style="padding: 32px;">
                <div style="font-size: 32px; color: var(--gold-500); margin-bottom: 16px;">02</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin-bottom: 12px;">
                    China-to-Tanzania Importing
                </h3>
                <p style="color: var(--slate-300); font-size: 15px;">
                    The complete roadmap for sourcing verified products from China, making secure payments without scams, clearing customs in Dar es Salaam, and calculating true landed costs for maximum retail profit.
                </p>
            </div>

            <div class="book-card" style="padding: 32px;">
                <div style="font-size: 32px; color: var(--gold-500); margin-bottom: 16px;">03</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin-bottom: 12px;">
                    Small Business Scaling
                </h3>
                <p style="color: var(--slate-300); font-size: 15px;">
                    How to turn a small trading stall or service venture into a resilient asset. Teaches inventory management, daily sales acceleration, and customer retention in East African markets.
                </p>
            </div>

            <div class="book-card" style="padding: 32px;">
                <div style="font-size: 32px; color: var(--gold-500); margin-bottom: 16px;">04</div>
                <h3 style="font-family: var(--font-heading); color: var(--white); font-size: 22px; margin-bottom: 12px;">
                    Safe Investment &amp; Compounding
                </h3>
                <p style="color: var(--slate-300); font-size: 15px;">
                    Demystifying UTT AMIS funds, Government Treasury Bonds, and DSE stocks. How ordinary Tanzanians compound small monthly savings into multi-million Shilling wealth funds.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- VISUAL STORYTELLING & PHOTO GALLERY -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-subtitle">Visual Storytelling</div>
            <h2 class="section-title">In Action Across East Africa</h2>
            <p class="section-desc">
                From speaking at youth leadership forums and corporate training seminars to hosting financial literacy workshops.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            <div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <img src="/images/speaker-1.jpg" alt="Kelvin speaking with microphone" style="width: 100%; height: 320px; object-fit: cover;">
                <div style="background: var(--navy-800); padding: 16px; font-size: 14px; color: var(--gold-400); font-weight: 600;">
                    Keynote Speaker — Youth Financial Forums
                </div>
            </div>
            <div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <img src="/images/speaker-2.jpg" alt="Kelvin teaching at pulpit" style="width: 100%; height: 320px; object-fit: cover;">
                <div style="background: var(--navy-800); padding: 16px; font-size: 14px; color: var(--gold-400); font-weight: 600;">
                    Community Financial Literacy Seminars
                </div>
            </div>
            <div style="border-radius: var(--radius-md); overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <img src="/images/speaker-3.jpg" alt="Kelvin seated on armchair speaking" style="width: 100%; height: 320px; object-fit: cover;">
                <div style="background: var(--navy-800); padding: 16px; font-size: 14px; color: var(--gold-400); font-weight: 600;">
                    Executive Mentorship &amp; Panels
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 48px; display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="/speaking.php" class="btn btn-gold">Invite Kelvin to Speak</a>
            <a href="/books.php" class="btn btn-outline-gold">Explore His Books</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
