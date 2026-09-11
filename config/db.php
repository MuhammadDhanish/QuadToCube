<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Database Connection Manager (PDO)
 */

class Database {
    private static ?PDO $instance = null;

    // Default configuration for standard XAMPP / MariaDB / MySQL
    private static string $host = 'localhost';
    private static string $dbName = 'crms_db';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            // Check for environment overrides
            $host = getenv('DB_HOST') ?: self::$host;
            $port = getenv('DB_PORT') ?: '3306';
            $dbName = getenv('DB_NAME') ?: self::$dbName;
            $username = getenv('DB_USER') ?: self::$username;
            $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : self::$password;

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=" . self::$charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            // Allow SSL for remote cloud MySQL providers (e.g., TiDB, Aiven)
            if (defined('Pdo\Mysql::ATTR_SSL_VERIFY_SERVER_CERT')) {
                $options[Pdo\Mysql::ATTR_SSL_VERIFY_SERVER_CERT] = false;
            } elseif (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            }

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
                // If connected successfully to an empty cloud DB, auto-populate tables
                try {
                    $check = self::$instance->query("SHOW TABLES LIKE 'admins'");
                    if ($check && $check->rowCount() === 0) {
                        self::autoMigrate(self::$instance);
                    }
                } catch (Exception $migrateErr) {
                    // Silent catch if inspection fails
                }
            } catch (PDOException $e) {
                // If database does not exist, attempt to connect to MySQL server directly and create database + tables
                if (strpos($e->getMessage(), "Unknown database") !== false) {
                    try {
                        $rootDsn = "mysql:host={$host};port={$port};charset=" . self::$charset;
                        $pdoRoot = new PDO($rootDsn, $username, $password, $options);
                        $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                        
                        // Reconnect to newly created DB
                        self::$instance = new PDO($dsn, $username, $password, $options);
                        self::autoMigrate(self::$instance);
                    } catch (Exception $innerEx) {
                        self::renderDbError($innerEx->getMessage());
                    }
                } else {
                    self::renderDbError($e->getMessage());
                }
            }
        }
        return self::$instance;
    }

    private static function autoMigrate(PDO $pdo): void {
        $schemaFile = __DIR__ . '/../database/schema.sql';
        $seedFile = __DIR__ . '/../database/seed.sql';

        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            $pdo->exec($sql);
        }
        if (file_exists($seedFile)) {
            $sql = file_get_contents($seedFile);
            $pdo->exec($sql);
        }
    }

    private static function renderDbError(string $errorMsg): void {
        http_response_code(500);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>CRMS - Database Connection Error</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; min-height: 100vh; }
                .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; }
            </style>
        </head>
        <body>
            <div class="container" style="max-width: 680px;">
                <div class="card shadow-lg p-4">
                    <h3 class="text-danger mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Database Connection Notice</h3>
                    <p class="text-secondary">Could not connect to MySQL server using credentials <code>root@localhost</code>.</p>
                    <div class="alert alert-dark border-secondary">
                        <small class="text-warning"><strong>Error Detail:</strong> <?= htmlspecialchars($errorMsg) ?></small>
                    </div>
                    <h5 class="text-light mt-3">Quick Setup in XAMPP:</h5>
                    <ol class="text-secondary small ps-3">
                        <li>Open <strong>XAMPP Control Panel</strong>.</li>
                        <li>Click <strong>Start</strong> next to <strong>Apache</strong> and <strong>MySQL</strong>.</li>
                        <li>Open <a href="http://localhost/phpmyadmin" target="_blank" class="text-info">http://localhost/phpmyadmin</a> in your browser.</li>
                        <li>Import <code>database/schema.sql</code> and <code>database/seed.sql</code>, or create a database named <code>crms_db</code>.</li>
                        <li>Refresh this page.</li>
                    </ol>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
