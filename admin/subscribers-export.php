<?php
/**
 * ==============================================================================
 * CMS NEWSLETTER CSV EXPORTER (admin/subscribers-export.php)
 * ==============================================================================
 */

require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=kelvin_subscribers_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Email', 'Name', 'Status', 'Created At']);

$subs = db_fetch_all("SELECT id, email, name, status, created_at FROM subscribers ORDER BY created_at DESC");
foreach ($subs as $s) {
    fputcsv($output, [
        $s['id'],
        $s['email'],
        $s['name'],
        $s['status'],
        $s['created_at']
    ]);
}

fclose($output);
exit;
