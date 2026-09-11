<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Analytics & Reporting Model
 */

require_once __DIR__ . '/../../config/db.php';

class Report {
    public static function getDashboardSummary(): array {
        $pdo = Database::getConnection();

        $totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM `students`")->fetchColumn();
        $totalCompanies = (int)$pdo->query("SELECT COUNT(*) FROM `companies`")->fetchColumn();
        $activeJobs = (int)$pdo->query("SELECT COUNT(*) FROM `jobs` WHERE `status` = 'open' AND `deadline` >= NOW()")->fetchColumn();
        $totalApplications = (int)$pdo->query("SELECT COUNT(*) FROM `applications`")->fetchColumn();
        $placedStudents = (int)$pdo->query("SELECT COUNT(DISTINCT student_id) FROM `applications` WHERE `status` = 'selected'")->fetchColumn();

        $placementRate = ($totalStudents > 0) ? round(($placedStudents / $totalStudents) * 100, 1) : 0;

        return [
            'total_students' => $totalStudents,
            'total_companies' => $totalCompanies,
            'active_jobs' => $activeJobs,
            'total_applications' => $totalApplications,
            'placed_students' => $placedStudents,
            'placement_rate' => $placementRate
        ];
    }

    public static function getApplicationsPerJob(): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT j.job_id, j.title, c.company_name,
                   COUNT(a.application_id) AS applicant_count,
                   SUM(CASE WHEN a.status = 'selected' THEN 1 ELSE 0 END) AS selected_count
            FROM `jobs` j
            INNER JOIN `companies` c ON j.company_id = c.company_id
            LEFT JOIN `applications` a ON j.job_id = a.job_id
            GROUP BY j.job_id
            ORDER BY applicant_count DESC, j.created_at DESC
            LIMIT 10
        ";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getBranchPlacementStats(): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT s.course,
                   COUNT(DISTINCT s.student_id) AS total_students,
                   COUNT(DISTINCT CASE WHEN a.status = 'selected' THEN s.student_id END) AS placed_count
            FROM `students` s
            LEFT JOIN `applications` a ON s.student_id = a.student_id
            GROUP BY s.course
            ORDER BY total_students DESC
        ";
        $results = $pdo->query($sql)->fetchAll();

        foreach ($results as &$r) {
            $total = (int)$r['total_students'];
            $placed = (int)$r['placed_count'];
            $r['rate'] = ($total > 0) ? round(($placed / $total) * 100, 1) : 0;
        }
        return $results;
    }

    public static function getCompanyWiseReport(): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT c.company_id, c.company_name, c.industry, c.location,
                   COUNT(DISTINCT j.job_id) AS total_jobs,
                   COUNT(a.application_id) AS total_applicants,
                   SUM(CASE WHEN a.status = 'selected' THEN 1 ELSE 0 END) AS total_hired
            FROM `companies` c
            LEFT JOIN `jobs` j ON c.company_id = j.company_id
            LEFT JOIN `applications` a ON j.job_id = a.job_id
            GROUP BY c.company_id
            ORDER BY total_hired DESC, c.company_name ASC
        ";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getPlacedStudentsList(): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT st.name AS student_name, st.email AS student_email, st.course, st.cgpa,
                   j.title AS job_title, j.salary_package,
                   c.company_name,
                   s.offer_letter_ref, s.remarks, a.updated_at AS selection_date
            FROM `applications` a
            INNER JOIN `students` st ON a.student_id = st.student_id
            INNER JOIN `jobs` j ON a.job_id = j.job_id
            INNER JOIN `companies` c ON j.company_id = c.company_id
            INNER JOIN `selections` s ON a.application_id = s.application_id
            WHERE a.status = 'selected'
            ORDER BY a.updated_at DESC
        ";
        return $pdo->query($sql)->fetchAll();
    }
}
