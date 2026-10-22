<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Company Model
 */

require_once __DIR__ . '/../../config/db.php';

class Company {
    public static function getAll(string $search = ''): array {
        $pdo = Database::getConnection();

        $sql = "
            SELECT c.*, 
                   COUNT(j.job_id) AS total_jobs,
                   SUM(CASE WHEN j.status = 'open' AND j.deadline >= NOW() THEN 1 ELSE 0 END) AS active_jobs
            FROM `companies` c
            LEFT JOIN `jobs` j ON c.company_id = j.company_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (c.company_name LIKE ? OR c.industry LIKE ? OR c.location LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " GROUP BY c.company_id ORDER BY c.company_name ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById(int $companyId): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `companies` WHERE `company_id` = ? LIMIT 1");
        $stmt->execute([$companyId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO `companies` (`company_name`, `contact_person`, `contact_email`, `contact_phone`, `website`, `industry`, `location`, `description`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['company_name'],
            $data['contact_person'] ?? null,
            $data['contact_email'] ?? null,
            $data['contact_phone'] ?? null,
            $data['website'] ?? null,
            $data['industry'] ?? 'Information Technology',
            $data['location'] ?? null,
            $data['description'] ?? null
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $companyId, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            UPDATE `companies` 
            SET `company_name` = ?, `contact_person` = ?, `contact_email` = ?, `contact_phone` = ?,
                `website` = ?, `industry` = ?, `location` = ?, `description` = ?
            WHERE `company_id` = ?
        ");
        return $stmt->execute([
            $data['company_name'],
            $data['contact_person'] ?? null,
            $data['contact_email'] ?? null,
            $data['contact_phone'] ?? null,
            $data['website'] ?? null,
            $data['industry'] ?? 'Information Technology',
            $data['location'] ?? null,
            $data['description'] ?? null,
            $companyId
        ]);
    }

    public static function delete(int $companyId): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM `companies` WHERE `company_id` = ?");
        return $stmt->execute([$companyId]);
    }

    public static function countAll(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM `companies`")->fetchColumn();
    }
}
