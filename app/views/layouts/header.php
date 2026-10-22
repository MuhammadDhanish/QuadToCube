<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Shared Header Layout
 */
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/auth.php';

$currentUser = Auth::getCurrentUser();
$isStudent = Auth::isStudent();
$isAdmin = Auth::isAdmin();
$currentPage = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?><?= APP_NAME ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CRMS Styles (dual fallbacks) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        /* Minimalist Liquid Glass Navbar */
        .navbar-crms {
            background: rgba(15, 23, 42, 0.88) !important;
            -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
            backdrop-filter: blur(16px) saturate(180%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25) !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            z-index: 1030;
        }
        .navbar-crms .brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #ffffff;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.35);
        }
        .navbar-crms .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.2rem;
            color: #ffffff !important;
            letter-spacing: -0.3px;
        }
        .navbar-crms .nav-link {
            color: #94a3b8 !important;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.4rem 0.85rem !important;
            margin: 0 0.1rem;
            border-radius: 8px;
            white-space: nowrap !important;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
        }
        .navbar-crms .nav-link i {
            font-size: 0.95rem;
            margin-right: 0.35rem;
            color: #64748b;
            transition: color 0.15s ease;
        }
        .navbar-crms .nav-link:hover {
            color: #f8fafc !important;
            background: rgba(255, 255, 255, 0.06) !important;
        }
        .navbar-crms .nav-link:hover i {
            color: #38bdf8;
        }
        .navbar-crms .nav-link.active {
            color: #38bdf8 !important;
            background: rgba(56, 189, 248, 0.1) !important;
            font-weight: 600;
        }
        .navbar-crms .nav-link.active i {
            color: #38bdf8 !important;
        }
        .navbar-crms .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #38bdf8;
            font-weight: 700;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .navbar-crms .btn-logout-minimal {
            color: #94a3b8;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 6px;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .navbar-crms .btn-logout-minimal:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
        }
        .navbar-crms .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<!-- Main Top Navigation (Minimalist Liquid Glass) -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-crms sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center me-3" href="<?= BASE_URL ?>/public/index.php">
            <div class="brand-icon me-2 d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span>CRMS</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#crmsNavbar" aria-controls="crmsNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="crmsNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-2">
                <?php if ($isStudent): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/dashboard">
                            <i class="bi bi-speedometer2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/jobs' || $currentPage === 'student/job-detail' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/jobs">
                            <i class="bi bi-briefcase"></i>Opportunities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/applications' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/applications">
                            <i class="bi bi-file-earmark-check"></i>Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/profile' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/profile">
                            <i class="bi bi-person"></i>Profile
                        </a>
                    </li>
                <?php elseif ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/dashboard">
                            <i class="bi bi-grid-fill"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/students' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/students">
                            <i class="bi bi-people-fill"></i>Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/companies' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/companies">
                            <i class="bi bi-building"></i>Companies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentPage, 'admin/job') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/jobs">
                            <i class="bi bi-briefcase-fill"></i>Drives
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/applications' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/applications">
                            <i class="bi bi-check2-all"></i>Review
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/reports' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/reports">
                            <i class="bi bi-bar-chart-line-fill"></i>Reports
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'login' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=login">
                            <i class="bi bi-box-arrow-in-right"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'register' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=register">
                            <i class="bi bi-person-plus"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <?php if ($currentUser): ?>
                    <div class="d-none d-sm-flex align-items-center gap-2">
                        <div class="user-avatar">
                            <?= strtoupper(substr($currentUser['name'], 0, 1)) ?>
                        </div>
                        <div class="lh-sm text-end">
                            <div class="text-white small fw-medium" style="font-size: 0.825rem;"><?= e($currentUser['name']) ?></div>
                            <div class="text-secondary" style="font-size: 0.7rem;">
                                <?= ucfirst($currentUser['role']) ?><?php if ($isStudent && isset($_SESSION['student_cgpa'])): ?> &bull; <?= number_format((float)$_SESSION['student_cgpa'], 2) ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/public/index.php?page=logout" class="btn-logout-minimal" title="Logout">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=login&role=student" class="btn btn-outline-light btn-sm px-3 rounded-2">
                        Student Login
                    </a>
                    <a href="<?= BASE_URL ?>/public/index.php?page=login&role=admin" class="btn btn-primary btn-sm px-3 rounded-2">
                        Admin Portal
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Alerts Notification Banner -->
<div class="container-fluid px-lg-4 mt-3">
    <?php foreach (getFlashes() as $flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi <?= $flash['type'] === 'success' ? 'bi-check-circle-fill' : ($flash['type'] === 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill') ?> fs-5 me-2"></i>
            <div><?= $flash['message'] ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
</div>

<main class="flex-grow-1 pb-5">
