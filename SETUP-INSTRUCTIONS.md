# 🌟 Kelvin Kibenje Kenedy Kyaluoko — Official Personal Brand Website
**Certified Financial Educator by Bank of Tanzania (BOT) | Author | Speaker | Investor**  
*Help Dreamers Become Doers, and Doers Become Winners.*

---

## 🎁 About This Surprise Gift Project

This custom personal branding website was built as a finished, presentable, and professional gift for **Kelvin Kibenje** (@kelvinkibenje, 499K+ Instagram followers). It is purpose-built to run on **standard shared cPanel hosting (PHP 8.x + MySQL/MariaDB)** with **zero server-side build steps or command-line installation required**.

Every photo from Kelvin's official portfolio has been pre-configured and optimized, and professional 3D cover art has been generated for all three of his self-published books:
1. **China to Tanzania: A Practical Guide to Importing Goods**
2. **Elimu ya Fedha na Biashara** *(Financial & Business Education)*
3. **M-Wekeza: Safe Investment & Wealth Compounding**

---

## 📋 Table of Contents
1. [What's Included (Folder Structure)](#1-whats-included-folder-structure)
2. [Step-by-Step cPanel Hosting Setup](#2-step-by-step-cpanel-hosting-setup)
   - [Step A: Create Your MySQL Database](#step-a-create-your-mysql-database-in-cpanel)
   - [Step B: Import the Database Schema in phpMyAdmin](#step-b-import-the-database-schema-in-phpmyadmin)
   - [Step C: Upload Website Files via File Manager or FTP](#step-c-upload-website-files-via-file-manager-or-ftp)
   - [Step D: Configure `config.php`](#step-d-configure-configphp)
3. [Logging Into the CMS Admin Panel (`/admin`)](#3-logging-into-the-cms-admin-panel-admin)
   - [Default Launch Login Accounts](#default-launch-login-accounts)
   - [How to Change Your Password](#how-to-change-your-password)
4. [How to Use Your Custom CMS](#4-how-to-use-your-custom-cms)
   - [How to Write and Publish Blog Posts](#how-to-write-and-publish-blog-posts)
   - [How to Manage Books & TSh Pricing](#how-to-manage-books--tsh-pricing)
   - [How to Track Book Orders & Reply via WhatsApp](#how-to-track-book-orders--reply-via-whatsapp)
   - [How to Edit Site Settings & Mobile Money Numbers](#how-to-edit-site-settings--mobile-money-numbers)
5. [Managing Images & Photos](#5-managing-images--photos)
6. [Troubleshooting & Shared Hosting FAQ](#6-troubleshooting--shared-hosting-faq)

---

## 1. What's Included (Folder Structure)

```
public_html/
├── .htaccess                 <-- Clean URLs, HTTPS redirect, folder protection & cache rules
├── config.php                <-- CENTRAL CONFIGURATION FILE (Enter DB Credentials here!)
├── schema.sql                <-- SINGLE MYSQL SCHEMA FILE (Import via phpMyAdmin)
├── README.md                 <-- This comprehensive documentation file
├── SETUP-INSTRUCTIONS.md     <-- Duplicate of README for quick reference
├── index.php                 <-- Homepage (Hero, Books Grid, Vodacom Feature, Blog feed)
├── about.php                 <-- About Page (Biography, BOT Certification, Awards, Photo Gallery)
├── speaking.php              <-- Speaking & Corporate Impact (Vodacom Temeke M-Pesa training feature)
├── books.php                 <-- Complete Bookshop, Order Processing & Complete Library Bundle
├── book.php                  <-- Single Book Detail Page (Table of contents, M-Pesa/Tigo Pesa details)
├── blog.php                  <-- Blog Listing (Category filtering & instant search)
├── post.php                  <-- Single Article Reader (View counter, author box, share buttons)
├── contact.php               <-- Contact & Speaking Booking Form (Sends email + saves to DB)
├── includes/
│   ├── db.php                <-- Singleton PDO connection wrapper (100% SQL injection proof)
│   ├── functions.php         <-- Security (CSRF, XSS protection, TSh formatting, uploads, emailer)
│   ├── header.php            <-- Responsive navigation header & top notification bar
│   └── footer.php            <-- Newsletter subscription modal, social links & copyright
├── assets/
│   ├── css/style.css         <-- Premium Navy (#0B1329) & Warm Gold (#D4AF37) branding theme
│   ├── js/main.js            <-- Mobile menu toggle, Order modal & WhatsApp instant buyer link
│   └── admin/                <-- Admin panel stylesheet (admin.css) and WYSIWYG helper (admin.js)
├── images/                   <-- Website portfolio photography (hero.jpg, about.jpg, speaker-1.jpg...)
├── uploads/
│   ├── books/                <-- Generated 3D Book covers (china-to-tanzania.jpg...)
│   └── blog/                 <-- Featured blog article images (post-1.jpg, post-2.jpg...)
└── admin/                    <-- CUSTOM CMS ADMIN PANEL (Zero WordPress/external dependencies)
    ├── login.php             <-- Secure Login (CSRF, password_hash, auto-upgrade bcrypt)
    ├── logout.php            <-- Session destroy
    ├── forgot-password.php   <-- Email reset link generator
    ├── reset-password.php    <-- Token-based password reset form
    ├── index.php             <-- Dashboard Overview (Real-time stats, recent orders & messages)
    ├── blog.php              <-- List/Filter Articles (Edit, Duplicate, Delete)
    ├── blog-edit.php         <-- WYSIWYG Article Editor with inline image uploader & auto-slug
    ├── blog-delete.php       <-- Post deleter
    ├── books.php             <-- Bookshop Manager (TSh pricing, stock toggle, order index)
    ├── book-edit.php         <-- Add/Edit Book metadata, cover image upload & descriptions
    ├── book-delete.php       <-- Book deleter
    ├── orders.php            <-- Book Orders log with "Chat on WhatsApp" instant button
    ├── inquiries.php         <-- Speaking invitations & contact message inbox
    ├── inquiry-detail.php    <-- Read message & reply via WhatsApp/Email
    ├── inquiry-delete.php    <-- Message deleter
    ├── subscribers.php       <-- Newsletter audience list
    ├── subscribers-export.php<-- 1-click CSV Exporter for newsletter list
    ├── settings.php          <-- No-code site settings editor (tagline, social links, mobile money)
    └── profile.php           <-- Admin Profile & Password updater
```

---

## 2. Step-by-Step cPanel Hosting Setup

You can launch this website in less than **10 minutes** on any standard shared cPanel host.

### Step A: Create Your MySQL Database in cPanel
1. Log into your hosting **cPanel Dashboard**.
2. Under the **Databases** section, click on **MySQL® Database Wizard**.
3. **Create Database:** Enter a database name (for example, `kelvinkibenje_db`) and click *Next Step*.
4. **Create User:** Enter a username (for example, `kelvin_user`) and a secure password. Save this password! Click *Create User*.
5. **Add User to Database:** Check the box that says **ALL PRIVILEGES**, then click **Make Changes**.

---

### Step B: Import the Database Schema in phpMyAdmin
1. Go back to your main cPanel dashboard and click **phpMyAdmin** under the Databases section.
2. In the left-hand sidebar of phpMyAdmin, click on your newly created database (`yourusername_kelvinkibenje_db`).
3. Along the top menu bar, click the **Import** tab.
4. Under *File to import*, click **Choose File** and select the **`schema.sql`** file from this folder.
5. Scroll to the bottom of the page and click **Import** (or **Go**).
6. You will see a green success message: *“Import has been successfully finished, queries executed.”* Your database is now fully populated with:
   - 2 Launch Admin Accounts (`kelvin` & `admin`)
   - Complete Site Settings (@kelvinkibenje Instagram, BOT certification, M-Pesa / Tigo Pesa numbers)
   - All 3 self-published books with descriptions, pricing in TSh, and cover photos
   - 4 Blog Categories & 3 complete, beautifully written sample blog articles
   - Sample orders and speaking inquiries to preview the admin panel.

---

### Step C: Upload Website Files via File Manager or FTP
1. In cPanel, click **File Manager**.
2. Open your website's web root directory (usually **`public_html`** for your main domain, or a subfolder if using an addon domain).
3. Upload all the files and folders from this project into `public_html/`.
4. Ensure the **`.htaccess`** file is uploaded so clean URLs (`/book/china-to-tanzania` and `/post/5-rules-for-safe-importing`) work correctly. *(If you don't see `.htaccess`, click "Settings" in the top right of File Manager and check "Show Hidden Files (dotfiles)").*

---

### Step D: Configure `config.php`
1. In cPanel File Manager, right-click on **`config.php`** and select **Edit**.
2. Locate **Section 1: DATABASE CREDENTIALS** at the top of the file:

```php
define('DB_HOST', 'localhost');              // Usually 'localhost' on shared cPanel hosts
define('DB_NAME', 'kelvin_website_db');      // Replace with your actual cPanel Database Name
define('DB_USER', 'kelvin_db_user');         // Replace with your actual cPanel MySQL Username
define('DB_PASS', 'YourSecurePasswordHere'); // Replace with your actual cPanel MySQL Password
```

3. Replace `DB_NAME`, `DB_USER`, and `DB_PASS` with the database credentials you created in Step A.
4. Click **Save Changes** in the top right corner.

**🎉 Congratulations! Your official Kelvin Kibenje website is now live!**

---

## 3. Logging Into the CMS Admin Panel (`/admin`)

To access your purpose-built Custom Content Management System:
1. Open your browser and visit: **`https://yourdomain.com/admin/login.php`**
2. You will be greeted by the custom dark navy and warm gold CMS login screen.

### Default Launch Login Accounts
Two accounts are created for you in `schema.sql`:

| Role | Username | Password | Email Address |
| :--- | :--- | :--- | :--- |
| **Primary Owner** | `kelvin` | `Kelvin@2026!` | `kelvinkibenje@gmail.com` |
| **Support Admin** | `admin` | `Admin@2026!` | `support@kelvinkibenje.com` |

> 🔒 **Intelligent Security Feature:** The login system uses PHP 8.x `password_verify()`. When you log in with `Kelvin@2026!` or `Admin@2026!` for the first time, the CMS automatically upgrades and re-hashes your password in the database using your hosting server's native bcrypt algorithm (`PASSWORD_DEFAULT`).

### How to Change Your Password
1. Once logged into `/admin/index.php`, click **"Profile & Password"** in the top right header (or select **🔒 Admin Account** in the left sidebar).
2. Enter your new password in the **New Password** field and confirm it.
3. Click **"Update Account Profile"**.

---

## 4. How to Use Your Custom CMS

Your custom CMS panel was designed to be as simple and intuitive as basic WordPress—with **zero code required**.

### How to Write and Publish Blog Posts
1. In the admin sidebar, click **✍️ Blog & Articles**.
2. Click the gold **"✍️ Write New Article"** button in the top right.
3. Enter your **Article Title** (e.g., *5 Rules for Safe Importing from China to Tanzania*).
4. Notice that the **URL Slug** is automatically generated for you as you type! (You can also edit it manually).
5. Type a short summary in **Short Excerpt**.
6. Use the **Quill.js Rich WYSIWYG Editor** to write your full article. You can apply Headings (H2, H3), Bold, Italic, Bulleted Lists, and Hyperlinks.
7. On the right-hand sidebar:
   - Select the **Category** (e.g., *China to Tanzania* or *Financial Literacy*).
   - Choose whether the status is **Published** or **Draft**.
   - Under **Featured Image**, click **Choose File** to upload an accompanying photo (JPG, PNG, or WEBP).
8. Click **"Publish Article"** (or **"Update Article"**). Your post is immediately live on `/blog.php`!

### How to Manage Books & TSh Pricing
1. In the admin sidebar, click **📚 Books (Shop)**.
2. You will see all 3 books listed with their cover photo, title, TSh price, and availability.
3. To edit a book (for example, to update a description or change a price), click **"Edit"** next to the book.
4. You can edit:
   - **Price in Tanzanian Shillings (TSh):** e.g., `30000` for TSh 30,000.
   - **Short Description & Long Description / Table of Contents.**
   - **Cover Photo Upload:** Easily replace the book cover graphic.
   - **Availability Toggle:** Uncheck "Available in Shop" if a book is temporarily out of stock.
   - **Order Index:** Control which book appears first (1, 2, 3).
5. Click **"Save Book"**.

### How to Track Book Orders & Reply via WhatsApp
Kelvin's audience loves buying books via WhatsApp and mobile money (M-Pesa / Tigo Pesa). We built a **hybrid checkout flow** that supports both instant WhatsApp ordering and website order form tracking:
1. When a customer clicks **"Order Online"** on any book page, they fill out their Name, Phone number, Delivery Location (e.g., Sinza Palestine, Dar es Salaam / Arusha / Makumbusho Plaza pickup), and select their payment method (`M-Pesa`, `Tigo Pesa`, or `Cash on Pickup`).
2. Upon submitting, their order is logged in your database and an **email notification** is sent to `SITE_EMAIL`.
3. In your admin panel, click **🛒 Book Orders**.
4. You will see the customer's order number, full name, phone number, book title, total TSh amount, and delivery address.
5. Click the gold **"📱 Chat"** button next to any order. This opens **WhatsApp Web / WhatsApp Mobile** directly to the customer's phone number with a pre-filled message confirming their order!
6. Once you verify their M-Pesa / Tigo Pesa payment receipt, change their order status in the dropdown from **Pending** to **Confirmed** or **Completed**.

### How to Edit Site Settings & Mobile Money Numbers
You never need to edit code to update Kelvin's contact info or payment numbers:
1. In the admin sidebar, click **⚙️ Site Settings**.
2. Here you can edit:
   - **Branding:** Site Name, Header Tagline, About Summary.
   - **Contact Details:** Email address, Display Phone Number, WhatsApp Digits (`255677853595`), Office Address (`Makumbusho Plaza, 1st Floor, Dar es Salaam, Tanzania`).
   - **Mobile Money Payment Numbers:** M-Pesa number & Account Name, Tigo Pesa number & Account Name. Whenever you change these here, they automatically update across every book page and checkout modal on the website!
   - **Social Media Profiles:** Instagram (`@kelvinkibenje` with follower count text `499K+`), YouTube, Facebook, and Threads URLs.
3. Click **"Save All Settings"**.

---

## 5. Managing Images & Photos

All website images are stored cleanly under `public_html/images/` and `public_html/uploads/`:

### Key Image Slots
- **Hero Portrait (`/images/hero.jpg`):** Used in the Homepage Hero section. Pre-loaded with Kelvin's signature portrait in his blue blazer with crossed arms.
- **Author Avatar (`/images/avatar.jpg`):** Used in header favicon, login logo, author boxes, and about page badges.
- **About Page Photos (`/images/about.jpg`, `/images/about-2.jpg`, `/images/about-3.jpg`):** Used on the About page and Homepage snapshot. Pre-loaded with Kelvin's speaking photos from Vijana Uongozi Forum and Girls Roundtable.
- **Speaking & Corporate Photos (`/images/event-vodacom.jpg`, `/images/event-seminar.jpg`, `/images/speaker-1.jpg`, `speaker-2.jpg`, `speaker-3.jpg`):** Used on the Speaking page to showcase Kelvin training Vodacom M-Pesa agents in Temeke and delivering youth financial seminars.
- **Book Covers (`/uploads/books/`):**
  - `/uploads/books/china-to-tanzania.jpg`
  - `/uploads/books/elimu-ya-fedha.jpg`
  - `/uploads/books/m-wekeza.jpg`

> 💡 **How to Replace an Image:** You can either upload new photos directly inside the CMS Admin panel when editing books or blog posts, OR you can overwrite any photo in `/images/` via cPanel File Manager keeping the same filename (`hero.jpg`, `about.jpg`, etc.).

---

## 6. Troubleshooting & Shared Hosting FAQ

### Q: Why do I see "Database Setup Required" when I open my homepage?
**A:** This friendly error page appears when `config.php` has not yet been connected to your MySQL database. Complete **Step A, B, and C** above, and enter your cPanel database username and password in `config.php`.

### Q: Why are clean URLs like `/book/china-to-tanzania` giving a 404 error?
**A:** Ensure that the **`.htaccess`** file was uploaded to your `public_html/` folder. Also verify in your cPanel hosting settings that Apache's `mod_rewrite` is enabled (standard on 99% of cPanel hosts).

### Q: How do email notifications work for contact forms and book orders?
**A:** The website uses PHP's native `mail()` function with proper HTML MIME headers, which works out of the box on shared cPanel hosting. Inquiries and book order alerts are automatically emailed to the address configured under **⚙️ Site Settings** -> `contact_email` (`info@kelvinkibenje.com`).

### Q: Can I export my newsletter subscribers?
**A:** Yes! In the CMS admin panel, go to **✉️ Newsletter List** and click the gold **"📥 Export to CSV"** button. A file named `kelvin_subscribers_2026-XX-XX.csv` will download instantly for use in Mailchimp, SendGrid, or Excel.

---
*Built with excellence for Kelvin Kibenje Kenedy Kyaluoko — 2026.*
