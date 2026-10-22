<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Front Controller & Request Router
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/StudentController.php';
require_once __DIR__ . '/../app/controllers/ApplicationController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

$page = $_GET['page'] ?? null;
if (!$page) {
    $uriPath = trim((string)parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($uriPath === 'logout' || $uriPath === 'public/logout') {
        $page = 'logout';
    } elseif ($uriPath === 'login' || $uriPath === 'public/login') {
        $page = 'login';
    } elseif ($uriPath === 'register' || $uriPath === 'public/register') {
        $page = 'register';
    } else {
        $page = 'home';
    }
}

switch ($page) {
    // -----------------------------------------------------
    // Authentication & Public Routes
    // -----------------------------------------------------
    case 'home':
        if (Auth::isLoggedIn()) {
            if (Auth::isAdmin()) {
                header('Location: ' . BASE_URL . '/public/index.php?page=admin/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/public/index.php?page=student/dashboard');
            }
        } else {
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
        }
        exit;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            AuthController::handleLogin();
        } else {
            AuthController::showLogin();
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            AuthController::handleRegister();
        } else {
            AuthController::showRegister();
        }
        break;

    case 'logout':
        AuthController::logout();
        break;

    // -----------------------------------------------------
    // Student Routes
    // -----------------------------------------------------
    case 'student/dashboard':
        StudentController::dashboard();
        break;

    case 'student/profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            StudentController::updateProfile();
        } else {
            StudentController::profile();
        }
        break;

    case 'student/jobs':
        StudentController::jobs();
        break;

    case 'student/job-detail':
        StudentController::jobDetail();
        break;

    case 'student/apply':
        StudentController::apply();
        break;

    case 'student/applications':
        ApplicationController::index();
        break;

    case 'student/withdraw':
        ApplicationController::withdraw();
        break;

    // -----------------------------------------------------
    // Admin Routes
    // -----------------------------------------------------
    case 'admin/dashboard':
        AdminController::dashboard();
        break;

    case 'admin/students':
        AdminController::students();
        break;

    case 'admin/companies':
        AdminController::companies();
        break;

    case 'admin/company-save':
        AdminController::saveCompany();
        break;

    case 'admin/company-delete':
        AdminController::deleteCompany();
        break;

    case 'admin/jobs':
        AdminController::jobs();
        break;

    case 'admin/job-create':
    case 'admin/job-form':
        AdminController::jobForm();
        break;

    case 'admin/job-save':
        AdminController::saveJob();
        break;

    case 'admin/job-delete':
        AdminController::deleteJob();
        break;

    case 'admin/applications':
        AdminController::applications();
        break;

    case 'admin/application-update':
        AdminController::updateApplicationStatus();
        break;

    case 'admin/reports':
        AdminController::reports();
        break;

    case 'admin/export':
        AdminController::exportCsv();
        break;

    default:
        http_response_code(404);
        echo "<!DOCTYPE html><html><head><title>404 Not Found</title><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head><body class='bg-light text-center p-5'><div class='container py-5'><h1 class='display-1 text-danger fw-bold'>404</h1><h3 class='mb-3'>Page Not Found</h3><p class='text-muted'>The requested path does not exist in CRMS.</p><a href='" . BASE_URL . "/public/index.php' class='btn btn-primary'>Return Home</a></div></body></html>";
        break;
}
