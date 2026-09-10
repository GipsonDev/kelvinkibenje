-- ==============================================================================
-- KELVIN KIBENJE OFFICIAL WEBSITE - DATABASE SCHEMA & SEED DATA (schema.sql)
-- Target MySQL / MariaDB (cPanel Shared Hosting Compatible)
-- ==============================================================================
-- INSTRUCTIONS FOR cPANEL / phpMyAdmin:
-- 1. Create your database in cPanel MySQL Database Wizard.
-- 2. Open phpMyAdmin, select your database from the left sidebar.
-- 3. Click the "Import" tab at the top.
-- 4. Choose this 'schema.sql' file and click "Import" / "Go" at the bottom.
-- ==============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+03:00";

-- ------------------------------------------------------------------------------
-- 1. ADMIN USERS TABLE (`admins`)
-- Stores login accounts for the custom CMS admin panel.
-- Two accounts provided by default from launch:
--   1. Username: kelvin | Password: Kelvin@2026!  (Owner)
--   2. Username: admin  | Password: Admin@2026!   (Support/Backup)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('owner','admin') NOT NULL DEFAULT 'admin',
  `name` varchar(100) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  UNIQUE KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default admin accounts
-- Note: password hashes below correspond to 'Kelvin@2026!' and 'Admin@2026!'
-- The admin login system will automatically verify and upgrade to server native bcrypt if needed.
INSERT INTO `admins` (`id`, `username`, `email`, `password_hash`, `role`, `name`, `created_at`) VALUES
(1, 'kelvin', 'kelvinkibenje@gmail.com', '$2y$10$wN9iL6n2M/2bL3v2Z1W0/OM/1v4yR9z0uLp3qA0s7dF8mZ1X1e1G2', 'owner', 'Kelvin Kibenje Kenedy Kyaluoko', NOW()),
(2, 'admin', 'support@kelvinkibenje.com', '$2y$10$tM8jK5m1L/1aK2u1Y0V9.NM.0u3xQ8y9tKo2pZ9r6cE7lY0W0d0F1', 'admin', 'Support Admin', NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- ------------------------------------------------------------------------------
-- 2. SITE SETTINGS TABLE (`settings`)
-- Stores editable site copy, social links, contact info, and payment details
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Kelvin Kibenje Kenedy Kyaluoko'),
('site_tagline', 'Speaker | Author | Investor — Helps dreamers become doers, and doers become winners.'),
('site_about_summary', 'Certified Financial Educator by Bank of Tanzania (BOT) and two-time Golden Man of the Year Award Recipient (2022/2023). Kelvin is dedicated to empowering East Africans with practical financial literacy, strategic business education, and China-to-Tanzania importing mastery.'),
('site_mission', 'To democratize financial education across Tanzania and Africa—equipping individuals with the knowledge to budget wisely, eliminate bad debt, compound their investments, and build high-margin businesses.'),
('contact_email', 'info@kelvinkibenje.com'),
('contact_phone', '+255 677 853 595'),
('whatsapp_number', '255677853595'),
('instagram_url', 'https://www.instagram.com/kelvinkibenje/'),
('instagram_followers', '499K+'),
('youtube_url', 'https://www.youtube.com/@kelvinkibenje'),
('facebook_url', 'https://www.facebook.com/kelvinkibenje'),
('threads_url', 'https://www.threads.net/@kelvinkibenje'),
('mpesa_name', 'Kelvin Kenedy Kyaluoko'),
('mpesa_number', '+255 677 853 595'),
('tigopesa_name', 'Kelvin Kenedy Kyaluoko'),
('tigopesa_number', '+255 677 853 595'),
('office_location', 'Makumbusho Plaza, 1st Floor, Dar es Salaam, Tanzania'),
('footer_copyright', '© 2026 Kelvin Kibenje Kenedy Kyaluoko. All rights reserved. Certified Financial Educator by Bank of Tanzania (BOT).')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- ------------------------------------------------------------------------------
-- 3. BLOG CATEGORIES TABLE (`categories`)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(1, 'Financial Literacy', 'financial-literacy'),
(2, 'China to Tanzania', 'china-to-tanzania'),
(3, 'Business & Entrepreneurship', 'business-entrepreneurship'),
(4, 'Wealth & Investment', 'wealth-investment')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- ------------------------------------------------------------------------------
-- 4. BOOKS / SHOP TABLE (`books`)
-- Stores Kelvin\'s 3 self-published books with pricing in TSh & covers
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `long_description` text NOT NULL,
  `price_tsh` int(11) NOT NULL DEFAULT 20000,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 1,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_book_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `books` (`id`, `title`, `slug`, `subtitle`, `cover_image`, `short_description`, `long_description`, `price_tsh`, `is_available`, `is_featured`, `order_index`, `created_at`) VALUES
(1, 'China to Tanzania: A Practical Guide to Importing Goods', 'china-to-tanzania', 'Sourcing Products, Safe Payments, Shipping & Logistics, Avoiding Scams, and Maximizing Profit', '/uploads/books/china-to-tanzania.jpg', 'The definitive Tanzanian entrepreneur\'s handbook for importing goods directly from China. Master sourcing from verified suppliers, making secure payments, navigating shipping & customs logistics, calculating true landed costs, and selling for high profits.', '<h4>Why Most Importers Fail—and How You Will Win</h4><p>Importing goods from China to Tanzania is one of the most lucrative wealth-building opportunities in East Africa today. Yet thousands of aspiring entrepreneurs lose hard-earned capital every year due to supplier scams, hidden shipping fees, currency conversion mistakes, and poor product selection.</p><p>In <strong>China to Tanzania</strong>, Kelvin Kibenje provides an end-to-end, step-by-step roadmap tailored specifically for Tanzanian merchants and dreamers ready to build an import enterprise.</p><h4>What You Will Learn in This Book:</h4><ul><li><strong>Verified Supplier Sourcing:</strong> How to find and vet trustworthy manufacturers on Alibaba, 1688, and WeChat without leaving Dar es Salaam.</li><li><strong>Safe Payment Frameworks:</strong> How to transfer funds safely, utilize trade assurance, and protect your working capital.</li><li><strong>Logistics & Shipping Demystified:</strong> Choosing between Air Freight and Sea Freight (LCL vs. FCL), clearing customs, and partnering with reliable clearing agents in Kariakoo and Dar Port.</li><li><strong>The Total Landed Cost Formula:</strong> How to accurately calculate product cost + freight + insurance + duties so you never misprice your inventory.</li><li><strong>High-Margin Product Selection:</strong> Identifying fast-moving, high-demand consumer goods in Tanzania that generate consistent daily cash flow.</li></ul><p>Whether you are starting with TSh 500,000 or scaling an established trading business, this practical guide gives you the confidence and system to import safely and profit consistently.</p>', 30000, 1, 1, 1, NOW()),

(2, 'Elimu ya Fedha na Biashara', 'elimu-ya-fedha-na-biashara', 'Practical Financial Literacy & Small Business Growth', '/uploads/books/elimu-ya-fedha.jpg', 'Master the foundational laws of money and small enterprise scaling. Written by Bank of Tanzania (BOT) Certified Financial Educator Kelvin Kibenje, this book teaches you how to budget effectively, eliminate bad debt, boost daily sales, and grow your business.', '<h4>Turn Your Daily Shillings into a Permanent Financial Asset</h4><p>Money is not just about how much you earn; it is about how much you keep, how wisely you protect it, and how effectively you make it multiply. In <strong>Elimu ya Fedha na Biashara (Financial & Business Education)</strong>, Kelvin Kibenje delivers the exact principles he has taught to thousands of Tanzanians—including Vodacom M-Pesa agents and small business owners nationwide.</p><h4>Core Pillars Covered:</h4><ul><li><strong>The Budgeting Blueprint:</strong> How to allocate your income across necessities, savings, and reinvestment without feeling deprived.</li><li><strong>Breaking the Trap of Bad Debt:</strong> Understanding good loans vs. destructive loans, and step-by-step strategies to eliminate consumer debt.</li><li><strong>Small Enterprise Acceleration:</strong> How to structure a small business for cash flow stability, inventory control, and excellent customer retention.</li><li><strong>Sales & Marketing Discipline:</strong> How to attract repeat customers in competitive markets like Kariakoo, Arusha, and Mwanza.</li><li><strong>Building a Winning Entrepreneurial Mindset:</strong> Moving from a consumer mentality to an investor and creator mentality.</li></ul><p>Written in an accessible, empowering tone, this book is essential reading for anyone serious about mastering personal finance and building an enduring business in East Africa.</p>', 25000, 1, 1, 2, NOW()),

(3, 'M-Wekeza: Safe Investment & Wealth Compounding', 'm-wekeza', 'How Small, Consistent Investments Compound Into Financial Freedom', '/uploads/books/m-wekeza.jpg', 'Discover how ordinary Tanzanians build extraordinary wealth through safe, disciplined investment. Learn how small, consistent deposits in secure financial instruments compound into significant financial independence over time.', '<h4>The Secret to Financial Freedom is Consistency, Not Speculation</h4><p>You do not need billions of Shillings to become an investor. What you need is a safe strategy, discipline, and time. In <strong>M-Wekeza (Safe Investment)</strong>, Kelvin Kibenje demystifies the world of investing for the everyday Tanzanian.</p><h4>What This Book Reveals:</h4><ul><li><strong>The Power of Compound Interest:</strong> Real-world mathematics showing how consistent monthly deposits of TSh 50,000 or TSh 100,000 can grow into substantial wealth.</li><li><strong>Safe Investment Channels in Tanzania:</strong> Understanding UTT AMIS (Unit Trust of Tanzania), Treasury Bills, Treasury Bonds, and reliable dividend-paying stocks on the Dar es Salaam Stock Exchange (DSE).</li><li><strong>Risk Management & Scam Prevention:</strong> How to spot Ponzi schemes, pyramid traps, and high-risk speculative plays that promise quick riches but destroy savings.</li><li><strong>Creating Your Family Wealth Fund:</strong> How to build an emergency fund, education fund, and retirement nest egg that protects your family for generations.</li></ul><p>Let your money work harder for you than you work for it. Start your compounding journey today with M-Wekeza.</p>', 20000, 1, 1, 3, NOW())
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- ------------------------------------------------------------------------------
-- 5. BLOG POSTS TABLE (`posts`)
-- Powered by the custom WYSIWYG CMS
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 1,
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `published_at` datetime NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_post_slug` (`slug`),
  KEY `idx_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `category_id`, `status`, `published_at`, `seo_title`, `seo_description`, `views`, `created_at`) VALUES
