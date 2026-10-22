<?php
$pageTitle = e($job['title']) . ' - ' . e($job['company_name']);
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <!-- Top Breadcrumb / Back Link -->
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Placement Drives
        </a>
    </div>

    <div class="row g-4">
        <!-- Left: Job Information & Company Profile -->
        <div class="col-lg-8">
            <div class="card card-modern shadow-sm p-4 p-md-5 mb-4">
                
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 mb-2">
                            <?= e($job['job_type']) ?>
                        </span>
                        <h2 class="fw-bold text-dark mb-1"><?= e($job['title']) ?></h2>
                        <h5 class="text-secondary mb-0">
                            <i class="bi bi-building text-primary me-2"></i><?= e($job['company_name']) ?>
                            <?php if (!empty($job['website'])): ?>
                                <a href="<?= e($job['website']) ?>" target="_blank" class="small text-muted ms-2 text-decoration-none">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            <?php endif; ?>
                        </h5>
                    </div>

                    <div>
                        <?php if ($application): ?>
                            <div class="text-end">
                                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill">
                                    <i class="bi bi-check2-circle me-1"></i>Applied: <?= strtoupper(str_replace('_', ' ', $application['status'])) ?>
                                </span>
                                <div class="small text-muted mt-1">Applied on <?= date('d M Y', strtotime($application['applied_date'])) ?></div>
                            </div>
                        <?php elseif ($eligibility['is_eligible']): ?>
                            <span class="badge-eligible px-3 py-2 fs-6">
                                <i class="bi bi-check-circle-fill me-1"></i>Eligible to Apply
                            </span>
                        <?php else: ?>
                            <span class="badge-ineligible px-3 py-2 fs-6">
                                <i class="bi bi-x-circle-fill me-1"></i>Ineligible
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Key Metrics Bar -->
                <div class="row g-3 my-3 p-3 bg-light rounded-4">
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">CTC / Compensation</div>
                        <div class="fw-bold text-success fs-5"><?= e($job['salary_package']) ?></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">Job Location</div>
                        <div class="fw-bold text-dark fs-6"><?= e($job['location']) ?></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">Application Deadline</div>
                        <div class="fw-bold text-danger fs-6"><?= date('d M Y, h:i A', strtotime($job['deadline'])) ?></div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">Total Applicants</div>
                        <div class="fw-bold text-primary fs-6"><?= (int)$job['total_applicants'] ?> Applied</div>
                    </div>
                </div>

                <!-- Description -->
                <h5 class="fw-bold text-dark mt-4 mb-3 border-bottom pb-2">
                    <i class="bi bi-file-text-fill text-primary me-2"></i>Job Description & Roles
                </h5>
                <div class="text-secondary lh-lg mb-4" style="white-space: pre-line;">
                    <?= e($job['description']) ?>
                </div>

                <!-- Required Skills -->
                <?php if (!empty($job['required_skills'])): ?>
                    <h5 class="fw-bold text-dark mt-4 mb-3 border-bottom pb-2">
                        <i class="bi bi-tools text-primary me-2"></i>Recommended Skills & Competencies
                    </h5>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <?php 
                        $reqSkills = array_map('trim', explode(',', $job['required_skills']));
                        $studentSkillsLower = array_map('strtolower', array_map('trim', explode(',', $student['skills'] ?? '')));
                        foreach ($reqSkills as $sk): 
                            $isMatched = in_array(strtolower($sk), $studentSkillsLower);
                        ?>
                            <span class="skill-badge <?= $isMatched ? 'matched' : '' ?> px-3 py-2 fs-6">
                                <?php if ($isMatched): ?>
                                    <i class="bi bi-check-circle-fill text-primary me-1"></i>
                                <?php endif; ?>
                                <?= e($sk) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Company Details -->
                <h5 class="fw-bold text-dark mt-4 mb-3 border-bottom pb-2">
                    <i class="bi bi-building-check text-primary me-2"></i>About the Recruiter
                </h5>
                <p class="text-secondary"><?= e($job['company_desc'] ?: 'Leading corporate placement partner collaborating with university placement cell.') ?></p>
                <div class="small text-muted">
                    <span><i class="bi bi-geo-alt me-1"></i>HQ: <?= e($job['company_location']) ?></span> &bull; 
                    <span class="ms-2"><i class="bi bi-briefcase me-1"></i>Industry: <?= e($job['industry']) ?></span>
                </div>

            </div>
        </div>

        <!-- Right: Automated Eligibility Breakdown & Action Box -->
        <div class="col-lg-4">
            
            <!-- Eligibility Breakdown Card -->
            <div class="card card-modern shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="bi bi-clipboard-check-fill text-primary me-2"></i>Eligibility Audit
                </h5>
                <p class="small text-muted mb-3">
                    Automated comparison between your profile credentials and the recruiter's specifications.
                </p>

                <div class="list-group list-group-flush mb-3">
                    <!-- CGPA -->
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">Minimum CGPA</div>
                            <div class="text-muted small">Req: <?= number_format((float)$job['min_cgpa'], 2) ?> | You: <?= number_format((float)$student['cgpa'], 2) ?></div>
                        </div>
                        <i class="bi <?= $eligibility['checks']['cgpa']['passed'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> fs-5"></i>
                    </div>

                    <!-- Course -->
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">Eligible Degrees</div>
                            <div class="text-muted small"><?= e($job['allowed_courses']) ?></div>
                        </div>
                        <i class="bi <?= $eligibility['checks']['course']['passed'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> fs-5"></i>
                    </div>

                    <!-- Semester -->
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">Eligible Semesters</div>
                            <div class="text-muted small">Allowed: Sem <?= e($job['allowed_semesters']) ?> | You: Sem <?= e($student['semester']) ?></div>
                        </div>
                        <i class="bi <?= $eligibility['checks']['semester']['passed'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> fs-5"></i>
                    </div>

                    <!-- Deadline -->
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">Drive Active Status</div>
                            <div class="text-muted small">Closes: <?= date('d M Y', strtotime($job['deadline'])) ?></div>
                        </div>
                        <i class="bi <?= $eligibility['checks']['deadline']['passed'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> fs-5"></i>
                    </div>

                    <!-- Skill Match -->
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold small">Skill Match Score</span>
                            <span class="badge bg-primary rounded-pill"><?= $eligibility['skill_match_pct'] ?>%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $eligibility['skill_match_pct'] ?>%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Final Decision Box -->
                <?php if ($eligibility['is_eligible']): ?>
                    <div class="alert alert-success d-flex align-items-center border-0 p-3 mb-0">
                        <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                        <div class="small">
                            <strong>Criteria Verified!</strong> You meet all criteria and are officially eligible to apply.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger border-0 p-3 mb-0">
                        <div class="fw-bold small mb-1"><i class="bi bi-x-circle-fill me-1"></i>You are not eligible for this drive:</div>
                        <ul class="mb-0 ps-3 small">
                            <?php foreach ($eligibility['reasons'] as $reason): ?>
                                <li><?= e($reason) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Application Action Box -->
            <div class="card card-modern shadow-sm p-4">
                <h5 class="fw-bold text-dark mb-3">Application Status</h5>

                <?php if ($application): ?>
                    <div class="text-center py-3">
                        <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-2">
                            <i class="bi bi-send-check-fill fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Application Submitted</h6>
                        <p class="text-muted small">Status: <strong><?= strtoupper(str_replace('_', ' ', $application['status'])) ?></strong></p>
                        <a href="<?= BASE_URL ?>/public/index.php?page=student/applications" class="btn btn-outline-primary btn-sm w-100 mt-2">
                            <i class="bi bi-file-earmark-check me-1"></i>View in My Applications
                        </a>
                    </div>
                <?php elseif ($eligibility['is_eligible']): ?>
                    <p class="text-muted small mb-3">
                        Ready to apply? Click the button below to submit your verified student profile to <strong><?= e($job['company_name']) ?></strong>.
                    </p>
                    <button type="button" class="btn btn-primary-custom w-100 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#applyModal">
                        <i class="bi bi-send-fill me-1"></i>Apply for this Position
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary w-100 py-2" disabled>
                        <i class="bi bi-slash-circle me-1"></i>Application Locked (Ineligible)
                    </button>
                    <div class="small text-muted text-center mt-2">
                        If your academic details or CGPA have recently updated, please 
                        <a href="<?= BASE_URL ?>/public/index.php?page=student/profile" class="text-primary">edit your profile</a>.
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Apply Confirmation Modal -->
<?php if ($eligibility['is_eligible'] && !$application): ?>
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= BASE_URL ?>/public/index.php?page=student/apply" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="applyModalLabel">
                        <i class="bi bi-send-fill text-primary me-2"></i>Confirm Job Application
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="fw-bold text-dark"><?= e($job['title']) ?></div>
                        <div class="text-muted small"><?= e($job['company_name']) ?> &bull; <?= e($job['salary_package']) ?></div>
                    </div>

                    <p class="small text-secondary mb-3">
                        Your registered profile details (<strong><?= e($student['name']) ?></strong>, CGPA: <strong><?= number_format((float)$student['cgpa'], 2) ?></strong>, <strong><?= e($student['course']) ?></strong>) will be transmitted to the placement cell and company coordinators.
                    </p>

                    <div class="mb-3">
                        <label for="notes" class="form-label small fw-semibold">Cover Note / Remarks (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Highlight why you are an ideal fit or mention key project achievements..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="bi bi-check2-circle me-1"></i>Confirm & Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
