<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Job Model & Smart Eligibility Engine
 */

require_once __DIR__ . '/../../config/db.php';

class Job {
    public static function getAll(array $filters = []): array {
        $pdo = Database::getConnection();

        $sql = "
            SELECT j.*, c.company_name, c.industry, c.location AS company_location,
                   COUNT(a.application_id) AS total_applicants,
                   SUM(CASE WHEN a.status = 'selected' THEN 1 ELSE 0 END) AS selected_count
            FROM `jobs` j
            INNER JOIN `companies` c ON j.company_id = c.company_id
            LEFT JOIN `applications` a ON j.job_id = a.job_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (j.title LIKE ? OR c.company_name LIKE ? OR j.required_skills LIKE ?)";
            $term = "%{$filters['search']}%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        if (!empty($filters['company_id'])) {
            $sql .= " AND j.company_id = ?";
            $params[] = (int)$filters['company_id'];
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND j.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['active_only'])) {
            $sql .= " AND j.status = 'open' AND j.deadline >= NOW()";
        }

        $sql .= " GROUP BY j.job_id ORDER BY j.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById(int $jobId): ?array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT j.*, c.company_name, c.contact_person, c.contact_email, c.contact_phone,
                   c.website, c.industry, c.location AS company_location, c.description AS company_desc,
                   COUNT(a.application_id) AS total_applicants
            FROM `jobs` j
            INNER JOIN `companies` c ON j.company_id = c.company_id
            LEFT JOIN `applications` a ON j.job_id = a.job_id
            WHERE j.job_id = ?
            GROUP BY j.job_id
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$jobId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO `jobs` (`company_id`, `title`, `description`, `job_type`, `salary_package`, `location`, `min_cgpa`, `allowed_courses`, `allowed_semesters`, `required_skills`, `deadline`, `status`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            (int)$data['company_id'],
            $data['title'],
            $data['description'],
            $data['job_type'] ?? 'Full-time',
            $data['salary_package'] ?? 'Best in Industry',
            $data['location'] ?? 'On-site',
            (float)$data['min_cgpa'],
            $data['allowed_courses'] ?? 'All',
            $data['allowed_semesters'] ?? 'All',
            $data['required_skills'] ?? '',
            $data['deadline'],
            $data['status'] ?? 'open'
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $jobId, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            UPDATE `jobs`
            SET `company_id` = ?, `title` = ?, `description` = ?, `job_type` = ?,
                `salary_package` = ?, `location` = ?, `min_cgpa` = ?, `allowed_courses` = ?,
                `allowed_semesters` = ?, `required_skills` = ?, `deadline` = ?, `status` = ?
            WHERE `job_id` = ?
        ");
        return $stmt->execute([
            (int)$data['company_id'],
            $data['title'],
            $data['description'],
            $data['job_type'] ?? 'Full-time',
            $data['salary_package'] ?? 'Best in Industry',
            $data['location'] ?? 'On-site',
            (float)$data['min_cgpa'],
            $data['allowed_courses'] ?? 'All',
            $data['allowed_semesters'] ?? 'All',
            $data['required_skills'] ?? '',
            $data['deadline'],
            $data['status'] ?? 'open',
            $jobId
        ]);
    }

    public static function delete(int $jobId): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM `jobs` WHERE `job_id` = ?");
        return $stmt->execute([$jobId]);
    }

    public static function countActive(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM `jobs` WHERE `status` = 'open' AND `deadline` >= NOW()")->fetchColumn();
    }

    public static function countAll(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM `jobs`")->fetchColumn();
    }

    /**
     * Smart Eligibility Checking Engine
     * Evaluates student qualifications against job requirements
     */
    public static function checkEligibility(array $job, array $student): array {
        $isEligible = true;
        $reasons = [];
        $checks = [];

        // 1. CGPA Criterion
        $minCgpa = (float)$job['min_cgpa'];
        $studentCgpa = (float)($student['cgpa'] ?? 0);
        $cgpaPassed = ($studentCgpa >= $minCgpa);
        $checks['cgpa'] = [
            'label' => 'Minimum CGPA',
            'required' => number_format($minCgpa, 2),
            'student' => number_format($studentCgpa, 2),
            'passed' => $cgpaPassed
        ];
        if (!$cgpaPassed) {
            $isEligible = false;
            $reasons[] = "CGPA {$studentCgpa} is below the required minimum of {$minCgpa}.";
        }

        // 2. Allowed Courses Criterion
        $allowedCoursesRaw = trim($job['allowed_courses'] ?? 'All');
        $studentCourse = trim($student['course'] ?? '');
        $coursePassed = false;

        if ($allowedCoursesRaw === 'All' || empty($allowedCoursesRaw)) {
            $coursePassed = true;
        } else {
            $allowedList = array_map('trim', explode(',', $allowedCoursesRaw));
            foreach ($allowedList as $allowed) {
                if (strcasecmp($allowed, $studentCourse) === 0) {
                    $coursePassed = true;
                    break;
                }
            }
        }
        $checks['course'] = [
            'label' => 'Eligible Courses',
            'required' => $allowedCoursesRaw,
            'student' => $studentCourse,
            'passed' => $coursePassed
        ];
        if (!$coursePassed) {
            $isEligible = false;
            $reasons[] = "Course '{$studentCourse}' is not eligible for this drive. Allowed: {$allowedCoursesRaw}.";
        }

        // 3. Allowed Semesters Criterion
        $allowedSemsRaw = trim($job['allowed_semesters'] ?? 'All');
        $studentSem = (int)($student['semester'] ?? 0);
        $semPassed = false;

        if ($allowedSemsRaw === 'All' || empty($allowedSemsRaw)) {
            $semPassed = true;
        } else {
            $allowedSemList = array_map('intval', explode(',', $allowedSemsRaw));
            $semPassed = in_array($studentSem, $allowedSemList, true);
        }
        $checks['semester'] = [
            'label' => 'Eligible Semesters',
            'required' => $allowedSemsRaw,
            'student' => "Sem {$studentSem}",
            'passed' => $semPassed
        ];
        if (!$semPassed) {
            $isEligible = false;
            $reasons[] = "Semester {$studentSem} is not eligible. Allowed: Semester {$allowedSemsRaw}.";
        }

        // 4. Application Deadline
        $deadlineTime = strtotime($job['deadline']);
        $isExpired = ($deadlineTime < time());
        $isClosed = ($job['status'] !== 'open' || $isExpired);
        $checks['deadline'] = [
            'label' => 'Drive Status & Deadline',
            'required' => date('d M Y, h:i A', $deadlineTime),
            'student' => $isClosed ? 'Expired / Closed' : 'Active',
            'passed' => !$isClosed
        ];
        if ($isClosed) {
            $isEligible = false;
            $reasons[] = "This placement drive has ended or is currently closed.";
        }

        // 5. Skill Match Analysis
        $requiredSkills = array_filter(array_map('trim', explode(',', $job['required_skills'] ?? '')));
        $studentSkills = array_filter(array_map('trim', explode(',', $student['skills'] ?? '')));
        
        $matchedSkills = [];
        if (!empty($requiredSkills)) {
            $studentSkillsLower = array_map('strtolower', $studentSkills);
            foreach ($requiredSkills as $req) {
                if (in_array(strtolower($req), $studentSkillsLower)) {
                    $matchedSkills[] = $req;
                }
            }
            $matchPct = round((count($matchedSkills) / count($requiredSkills)) * 100);
        } else {
            $matchPct = 100;
        }

        $checks['skills'] = [
            'label' => 'Skill Match',
            'required' => implode(', ', $requiredSkills) ?: 'None required',
            'matched' => implode(', ', $matchedSkills) ?: 'None',
            'percentage' => $matchPct,
            'passed' => true // Recommended skills do not strictly block eligibility unless desired
        ];

        return [
            'is_eligible' => $isEligible,
            'reasons' => $reasons,
            'checks' => $checks,
            'skill_match_pct' => $matchPct,
            'is_closed' => $isClosed
        ];
    }
}
