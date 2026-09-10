<?php
/**
 * ==============================================================================
 * CMS BOOK DELETER (admin/book-delete.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0 && verify_csrf($_GET['csrf_token'] ?? '')) {
    try {
        db_execute("DELETE FROM books WHERE id = ?", [$id]);
        header('Location: /admin/books.php?msg=deleted');
        exit;
    } catch (Exception $e) {
        // Log error
    }
}

header('Location: /admin/books.php');
exit;
