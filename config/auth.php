<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Authentication & Authorization Guards
 */

require_once __DIR__ . '/config.php';

class Auth {
    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
    }

    public static function isStudent(): bool {
        return self::isLoggedIn() && ($_SESSION['user_role'] === 'student');
    }

    public static function isAdmin(): bool {
        return self::isLoggedIn() && in_array($_SESSION['user_role'], ['admin', 'superadmin', 'coordinator']);
    }

    public static function getCurrentUserId(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getCurrentUser(): ?array {
        if (!self::isLoggedIn()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? 'User',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'guest',
        ];
    }

    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            setFlash('danger', 'Please log in to access this page.');
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }
    }

    public static function requireStudent(): void {
        self::requireLogin();
        if (!self::isStudent()) {
            setFlash('danger', 'Access restricted. Student credentials required.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/dashboard');
            exit;
        }
    }

    public static function requireAdmin(): void {
        self::requireLogin();
        if (!self::isAdmin()) {
            setFlash('danger', 'Access denied. Administrator privileges required.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/dashboard');
            exit;
        }
    }

    public static function loginStudent(array $student): void {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$student['student_id'];
        $_SESSION['user_name'] = $student['name'];
        $_SESSION['user_email'] = $student['email'];
        $_SESSION['user_role'] = 'student';
        $_SESSION['student_course'] = $student['course'];
        $_SESSION['student_semester'] = (int)$student['semester'];
        $_SESSION['student_cgpa'] = (float)$student['cgpa'];
    }

    public static function loginAdmin(array $admin): void {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$admin['admin_id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['user_email'] = $admin['email'];
        $_SESSION['user_role'] = $admin['role'] ?? 'admin';
    }

    public static function logout(): void {
        $_SESSION = [];

        // Clear standard PHP session cookie across all path variations
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 86400,
                $params["path"] ?: '/', $params["domain"] ?? '',
                $params["secure"] ?? false, $params["httponly"] ?? true
            );
            setcookie(session_name(), '', time() - 86400, '/');
        }

        // Explicitly clear signed serverless session cookie
        $isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
            || (!empty($_SERVER['HTTP_HOST']) && (strpos((string)$_SERVER['HTTP_HOST'], '.vercel.app') !== false));

        setcookie('crms_session_data', '', [
            'expires' => time() - 86400,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        setcookie('crms_session_data', '', time() - 86400, '/');
        unset($_COOKIE['crms_session_data']);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
