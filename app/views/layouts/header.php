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
    <!-- Custom CRMS Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/style.css">
</head>
<body>

<!-- Main Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-crms sticky-top">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/public/index.php">
            <div class="bg-primary text-white rounded-3 p-2 me-2 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span>CRMS<span class="text-primary-emphasis fw-normal fs-6 ms-2 d-none d-md-inline text-secondary">Placement Portal</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#crmsNavbar" aria-controls="crmsNavbar" aria-expanded="false" aria-label="Toggle navigation">
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
                    <div class="text-light me-2 d-none d-sm-block text-end">
                        <div class="fw-semibold small"><?= e($currentUser['name']) ?></div>
                        <span class="badge <?= $isAdmin ? 'bg-warning text-dark' : 'bg-info text-dark' ?> py-1 px-2" style="font-size: 0.7rem;">
                            <?= strtoupper($currentUser['role']) ?>
                            <?php if ($isStudent && isset($_SESSION['student_cgpa'])): ?>
                                &bull; CGPA: <?= number_format((float)$_SESSION['student_cgpa'], 2) ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <a href="<?= BASE_URL ?>/public/index.php?page=logout" class="btn btn-outline-light btn-sm px-3 rounded-pill">
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
