<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Application Controller (Student Perspective)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../models/Application.php';

class ApplicationController {
    public static function index(): void {
        Auth::requireStudent();
        $studentId = Auth::getCurrentUserId();

        $applications = Application::getByStudent($studentId);

        require_once __DIR__ . '/../views/student/applications.php';
    }

    public static function withdraw(): void {
        Auth::requireStudent();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=student/applications');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation error.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/applications');
            exit;
        }

        $appId = (int)($_POST['application_id'] ?? 0);
        $studentId = Auth::getCurrentUserId();

        $withdrawn = Application::withdraw($appId, $studentId);
        if ($withdrawn) {
            setFlash('info', 'Your application has been withdrawn.');
        } else {
            setFlash('danger', 'Unable to withdraw application. It may already be under review or shortlisted.');
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=student/applications');
        exit;
    }
}
