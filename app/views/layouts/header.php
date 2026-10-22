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
        /* Liquid Glass Navigation Bar (Frosted Glassmorphism) */
        .navbar-crms {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(30, 41, 59, 0.88) 50%, rgba(15, 23, 42, 0.95) 100%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(200%) !important;
            backdrop-filter: blur(24px) saturate(200%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18) !important;
            box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.45), inset 0 1px 0 0 rgba(255, 255, 255, 0.2) !important;
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            z-index: 1030;
        }
        .navbar-crms .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #ffffff !important;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }
        .navbar-crms .brand-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%) !important;
            box-shadow: 0 0 18px rgba(99, 102, 241, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .navbar-crms .nav-link {
            color: #f1f5f9 !important;
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.5rem 1rem !important;
            margin: 0 0.15rem;
            border-radius: 9999px;
            border: 1px solid transparent;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
        }
        .navbar-crms .nav-link i {
            color: #93c5fd;
            font-size: 1.05rem;
            transition: color 0.25s ease, transform 0.25s ease;
        }
        .navbar-crms .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.14) !important;
            border-color: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25), inset 0 1px 1px rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-1px);
        }
        .navbar-crms .nav-link:hover i {
            color: #67e8f9;
            transform: scale(1.1);
        }
        .navbar-crms .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.5) 0%, rgba(6, 182, 212, 0.4) 100%) !important;
            border: 1px solid rgba(56, 189, 248, 0.5) !important;
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.35), inset 0 1px 1px rgba(255, 255, 255, 0.35) !important;
        }
        .navbar-crms .nav-link.active i {
            color: #38bdf8 !important;
        }
        .navbar-crms .user-glass-pill {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            padding: 0.35rem 0.85rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .navbar-crms .user-name {
            color: #ffffff !important;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }
        .navbar-crms .btn-logout-glass {
            background: rgba(239, 68, 68, 0.18) !important;
            border: 1px solid rgba(248, 113, 113, 0.45) !important;
            color: #fecaca !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.2s ease;
        }
        .navbar-crms .btn-logout-glass:hover {
            background: rgba(239, 68, 68, 0.45) !important;
            border-color: rgba(248, 113, 113, 0.8) !important;
            color: #ffffff !important;
            box-shadow: 0 0 16px rgba(239, 68, 68, 0.5) !important;
            transform: translateY(-1px);
        }
        .navbar-crms .navbar-toggler {
            background: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>

<!-- Main Top Navigation (Liquid Glass Aesthetic) -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-crms sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/public/index.php">
            <div class="brand-badge text-white rounded-3 p-2 me-2 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </div>
            <span class="text-white fw-bold">CRMS<span class="badge bg-primary-subtle text-info border border-info-subtle rounded-pill fw-medium fs-7 ms-2 d-none d-md-inline-block px-2 py-1">Placement Portal</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#crmsNavbar" aria-controls="crmsNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="crmsNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <?php if ($isStudent): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/dashboard">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/jobs' || $currentPage === 'student/job-detail' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/jobs">
                            <i class="bi bi-briefcase me-1"></i>Job Opportunities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/applications' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/applications">
                            <i class="bi bi-file-earmark-check me-1"></i>My Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'student/profile' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=student/profile">
                            <i class="bi bi-person-badge me-1"></i>My Profile
                        </a>
                    </li>
                <?php elseif ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/dashboard">
                            <i class="bi bi-grid-fill me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/students' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/students">
                            <i class="bi bi-people-fill me-1"></i>Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/companies' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/companies">
                            <i class="bi bi-building me-1"></i>Companies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentPage, 'admin/job') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/jobs">
                            <i class="bi bi-briefcase-fill me-1"></i>Placement Drives
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/applications' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/applications">
                            <i class="bi bi-check2-all me-1"></i>Applications Review
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'admin/reports' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=admin/reports">
                            <i class="bi bi-bar-chart-line-fill me-1"></i>Reports & Stats
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'login' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'register' ? 'active' : '' ?>" href="<?= BASE_URL ?>/public/index.php?page=register">
                            <i class="bi bi-person-plus me-1"></i>Student Registration
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if ($currentUser): ?>
                    <div class="user-glass-pill d-none d-sm-flex align-items-center gap-2 me-1">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; font-weight: 700; font-size: 0.85rem;">
                            <?= strtoupper(substr($currentUser['name'], 0, 1)) ?>
                        </div>
                        <div class="text-end">
                            <div class="user-name small lh-1 mb-1"><?= e($currentUser['name']) ?></div>
                            <span class="badge <?= $isAdmin ? 'bg-warning text-dark' : 'bg-info text-dark' ?> py-0 px-2" style="font-size: 0.68rem; font-weight: 600;">
                                <?= strtoupper($currentUser['role']) ?>
                                <?php if ($isStudent && isset($_SESSION['student_cgpa'])): ?>
                                    &bull; CGPA: <?= number_format((float)$_SESSION['student_cgpa'], 2) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/public/index.php?page=logout" class="btn btn-logout-glass btn-sm px-3 rounded-pill fw-medium d-inline-flex align-items-center">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=login&role=student" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                        Student Login
                    </a>
                    <a href="<?= BASE_URL ?>/public/index.php?page=login&role=admin" class="btn btn-primary btn-sm px-3 rounded-pill">
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
