<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Global Configuration & Helper Utilities
 */

// Detect HTTPS (supports direct SSL, reverse proxies, Cloudflare, AWS, and Vercel)
$isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_SSL']) === 'on')
    || (!empty($_SERVER['HTTP_HOST']) && (strpos((string)$_SERVER['HTTP_HOST'], '.vercel.app') !== false || strpos((string)$_SERVER['HTTP_HOST'], '.now.sh') !== false));

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    // Secure session settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    if ($isHttps) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// Application Constants
define('APP_NAME', 'CRMS - Campus Recruitment Management System');
define('APP_VERSION', '1.0.0');
define('APP_INSTITUTION', 'National Institute of Technology & Engineering');

// Automatic BASE_URL detection (supports XAMPP, local PHP server, and Vercel serverless)
if (getenv('BASE_URL')) {
    define('BASE_URL', rtrim(getenv('BASE_URL'), '/'));
} else {
    $protocol = $isHttps ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    // Strip /public or /api path if running via front controller / serverless bridge
    if (substr($scriptDir, -7) === '/public') {
        $scriptDir = substr($scriptDir, 0, -7);
    }
    if ($scriptDir === '/api' || $scriptDir === '/') {
        $scriptDir = '';
    }
    $scriptDir = rtrim($scriptDir, '/');
    define('BASE_URL', $protocol . $host . $scriptDir);
}

// CSRF Token Management
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField(): string {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

// XSS Protection Helper
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Input Sanitizer
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return trim(strip_tags((string)$data));
}

// Flash Notifications System
function setFlash(string $type, string $message): void {
    $_SESSION['flash_messages'][] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

function getFlashes(): array {
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

// Status Badges & Formatting Helper
function getStatusBadge(string $status): string {
    switch (strtolower($status)) {
        case 'applied':
            return '<span class="badge bg-secondary"><i class="bi bi-clock me-1"></i>Applied</span>';
        case 'under_review':
            return '<span class="badge bg-info text-dark"><i class="bi bi-search me-1"></i>Under Review</span>';
        case 'shortlisted':
            return '<span class="badge bg-primary"><i class="bi bi-star-fill me-1"></i>Shortlisted</span>';
        case 'selected':
            return '<span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Selected / Placed</span>';
        case 'rejected':
            return '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Not Selected</span>';
        case 'open':
            return '<span class="badge bg-success"><i class="bi bi-record-circle me-1"></i>Open</span>';
        case 'closed':
            return '<span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i>Closed</span>';
        default:
            return '<span class="badge bg-secondary">' . e($status) . '</span>';
    }
}

// Format Currency / CTC Helper
function formatSalary(?string $val): string {
    if (!$val) return 'Undisclosed';
    return e($val);
}
