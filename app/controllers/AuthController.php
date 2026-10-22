<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Authentication Controller
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public static function showLogin(): void {
        if (isset($_GET['logged_out'])) {
            Auth::logout();
            setFlash('info', 'You have been logged out successfully.');
        } elseif (Auth::isLoggedIn()) {
            if (Auth::isAdmin()) {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/public/index.php?page=student/dashboard');
            }
            exit;
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public static function handleLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'Security validation failed (CSRF token expired). Please try again.');
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleType = $_POST['role_type'] ?? 'student'; // 'student' or 'admin'

        if (empty($email) || empty($password)) {
            setFlash('danger', 'Please enter both email and password.');
            header('Location: ' . BASE_URL . '/public/index.php?page=login&role=' . urlencode($roleType));
            exit;
        }

        $user = User::authenticate($email, $password, $roleType);

        if ($user) {
            if ($roleType === 'admin') {
                Auth::loginAdmin($user);
                setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
                header('Location: ' . BASE_URL . '/public/index.php?page=admin/dashboard');
            } else {
                Auth::loginStudent($user);
                setFlash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
                header('Location: ' . BASE_URL . '/public/index.php?page=student/dashboard');
            }
            exit;
        } else {
            setFlash('danger', 'Invalid credentials or account type. Please verify email and password.');
            header('Location: ' . BASE_URL . '/public/index.php?page=login&role=' . urlencode($roleType));
            exit;
        }
    }

    public static function showRegister(): void {
        if (Auth::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/public/index.php?page=student/dashboard');
            exit;
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public static function handleRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=register');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'Security validation failed (CSRF token expired). Please try again.');
            header('Location: ' . BASE_URL . '/public/index.php?page=register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $course = trim($_POST['course'] ?? '');
        $semester = (int)($_POST['semester'] ?? 1);
        $cgpa = (float)($_POST['cgpa'] ?? 0);
        $skills = trim($_POST['skills'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        // Validation
        $errors = [];
        if (empty($name)) $errors[] = 'Full name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email address is required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters long.';
        if ($password !== $confirmPassword) $errors[] = 'Passwords do not match.';
        if (empty($course)) $errors[] = 'Degree / Course is required.';
        if ($semester < 1 || $semester > 8) $errors[] = 'Semester must be between 1 and 8.';
        if ($cgpa < 0 || $cgpa > 10) $errors[] = 'CGPA must be between 0.00 and 10.00.';

        if (User::emailExists($email, 'students')) {
            $errors[] = 'An account with this email address already exists. Please log in.';
        }

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            require_once __DIR__ . '/../views/auth/register.php';
            return;
        }

        try {
            $studentId = User::registerStudent([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'course' => $course,
                'semester' => $semester,
                'cgpa' => $cgpa,
                'skills' => $skills,
                'resume_link' => null
            ]);

            // Auto-login newly registered student
            $newStudent = [
                'student_id' => $studentId,
                'name' => $name,
                'email' => $email,
                'course' => $course,
                'semester' => $semester,
                'cgpa' => $cgpa
            ];
            Auth::loginStudent($newStudent);

            setFlash('success', 'Registration successful! Welcome to the Campus Recruitment Management System.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/dashboard');
            exit;
        } catch (Exception $e) {
            setFlash('danger', 'Registration error: ' . $e->getMessage());
            require_once __DIR__ . '/../views/auth/register.php';
        }
    }

    public static function logout(): void {
        Auth::logout();
        header('Location: ' . BASE_URL . '/public/index.php?page=login&logged_out=1');
        exit;
    }
}