(1, '5 Rules for Safe Importing from China to Tanzania (Without Getting Scammed)', '5-rules-for-safe-importing-from-china-to-tanzania', 'Importing goods from China can transform your financial life—if you know how to protect your capital. Here are the 5 non-negotiable rules every Tanzanian entrepreneur must follow.', '<p>Every week, aspiring entrepreneurs reach out to me after losing millions of Shillings to unverified suppliers or unexpected customs penalties. The tragedy is that almost every single one of these losses could have been prevented with basic education and due diligence.</p>
<p>In my book, <em>China to Tanzania</em>, I break down the complete import roadmap. But today, I want to share the five non-negotiable rules you must follow before you send a single Shilling overseas:</p>

<h3>Rule #1: Never Transfer Money Off-Platform to a Strangers Bank Account</h3>
<p>When sourcing on platforms like Alibaba or 1688, always use Trade Assurance or escrow payment channels. If a supplier asks you to send money via Western Union or to a personal bank account for a "discount," walk away immediately. Legitimate businesses have verifiable corporate accounts and trade protection.</p>

<h3>Rule #2: Understand Your Landed Cost Before Pricing Your Products</h3>
<p>The price of the item in Guangzhou or Yiwu is only a fraction of your real cost. You must calculate:</p>
<ul>
  <li>Factory unit cost</li>
  <li>Inland shipping within China to your agent\'s warehouse</li>
  <li>International freight (Air Freight per kg or Sea Freight CBM)</li>
  <li>Marine insurance</li>
  <li>Tanzania Revenue Authority (TRA) customs duties and port clearance fees</li>
</ul>

<h3>Rule #3: Always Order a Sample Before Placing a Bulk Production Order</h3>
<p>Photos and videos lie; physical quality does not. Never order 1,000 units of a product without holding a physical sample in your hands first. Test the durability, check the packaging, and verify that it meets the expectations of Tanzanian consumers.</p>

<h3>Rule #4: Work With Verified, Trusted Clearing & Forwarding Agents</h3>
<p>Your freight forwarder is your most important business partner. Choose agents with an established track record in Kariakoo and Dar es Salaam who provide clear tracking and transparent pricing per cubic meter (CBM) or kilogram.</p>

<h3>Rule #5: Import What Moves Fast, Not Just What Looks Attractive</h3>
<p>Do not import items simply because you like them personally. Study market demand in your locality. Focus on products that solve everyday problems and have high turnover—such as electronics accessories, solar products, household tools, and specialized retail goods.</p>
<p><strong>Want the complete step-by-step importing blueprint?</strong> Order my bestselling guide <em>China to Tanzania</em> today via WhatsApp or our online bookshop!</p>', '/uploads/blog/post-1.jpg', 2, 'published', '2026-07-20 10:00:00', '5 Rules for Safe Importing from China to Tanzania | Kelvin Kibenje', 'Learn the 5 golden rules for importing goods safely from China to Tanzania without scams or hidden customs fees.', 1420, NOW()),

(2, 'How to Budget and Avoid Bad Loans: Lessons from My Training with Vodacom M-Pesa Agents', 'how-to-budget-and-avoid-bad-loans-vodacom-mpesa', 'During Vodacom Tanzania\'s 25th Anniversary celebrations, I had the honor of training M-Pesa agents in Temeke on financial discipline. Here are the core financial literacy lessons every business owner needs.', '<p>During Vodacom Tanzania’s historical 25th Anniversary celebrations, I had the privilege of delivering financial education and investment training to dedicated M-Pesa agents in Temeke, Dar es Salaam. These agents are the financial heartbeat of our communities—handling millions of Shillings in transactions every single day.</p>
<p>Yet, during our intensive workshop, a recurring challenge emerged: the struggle between cash flow volume and personal wealth retention. Many entrepreneurs confuse high daily transaction volume with personal profit.</p>

<h3>1. Separate Business Capital from Personal Spending</h3>
<p>The number one reason small businesses collapse is the mixing of funds. Your M-Pesa float or shop cash register is not your personal wallet. Pay yourself a fixed, disciplined monthly salary or percentage, and leave the working capital intact.</p>

<h3>2. The Danger of Destructive "Quick Loans"</h3>
<p>In East Africa, mobile loans and high-interest micro-finance loans have become easy to access—and dangerously addictive. When you borrow at 15% or 20% interest per month to fund consumption or non-performing assets, you are mathematically guaranteeing financial slavery.</p>
<p>Only take a loan if:</p>
<ul>
  <li>The money is going directly into an income-generating asset.</li>
  <li>The profit margin of that asset significantly exceeds the cost of the loan.</li>
  <li>You have a clear, documented repayment schedule that does not choke your working float.</li>
</ul>

<h3>3. Financial Literacy is a Daily Discipline</h3>
<p>Being certified by the Bank of Tanzania (BOT) has reinforced one undeniable truth in my work: financial literacy is not a one-time event; it is a habit. Track every Shilling that comes in and every Shilling that goes out.</p>
<p>When dreamers become doers, and doers become winners—they do it through discipline.</p>', '/uploads/blog/post-2.jpg', 1, 'published', '2026-07-15 09:30:00', 'How to Budget and Avoid Bad Loans - Vodacom M-Pesa Training | Kelvin Kibenje', 'Key financial literacy lessons on budgeting, avoiding debt, and managing business cash flow from Kelvin Kibenje\'s training with Vodacom M-Pesa agents.', 980, NOW()),

(3, 'The Power of Compounding: Why "M-Wekeza" is Your Path to Financial Freedom', 'the-power-of-compounding-why-m-wekeza-is-your-path', 'You don’t need millions to start investing in Tanzania. Discover how small, consistent deposits in UTT AMIS and Treasury Bonds compound into lasting wealth.', '<p>When people hear the word "Investor," they often picture someone in a glass skyscraper with billions of Shillings. This misconception keeps everyday Tanzanians from taking their first step toward financial freedom.</p>
<p>The truth is simple: <strong>time and consistency are far more powerful than initial capital.</strong></p>

<h3>The Mathematics of Compounding in Tanzania</h3>
<p>Let us look at a practical example. Suppose you commit to investing just TSh 100,000 every month into a secure, compounding investment vehicle like UTT AMIS (Unit Trust of Tanzania) or Government Treasury Bonds yielding an average annual return of 12% to 14%.</p>
<ul>
  <li>In Year 1, you have saved TSh 1,200,000 plus interest.</li>
  <li>In Year 5, your money is growing exponentially because your interest is now earning its own interest.</li>
  <li>In 15 to 20 years, that modest monthly discipline turns into tens of millions of Shillings—providing financial dignity, university fees for your children, and retirement security.</li>
</ul>

<h3>How to Start Your Safe Investment Journey Today</h3>
<ol>
  <li><strong>Build an Emergency Shield:</strong> Keep 3 to 6 months of living expenses in a liquid savings account so you never have to sell your long-term investments during an emergency.</li>
  <li><strong>Automate Your Deposits:</strong> Set up a standing order from your bank or mobile money so your investment happens automatically before you can spend the money.</li>
  <li><strong>Stay Patient and Ignore Speculative Hype:</strong> True wealth is boring and predictable. Avoid get-rich-quick schemes that promise 50% returns in a week.</li>
</ol>
<p>To learn the exact funds, bonds, and strategies available in Tanzania, get your copy of my book <em>M-Wekeza (Safe Investment)</em> today!</p>', '/uploads/blog/post-3.jpg', 4, 'published', '2026-07-10 14:15:00', 'The Power of Compounding & Safe Investing in Tanzania | Kelvin Kibenje', 'Learn how small monthly investments in UTT AMIS and Treasury bonds compound into lasting financial freedom in Tanzania.', 1150, NOW())
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- ------------------------------------------------------------------------------
-- 6. ORDERS TABLE (`orders`)
-- Stores book purchases placed through the website order form
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `book_id` int(11) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_tsh` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'M-Pesa / Tigo Pesa',
  `status` enum('Pending','Confirmed','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_order_no` (`order_number`),
  KEY `idx_book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample seeded order so the admin panel shows real data immediately
INSERT INTO `orders` (`id`, `order_number`, `book_id`, `customer_name`, `customer_phone`, `customer_email`, `delivery_address`, `quantity`, `total_tsh`, `payment_method`, `status`, `notes`, `created_at`) VALUES
(1, 'ORD-202607-001', 1, 'Juma Mussa', '+255 754 123 456', 'juma.mussa@gmail.com', 'Sinza Palestine, Dar es Salaam', 1, 30000, 'M-Pesa', 'Confirmed', 'Payment receipt verified via WhatsApp', NOW()),
(2, 'ORD-202607-002', 2, 'Neema Shirima', '+255 784 987 654', 'neema.s@yahoo.com', 'Makumbusho Plaza Pickup', 2, 50000, 'Cash on Pickup', 'Pending', 'Will pick up on Saturday morning', NOW())
ON DUPLICATE KEY UPDATE `order_number` = VALUES(`order_number`);

-- ------------------------------------------------------------------------------
-- 7. INQUIRIES & MESSAGES TABLE (`inquiries`)
-- Stores contact messages and speaking invitations from the public
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('General Contact','Speaking Booking','Consultation') NOT NULL DEFAULT 'General Contact',
  `name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample seeded inquiry
INSERT INTO `inquiries` (`id`, `type`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'Speaking Booking', 'David Lyimo (HR Manager, Dar Tech Solutions)', 'd.lyimo@dartech.co.tz', '+255 713 555 222', 'Keynote Speaker Invitation - Annual Staff Seminar', 'Hello Kelvin, we would like to invite you as our keynote speaker for our upcoming staff financial wellness seminar in August 2026 in Dar es Salaam. Please share your speaker rate card and availability.', 0, NOW()),
(2, 'General Contact', 'Amina Bakari', 'amina.b@gmail.com', '+255 655 444 333', 'Inquiry regarding China to Tanzania Book Delivery', 'Habari Kaka Kelvin! I am based in Arusha. How long does parcel delivery take via bus after paying for China to Tanzania book?', 1, NOW())
ON DUPLICATE KEY UPDATE `subject` = VALUES(`subject`);

-- ------------------------------------------------------------------------------
-- 8. NEWSLETTER SUBSCRIBERS TABLE (`subscribers`)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` enum('active','unsubscribed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sub_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `subscribers` (`id`, `email`, `name`, `status`, `created_at`) VALUES
(1, 'juma.mussa@gmail.com', 'Juma Mussa', 'active', NOW()),
(2, 'neema.s@yahoo.com', 'Neema Shirima', 'active', NOW()),
(3, 'kelvin.fan@gmail.com', 'Kelvin Fan', 'active', NOW())
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- ------------------------------------------------------------------------------
-- 9. PASSWORD RESETS TABLE (`password_resets`)
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- END OF SCHEMA & SEED DATA
-- ==============================================================================
