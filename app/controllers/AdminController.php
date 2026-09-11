<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Placement Administrator & Coordinator Controller
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Report.php';

class AdminController {
    public static function dashboard(): void {
        Auth::requireAdmin();

        $summary = Report::getDashboardSummary();
        $applicationsPerJob = Report::getApplicationsPerJob();
        $branchStats = Report::getBranchPlacementStats();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public static function students(): void {
        Auth::requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $course = trim($_GET['course'] ?? '');
        $minCgpa = trim($_GET['min_cgpa'] ?? '');

        $students = Student::getAll($search, $course, $minCgpa);
        $courses = Student::getDistinctCourses();

        require_once __DIR__ . '/../views/admin/students.php';
    }

    public static function companies(): void {
        Auth::requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $companies = Company::getAll($search);

        require_once __DIR__ . '/../views/admin/companies.php';
    }

    public static function saveCompany(): void {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation failed.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
            exit;
        }

        $companyId = (int)($_POST['company_id'] ?? 0);
        $name = trim($_POST['company_name'] ?? '');

        if (empty($name)) {
            setFlash('danger', 'Company Name is required.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
            exit;
        }

        $data = [
            'company_name' => $name,
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'contact_email' => trim($_POST['contact_email'] ?? ''),
            'contact_phone' => trim($_POST['contact_phone'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'industry' => trim($_POST['industry'] ?? 'Information Technology'),
            'location' => trim($_POST['location'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];

        if ($companyId > 0) {
            Company::update($companyId, $data);
            setFlash('success', 'Company details updated successfully.');
        } else {
            Company::create($data);
            setFlash('success', 'New recruiting partner company added successfully.');
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
        exit;
    }

    public static function deleteCompany(): void {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation failed.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
            exit;
        }

        $companyId = (int)($_POST['company_id'] ?? 0);
        Company::delete($companyId);
        setFlash('success', 'Company and associated drives removed successfully.');
        header('Location: ' . BASE_URL . '/public/index.php?page=admin/companies');
        exit;
    }

    public static function jobs(): void {
        Auth::requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? 'all';
        $companyId = (int)($_GET['company_id'] ?? 0);

        $jobs = Job::getAll([
            'search' => $search,
            'status' => $status,
            'company_id' => $companyId ?: null
        ]);
        $companies = Company::getAll();

        require_once __DIR__ . '/../views/admin/jobs.php';
    }

    public static function jobForm(): void {
        Auth::requireAdmin();

        $jobId = (int)($_GET['id'] ?? 0);
        $job = $jobId > 0 ? Job::getById($jobId) : null;
        $companies = Company::getAll();

        require_once __DIR__ . '/../views/admin/job_form.php';
    }

    public static function saveJob(): void {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/jobs');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation error.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/jobs');
            exit;
        }

        $jobId = (int)($_POST['job_id'] ?? 0);
        $companyId = (int)($_POST['company_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $jobType = $_POST['job_type'] ?? 'Full-time';
        $salaryPackage = trim($_POST['salary_package'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $minCgpa = (float)($_POST['min_cgpa'] ?? 6.00);

        // Process allowed courses (array or text)
        $allowedCourses = $_POST['allowed_courses'] ?? 'All';
        if (is_array($allowedCourses)) {
            $allowedCourses = implode(',', array_map('trim', $allowedCourses));
        }

        $allowedSemesters = trim($_POST['allowed_semesters'] ?? 'All');
        $requiredSkills = trim($_POST['required_skills'] ?? '');
        $deadline = $_POST['deadline'] ?? date('Y-m-d H:i:s', strtotime('+30 days'));
        $status = $_POST['status'] ?? 'open';

        if (empty($title) || empty($companyId) || empty($deadline)) {
            setFlash('danger', 'Job Title, Company, and Deadline are mandatory fields.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/job-form' . ($jobId > 0 ? '&id='.$jobId : ''));
            exit;
        }

        $data = [
            'company_id' => $companyId,
            'title' => $title,
            'description' => $description,
            'job_type' => $jobType,
            'salary_package' => $salaryPackage,
            'location' => $location,
            'min_cgpa' => $minCgpa,
            'allowed_courses' => $allowedCourses,
            'allowed_semesters' => $allowedSemesters,
            'required_skills' => $requiredSkills,
            'deadline' => date('Y-m-d H:i:s', strtotime($deadline)),
            'status' => $status
        ];

        if ($jobId > 0) {
            Job::update($jobId, $data);
            setFlash('success', 'Placement opportunity updated successfully.');
        } else {
            Job::create($data);
            setFlash('success', 'New placement opportunity created successfully.');
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin/jobs');
        exit;
    }

    public static function deleteJob(): void {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/jobs');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation failed.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/jobs');
            exit;
        }

        $jobId = (int)($_POST['job_id'] ?? 0);
        Job::delete($jobId);
        setFlash('success', 'Job drive and applicant records removed.');
        header('Location: ' . BASE_URL . '/public/index.php?page=admin/jobs');
        exit;
    }

    public static function applications(): void {
        Auth::requireAdmin();

        $jobId = (int)($_GET['job_id'] ?? 0);
        $statusFilter = $_GET['status'] ?? 'all';
        $search = trim($_GET['search'] ?? '');

        $job = null;
        if ($jobId > 0) {
            $job = Job::getById($jobId);
        }

        $allJobs = Job::getAll();
        if (!$job && !empty($allJobs)) {
            $job = $allJobs[0];
            $jobId = (int)$job['job_id'];
        }

        $applications = $jobId > 0 ? Application::getByJob($jobId, $statusFilter, $search) : [];

        require_once __DIR__ . '/../views/admin/applications.php';
    }

    public static function updateApplicationStatus(): void {
        Auth::requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/applications');
            exit;
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('danger', 'CSRF validation error.');
            header('Location: ' . BASE_URL . '/public/index.php?page=admin/applications');
            exit;
        }

        $applicationId = (int)($_POST['application_id'] ?? 0);
        $jobId = (int)($_POST['job_id'] ?? 0);
        $status = $_POST['status'] ?? 'applied';
        $notes = trim($_POST['notes'] ?? '');
        $offerRef = trim($_POST['offer_letter_ref'] ?? '');

        try {
            Application::updateStatus($applicationId, $status, $notes, $offerRef);
            setFlash('success', 'Candidate status updated to ' . strtoupper(str_replace('_', ' ', $status)) . '.');
        } catch (Exception $e) {
            setFlash('danger', 'Error updating status: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/public/index.php?page=admin/applications&job_id=' . $jobId);
        exit;
    }

    public static function reports(): void {
        Auth::requireAdmin();

        $summary = Report::getDashboardSummary();
        $branchStats = Report::getBranchPlacementStats();
        $companyReport = Report::getCompanyWiseReport();
        $placedStudents = Report::getPlacedStudentsList();

        require_once __DIR__ . '/../views/admin/reports.php';
    }

    public static function exportCsv(): void {
        Auth::requireAdmin();

        $type = $_GET['type'] ?? 'placed_students';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=CRMS_' . $type . '_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');

        if ($type === 'placed_students') {
            fputcsv($output, ['Student Name', 'Email', 'Course', 'CGPA', 'Company Name', 'Job Role', 'Salary Package', 'Offer Reference', 'Remarks', 'Placed Date']);
            $list = Report::getPlacedStudentsList();
            foreach ($list as $row) {
                fputcsv($output, [
                    $row['student_name'],
                    $row['student_email'],
                    $row['course'],
                    $row['cgpa'],
                    $row['company_name'],
                    $row['job_title'],
                    $row['salary_package'],
                    $row['offer_letter_ref'] ?? 'N/A',
                    $row['remarks'] ?? '',
                    $row['selection_date']
                ]);
            }
        } elseif ($type === 'companies') {
            fputcsv($output, ['Company ID', 'Company Name', 'Industry', 'Location', 'Total Drives Posted', 'Total Applicants', 'Total Hired']);
            $list = Report::getCompanyWiseReport();
            foreach ($list as $row) {
                fputcsv($output, [
                    $row['company_id'],
                    $row['company_name'],
                    $row['industry'],
                    $row['location'],
                    $row['total_jobs'],
                    $row['total_applicants'],
                    $row['total_hired']
                ]);
            }
        }
        fclose($output);
        exit;
    }
}
