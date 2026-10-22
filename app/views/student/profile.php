<?php
$pageTitle = 'Edit Student Profile';
require_once __DIR__ . '/../layouts/header.php';

// Calculate completeness
$fieldsFilled = 0;
$totalCheck = 7;
if (!empty($student['name'])) $fieldsFilled++;
if (!empty($student['email'])) $fieldsFilled++;
if (!empty($student['course'])) $fieldsFilled++;
if (!empty($student['semester'])) $fieldsFilled++;
if (!empty($student['cgpa']) && (float)$student['cgpa'] > 0) $fieldsFilled++;
if (!empty($student['skills'])) $fieldsFilled++;
if (!empty($student['resume_link'])) $fieldsFilled++;
$completeness = round(($fieldsFilled / $totalCheck) * 100);
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Student Academic Profile</h2>
                    <p class="text-muted mb-0">Ensure your academic info, CGPA, and skills are accurate for recruitment eligibility.</p>
                </div>
                <a href="<?= BASE_URL ?>/public/index.php?page=student/dashboard" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>

            <!-- Profile Completeness Bar -->
            <div class="card card-modern shadow-sm p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-dark">Profile Completeness</span>
                    <span class="small fw-bold text-primary"><?= $completeness ?>%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $completeness ?>%;" aria-valuenow="<?= $completeness ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <div class="card card-modern shadow-sm p-4 p-md-5">
                <form action="<?= BASE_URL ?>/public/index.php?page=student/profile" method="POST">
                    <?= csrfField() ?>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-person-fill text-primary me-2"></i>Personal & Contact Details
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= e($student['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">College Email (Read-only)</label>
                            <input type="email" class="form-control bg-light" id="email" value="<?= e($student['email']) ?>" readonly>
                            <div class="form-text">Your institutional email is your primary identifier.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Contact Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= e($student['phone'] ?? '') ?>" placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6">
                            <label for="resume_link" class="form-label fw-semibold">Resume / Portfolio URL</label>
                            <input type="url" class="form-control" id="resume_link" name="resume_link" value="<?= e($student['resume_link'] ?? '') ?>" placeholder="https://drive.google.com/... or LinkedIn / GitHub">
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-mortarboard-fill text-primary me-2"></i>Academic Data & Eligibility Criteria
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="course" class="form-label fw-semibold">Degree / Branch <span class="text-danger">*</span></label>
                            <select class="form-select" id="course" name="course" required>
                                <?php 
                                $courses = defined('ALL_CRMS_COURSES') ? ALL_CRMS_COURSES : ['B.Tech CSE' => 'B.Tech CSE'];
                                foreach ($courses as $cCode => $cLabel): 
                                ?>
                                    <option value="<?= $cCode ?>" <?= $student['course'] === $cCode ? 'selected' : '' ?>><?= $cLabel ?> (<?= $cCode ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="semester" class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="semester" name="semester" required>
                                <?php for ($s = 1; $s <= 8; $s++): ?>
                                    <option value="<?= $s ?>" <?= (int)$student['semester'] === $s ? 'selected' : '' ?>>Sem <?= $s ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cgpa" class="form-label fw-semibold">Current CGPA <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="10" class="form-control" id="cgpa" name="cgpa" value="<?= number_format((float)$student['cgpa'], 2) ?>" required>
                        </div>
                        <div class="col-12">
                            <label for="skills" class="form-label fw-semibold">Technical & Professional Skills</label>
                            <input type="text" class="form-control" id="skills" name="skills" value="<?= e($student['skills'] ?? '') ?>" placeholder="e.g. Java, Python, SQL, React, AWS, DSA">
                            <div class="form-text">Comma-separated skills. These are cross-referenced with recruiter requirements for match scores.</div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-shield-lock-fill text-primary me-2"></i>Security (Leave blank to keep unchanged)
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6" placeholder="Leave empty if not changing">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?= BASE_URL ?>/public/index.php?page=student/dashboard" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i>Save Profile Changes
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
