<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Application Model
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/Selection.php';

class Application {
    public static function apply(int $jobId, int $studentId, ?string $notes = null): int {
        $pdo = Database::getConnection();

        // Guard against duplicate application
        if (self::hasApplied($jobId, $studentId)) {
            throw new Exception("You have already applied for this job opportunity.");
        }

        $stmt = $pdo->prepare("
            INSERT INTO `applications` (`job_id`, `student_id`, `applied_date`, `status`, `notes`)
            VALUES (?, ?, NOW(), 'applied', ?)
        ");
        $stmt->execute([$jobId, $studentId, $notes]);
        return (int)$pdo->lastInsertId();
    }

    public static function hasApplied(int $jobId, int $studentId): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM `applications` WHERE `job_id` = ? AND `student_id` = ? LIMIT 1");
        $stmt->execute([$jobId, $studentId]);
        return (bool)$stmt->fetchColumn();
    }

    public static function getStudentApplication(int $jobId, int $studentId): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `applications` WHERE `job_id` = ? AND `student_id` = ? LIMIT 1");
        $stmt->execute([$jobId, $studentId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function getByStudent(int $studentId): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT a.*, 
                   j.title AS job_title, j.salary_package, j.location AS job_location, j.job_type, j.deadline, j.status AS job_status,
                   c.company_name, c.industry, c.website,
                   s.result AS selection_result, s.offer_letter_ref, s.remarks AS selection_remarks
            FROM `applications` a
            INNER JOIN `jobs` j ON a.job_id = j.job_id
            INNER JOIN `companies` c ON j.company_id = c.company_id
            LEFT JOIN `selections` s ON a.application_id = s.application_id
            WHERE a.student_id = ?
            ORDER BY a.applied_date DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public static function getByJob(int $jobId, string $statusFilter = '', string $search = ''): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT a.*,
                   st.name AS student_name, st.email AS student_email, st.phone AS student_phone,
                   st.course, st.semester, st.cgpa, st.skills, st.resume_link,
                   s.selection_id, s.result AS selection_result, s.offer_letter_ref, s.remarks AS selection_remarks
            FROM `applications` a
            INNER JOIN `students` st ON a.student_id = st.student_id
            LEFT JOIN `selections` s ON a.application_id = s.application_id
            WHERE a.job_id = ?
        ";
        $params = [$jobId];

        if (!empty($statusFilter) && $statusFilter !== 'all') {
            $sql .= " AND a.status = ?";
            $params[] = $statusFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (st.name LIKE ? OR st.email LIKE ? OR st.skills LIKE ?)";
            $term = "%{$search}%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY st.cgpa DESC, a.applied_date ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById(int $applicationId): ?array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT a.*, 
                   j.title AS job_title, j.company_id, j.salary_package,
                   c.company_name,
                   st.name AS student_name, st.email AS student_email, st.course, st.cgpa,
                   s.selection_id, s.result AS selection_result, s.offer_letter_ref, s.remarks AS selection_remarks
            FROM `applications` a
            INNER JOIN `jobs` j ON a.job_id = j.job_id
            INNER JOIN `companies` c ON j.company_id = c.company_id
            INNER JOIN `students` st ON a.student_id = st.student_id
            LEFT JOIN `selections` s ON a.application_id = s.application_id
            WHERE a.application_id = ?
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$applicationId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function updateStatus(int $applicationId, string $status, ?string $notes = null, ?string $offerRef = null): bool {
        $pdo = Database::getConnection();

        $allowedStatuses = ['applied', 'under_review', 'shortlisted', 'selected', 'rejected'];
        if (!in_array($status, $allowedStatuses, true)) {
            throw new InvalidArgumentException("Invalid application status.");
        }

        $stmt = $pdo->prepare("UPDATE `applications` SET `status` = ?, `notes` = COALESCE(?, `notes`) WHERE `application_id` = ?");
        $updated = $stmt->execute([$status, $notes, $applicationId]);

        // Sync with selections table when marked selected or rejected
        if ($updated && in_array($status, ['selected', 'rejected'], true)) {
            Selection::recordResult($applicationId, $status, $offerRef, $notes);
        }

        return $updated;
    }

    public static function withdraw(int $applicationId, int $studentId): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            DELETE FROM `applications` 
            WHERE `application_id` = ? AND `student_id` = ? AND `status` = 'applied'
        ");
        return $stmt->execute([$applicationId, $studentId]);
    }

    public static function countAll(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM `applications`")->fetchColumn();
    }

    public static function countPlaced(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(DISTINCT student_id) FROM `applications` WHERE `status` = 'selected'")->fetchColumn();
    }
}
