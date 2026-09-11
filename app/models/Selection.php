<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Selection Model
 */

require_once __DIR__ . '/../../config/db.php';

class Selection {
    public static function recordResult(int $applicationId, string $status, ?string $offerLetterRef = null, ?string $remarks = null): bool {
        $pdo = Database::getConnection();

        // Check if selection record already exists
        $stmt = $pdo->prepare("SELECT `selection_id` FROM `selections` WHERE `application_id` = ? LIMIT 1");
        $stmt->execute([$applicationId]);
        $existing = $stmt->fetchColumn();

        if ($existing) {
            $stmtUpdate = $pdo->prepare("
                UPDATE `selections`
                SET `result` = ?, `offer_letter_ref` = COALESCE(?, `offer_letter_ref`), `remarks` = COALESCE(?, `remarks`)
                WHERE `selection_id` = ?
            ");
            return $stmtUpdate->execute([$status, $offerLetterRef, $remarks, $existing]);
        } else {
            $stmtInsert = $pdo->prepare("
                INSERT INTO `selections` (`application_id`, `result`, `offer_letter_ref`, `remarks`)
                VALUES (?, ?, ?, ?)
            ");
            return $stmtInsert->execute([$applicationId, $status, $offerLetterRef, $remarks]);
        }
    }

    public static function getByApplication(int $applicationId): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM `selections` WHERE `application_id` = ? LIMIT 1");
        $stmt->execute([$applicationId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }
}
