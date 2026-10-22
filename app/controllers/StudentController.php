<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Student Controller
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';

class StudentController {
    public static function dashboard(): void {
        Auth::requireStudent();
        $studentId = Auth::getCurrentUserId();
        $student = Student::getById($studentId);

        if (!$student) {
            Auth::logout();
            header('Location: ' . BASE_URL . '/public/index.php?page=login');
            exit;
        }

        // Get student applications
        $applications = Application::getByStudent($studentId);
        $totalApplied = count($applications);
        $shortlistedCount = 0;
        $selectedCount = 0;

        foreach ($applications as $app) {
            if ($app['status'] === 'shortlisted') $shortlistedCount++;
            if ($app['status'] === 'selected') $selectedCount++;
        }

        // Get recent active jobs and evaluate eligibility
        $allJobs = Job::getAll(['active_only' => true]);
        $eligibleJobs = [];
        $appliedJobIds = array_column($applications, 'job_id');

        foreach ($allJobs as &$job) {
            $job['eligibility'] = Job::checkEligibility($job, $student);
            $job['has_applied'] = in_array($job['job_id'], $appliedJobIds);
            if ($job['eligibility']['is_eligible'] && !$job['has_applied']) {
                $eligibleJobs[] = $job;
            }
        }
        unset($job);

        require_once __DIR__ . '/../views/student/dashboard.php';
    }

    public static function profile(): void {
        Auth::requireStudent();
        $studentId = Auth::getCurrentUserId();
        $student = Student::getById($studentId);

        require_once __DIR__ . '/../views/student/profile.php';
    }

    public static function updateProfile(): void {
        Auth::requireStudent();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=student/profile');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF token mismatch. Please try again.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/profile');
            exit;
        }

        $studentId = Auth::getCurrentUserId();

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $semester = (int)($_POST['semester'] ?? 1);
        $cgpa = (float)($_POST['cgpa'] ?? 0);
        $skills = trim($_POST['skills'] ?? '');
        $resumeLink = trim($_POST['resume_link'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($course)) {
            setFlash('danger', 'Name and Course are mandatory fields.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/profile');
            exit;
        }

        if ($cgpa < 0 || $cgpa > 10) {
            setFlash('danger', 'CGPA must be between 0.00 and 10.00.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/profile');
            exit;
        }

        Student::updateProfile($studentId, [
            'name' => $name,
            'phone' => $phone,
            'course' => $course,
            'semester' => $semester,
            'cgpa' => $cgpa,
            'skills' => $skills,
            'resume_link' => $resumeLink,
            'password' => $password
        ]);

        // Update session cache
        $_SESSION['user_name'] = $name;
        $_SESSION['student_course'] = $course;
        $_SESSION['student_semester'] = $semester;
        $_SESSION['student_cgpa'] = $cgpa;

        setFlash('success', 'Your student profile has been updated successfully.');
        header('Location: ' . BASE_URL . '/public/index.php?page=student/profile');
        exit;
    }

    public static function jobs(): void {
        Auth::requireStudent();
        $studentId = Auth::getCurrentUserId();
        $student = Student::getById($studentId);

        $search = trim($_GET['search'] ?? '');
        $filterEligibility = $_GET['filter'] ?? 'all'; // 'all', 'eligible_only'

        $jobs = Job::getAll([
            'search' => $search,
            'active_only' => true
        ]);

        $appliedList = Application::getByStudent($studentId);
        $appliedJobMap = [];
        foreach ($appliedList as $app) {
            $appliedJobMap[$app['job_id']] = $app['status'];
        }

        $processedJobs = [];
        foreach ($jobs as $job) {
            $eligibility = Job::checkEligibility($job, $student);
            $hasApplied = isset($appliedJobMap[$job['job_id']]);
            $appStatus = $appliedJobMap[$job['job_id']] ?? null;

            if ($filterEligibility === 'eligible_only' && !$eligibility['is_eligible']) {
                continue;
            }

            $job['eligibility'] = $eligibility;
            $job['has_applied'] = $hasApplied;
            $job['application_status'] = $appStatus;
            $processedJobs[] = $job;
        }

        require_once __DIR__ . '/../views/student/jobs.php';
    }

    public static function jobDetail(): void {
        Auth::requireStudent();
        $jobId = (int)($_GET['id'] ?? 0);
        $studentId = Auth::getCurrentUserId();

        $job = Job::getById($jobId);
        if (!$job) {
            setFlash('danger', 'Opportunity not found or inactive.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/jobs');
            exit;
        }

        $student = Student::getById($studentId);
        $eligibility = Job::checkEligibility($job, $student);
        $application = Application::getStudentApplication($jobId, $studentId);

        require_once __DIR__ . '/../views/student/job_detail.php';
    }

    public static function apply(): void {
        Auth::requireStudent();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=student/jobs');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation failed.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/jobs');
            exit;
        }

        $jobId = (int)($_POST['job_id'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');
        $studentId = Auth::getCurrentUserId();

        $job = Job::getById($jobId);
        if (!$job) {
            setFlash('danger', 'Opportunity not found.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/jobs');
            exit;
        }

        $student = Student::getById($studentId);
        $eligibility = Job::checkEligibility($job, $student);

        if (!$eligibility['is_eligible']) {
            setFlash('danger', 'You do not satisfy the eligibility requirements for this job: ' . implode(' ', $eligibility['reasons']));
            header('Location: ' . BASE_URL . '/public/index.php?page=student/job-detail&id=' . $jobId);
            exit;
        }

        try {
            Application::apply($jobId, $studentId, $notes);
            setFlash('success', 'Application submitted successfully for ' . htmlspecialchars($job['title']) . '! Track your progress under My Applications.');
            header('Location: ' . BASE_URL . '/public/index.php?page=student/applications');
            exit;
        } catch (Exception $e) {
            setFlash('warning', $e->getMessage());
            header('Location: ' . BASE_URL . '/public/index.php?page=student/job-detail&id=' . $jobId);
            exit;
        }
    }
}
