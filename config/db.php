<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Database Connection Manager (PDO)
 */

class Database {
    private static ?PDO $instance = null;

    // Default configuration: Connected to live Aiven MySQL cloud instance
    private static string $host = 'crms-mysql-dhanishdhanishkk-e099.e.aivencloud.com';
    private static string $port = '24023';
    private static string $dbName = 'defaultdb';
    private static string $username = 'avnadmin';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            // Load local .env file if present (safe for local development)
            $envFile = __DIR__ . '/../.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || strpos($line, '#') === 0) continue;
                    $parts = explode('=', $line, 2);
                    if (count($parts) === 2) {
                        $k = trim($parts[0]);
                        $v = trim($parts[1]);
                        $_ENV[$k] = $v;
                        $_SERVER[$k] = $v;
                        putenv("{$k}={$v}");
                    }
                }
            }

            // Check for environment overrides (checks $_ENV, $_SERVER, and getenv)
            $host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? getenv('DB_HOST') ?: self::$host;
            $port = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? getenv('DB_PORT') ?: self::$port;
            $dbName = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? getenv('DB_NAME') ?: self::$dbName;
            $username = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? getenv('DB_USER') ?: self::$username;
            
            $envPass = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? getenv('DB_PASS');
            $password = ($envPass !== false && $envPass !== null && $envPass !== '') ? $envPass : self::$password;

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
                    <p class="text-secondary">Could not connect to MySQL server.</p>
                    <div class="alert alert-dark border-secondary">
                        <small class="text-warning"><strong>Error Detail:</strong> <?= htmlspecialchars($errorMsg) ?></small>
                    </div>
                    <?php if (!empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], '.vercel.app') !== false): ?>
                    <h5 class="text-light mt-3">Fix for Vercel:</h5>
                    <p class="text-secondary small">Vercel requires a cloud MySQL database. In your <strong>Vercel Project &gt; Settings &gt; Environment Variables</strong>, add:</p>
                    <ul class="text-secondary small ps-3">
                        <li><code>DB_HOST</code> (e.g. from TiDB Cloud or Aiven)</li>
                        <li><code>DB_USER</code></li>
                        <li><code>DB_PASS</code></li>
                        <li><code>DB_NAME</code></li>
                        <li><code>DB_PORT</code> (usually <code>4000</code> or <code>3306</code>)</li>
                    </ul>
                    <?php else: ?>
                    <h5 class="text-light mt-3">Quick Setup in XAMPP:</h5>
                    <ol class="text-secondary small ps-3">
                        <li>Open <strong>XAMPP Control Panel</strong>.</li>
                        <li>Click <strong>Start</strong> next to <strong>Apache</strong> and <strong>MySQL</strong>.</li>
                        <li>Open <a href="http://localhost/phpmyadmin" target="_blank" class="text-info">http://localhost/phpmyadmin</a> in your browser.</li>
                        <li>Import <code>database/schema.sql</code> and <code>database/seed.sql</code>, or create a database named <code>crms_db</code>.</li>
                        <li>Refresh this page.</li>
                    </ol>
                    <?php endif; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
