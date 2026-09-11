<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Student Model
 */

require_once __DIR__ . '/../../config/db.php';

class Student {
    public static function getById(int $studentId): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `students` WHERE `student_id` = ? LIMIT 1");
        $stmt->execute([$studentId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function updateProfile(int $studentId, array $data): bool {
        $pdo = Database::getConnection();

        $fields = [
            'name = ?',
            'phone = ?',
            'course = ?',
            'semester = ?',
            'cgpa = ?',
            'skills = ?',
            'resume_link = ?'
        ];
        $params = [
            $data['name'],
            $data['phone'] ?? null,
            $data['course'],
            (int)$data['semester'],
            (float)$data['cgpa'],
            $data['skills'] ?? '',
            $data['resume_link'] ?? null
        ];

        // Update password if provided
        if (!empty($data['password'])) {
            $fields[] = 'password_hash = ?';
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $params[] = $studentId;
        $sql = "UPDATE `students` SET " . implode(', ', $fields) . " WHERE `student_id` = ?";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function getAll(string $search = '', string $course = '', string $minCgpa = ''): array {
        $pdo = Database::getConnection();

        $sql = "
            SELECT s.*, 
                   COUNT(a.application_id) AS total_applications,
                   SUM(CASE WHEN a.status = 'selected' THEN 1 ELSE 0 END) AS selected_count
            FROM `students` s
            LEFT JOIN `applications` a ON s.student_id = a.student_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (s.name LIKE ? OR s.email LIKE ? OR s.skills LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($course) && $course !== 'All') {
            $sql .= " AND s.course = ?";
            $params[] = $course;
        }

        if (!empty($minCgpa)) {
            $sql .= " AND s.cgpa >= ?";
            $params[] = (float)$minCgpa;
        }

        $sql .= " GROUP BY s.student_id ORDER BY s.cgpa DESC, s.name ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function countAll(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM `students`")->fetchColumn();
    }

    public static function getDistinctCourses(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT DISTINCT `course` FROM `students` WHERE `course` IS NOT NULL AND `course` != '' ORDER BY `course` ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
