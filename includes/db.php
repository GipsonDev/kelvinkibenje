<?php
/**
 * ==============================================================================
 * DATABASE CONNECTION & PREPARED STATEMENT WRAPPER (db.php)
 * Uses PDO for 100% protection against SQL Injection
 * ==============================================================================
 */

require_once __DIR__ . '/../config.php';

/**
 * Returns a singleton PDO database connection instance.
 *
 * @return PDO
 */
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error cleanly and display a user-friendly message without leaking credentials
            error_log("Database connection failure: " . $e->getMessage());
            die(generate_db_error_page());
        }
    }

    return $pdo;
}

/**
 * Executes a prepared statement and returns the PDOStatement object.
 *
 * @param string $sql
 * @param array $params
 * @return PDOStatement
 */
function db_query(string $sql, array $params = []): PDOStatement {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetches all matching rows from a prepared statement.
 *
 * @param string $sql
 * @param array $params
 * @return array
 */
function db_fetch_all(string $sql, array $params = []): array {
    return db_query($sql, $params)->fetchAll();
}

/**
 * Fetches a single row from a prepared statement, or false if not found.
 *
 * @param string $sql
 * @param array $params
 * @return mixed
 */
function db_fetch_one(string $sql, array $params = []) {
    return db_query($sql, $params)->fetch();
}

/**
 * Executes an INSERT, UPDATE, or DELETE query and returns true on success.
 *
 * @param string $sql
 * @param array $params
 * @return bool
 */
function db_execute(string $sql, array $params = []): bool {
    $stmt = getDB()->prepare($sql);
    return $stmt->execute($params);
}

/**
 * Returns the last inserted auto-increment ID.
 *
 * @return string
 */
function db_last_insert_id(): string {
    return getDB()->lastInsertId();
}

/**
 * Generates an elegant fallback error page when MySQL is not yet configured.
 */
function generate_db_error_page(): string {
    return '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Setup Required - Kelvin Kibenje Website</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0b1329; color: #ffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
            .container { background: #132247; border: 1px solid #d4af37; border-radius: 12px; padding: 40px; max-width: 600px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
            h1 { color: #d4af37; margin-top: 0; font-size: 24px; }
            p { line-height: 1.6; color: #cbd5e1; }
            .code { background: #080d1a; padding: 12px 16px; border-radius: 6px; font-family: monospace; color: #38bdf8; overflow-x: auto; margin: 16px 0; }
            .step { margin-bottom: 16px; }
            .badge { display: inline-block; background: #d4af37; color: #0b1329; font-weight: bold; padding: 2px 8px; border-radius: 4px; margin-right: 8px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>⚙️ Database Setup Required</h1>
            <p>Welcome to the <strong>Kelvin Kibenje Kenedy Kyaluoko</strong> Official Website! To launch the site, please complete the MySQL database connection in <strong>config.php</strong>:</p>
            <div class="step">
                <span class="badge">Step 1</span> Create a MySQL database and user in your cPanel dashboard.
            </div>
            <div class="step">
                <span class="badge">Step 2</span> Import the included <code>schema.sql</code> file into your database via <strong>phpMyAdmin</strong>.
            </div>
            <div class="step">
                <span class="badge">Step 3</span> Open <code>config.php</code> in your cPanel File Manager and update the credentials:
                <div class="code">
                    define(\'DB_HOST\', \'localhost\');<br>
                    define(\'DB_NAME\', \'your_database_name\');<br>
                    define(\'DB_USER\', \'your_database_user\');<br>
                    define(\'DB_PASS\', \'your_database_password\');
                </div>
            </div>
            <p style="font-size: 13px; color: #94a3b8; margin-top: 24px;">If you need assistance, please refer to <code>README.md</code> in the root folder for step-by-step screenshots and instructions.</p>
        </div>
    </body>
    </html>';
}
