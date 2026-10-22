<?php
$pageTitle = 'Candidate Applications Review';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Candidate Shortlisting & Selection</h2>
            <p class="text-muted mb-0">Evaluate student applicants, shortlist qualified candidates, and issue placement offers.</p>
        </div>
        <?php if (!empty($job)): ?>
            <div class="badge bg-light text-dark border px-3 py-2 fs-6">
                Total Applicants: <strong><?= count($applications) ?></strong>
            </div>
        <?php endif; ?>
    </div>

    <!-- Drive Selector and Filter Bar -->
    <div class="card card-modern shadow-sm p-3 mb-4">
        <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="page" value="admin/applications">

            <div class="col-md-5">
                <label class="small text-muted fw-semibold mb-1">Select Placement Drive</label>
                <select name="job_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($allJobs as $j): ?>
                        <option value="<?= $j['job_id'] ?>" <?= ($job && (int)$job['job_id'] === (int)$j['job_id']) ? 'selected' : '' ?>>
                            <?= e($j['title']) ?> &bull; <?= e($j['company_name']) ?> (<?= (int)$j['total_applicants'] ?> Applicants)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="small text-muted fw-semibold mb-1">Filter by Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                    <option value="applied" <?= $statusFilter === 'applied' ? 'selected' : '' ?>>Applied</option>
                    <option value="under_review" <?= $statusFilter === 'under_review' ? 'selected' : '' ?>>Under Review</option>
                    <option value="shortlisted" <?= $statusFilter === 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                    <option value="selected" <?= $statusFilter === 'selected' ? 'selected' : '' ?>>Selected / Placed</option>
                    <option value="rejected" <?= $statusFilter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="small text-muted fw-semibold mb-1">Search Candidates</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search candidate name, CGPA, skill..." value="<?= e($search) ?>">
                    <button type="submit" class="btn btn-primary-custom">Filter</button>
                    <?php if (!empty($search) || $statusFilter !== 'all'): ?>
                        <a href="<?= BASE_URL ?>/public/index.php?page=admin/applications&job_id=<?= $job['job_id'] ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Drive Metadata Card -->
    <?php if ($job): ?>
        <div class="card card-modern shadow-sm p-3 mb-4 bg-light bg-opacity-50">
            <div class="row align-items-center g-2">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1 text-dark"><?= e($job['title']) ?> &bull; <span class="text-primary"><?= e($job['company_name']) ?></span></h5>
                    <div class="small text-secondary">
                        <span><i class="bi bi-cash-stack text-success me-1"></i>Package: <strong><?= e($job['salary_package']) ?></strong></span> &bull; 
                        <span class="ms-2"><i class="bi bi-mortarboard text-primary me-1"></i>Min CGPA: <strong><?= number_format((float)$job['min_cgpa'], 2) ?></strong></span> &bull; 
                        <span class="ms-2"><i class="bi bi-clock-history text-danger me-1"></i>Deadline: <?= date('d M Y', strtotime($job['deadline'])) ?></span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/export&type=placed_students" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download me-1"></i>Export Placements CSV
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Applicants Table -->
    <div class="card card-modern shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Candidate Profile</th>
                        <th>Academic Record</th>
                        <th>CGPA</th>
                        <th>Technical Skills</th>
                        <th>Applied On</th>
                        <th>Application Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No candidates found for this opportunity and filter.</td></tr>
                    <?php else: ?>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 36px; height: 36px;">
                                            <?= strtoupper(substr($app['student_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($app['student_name']) ?></div>
                                            <div class="text-muted small"><?= e($app['student_email']) ?></div>
                                            <?php if (!empty($app['resume_link'])): ?>
                                                <a href="<?= e($app['resume_link']) ?>" target="_blank" class="small text-primary text-decoration-none">
                                                    <i class="bi bi-file-earmark-pdf"></i> Resume
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($app['course']) ?></span>
                                    <div class="small text-muted mt-1">Sem <?= e($app['semester']) ?></div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6"><?= number_format((float)$app['cgpa'], 2) ?></span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 250px;">
                                        <?php 
                                        $skills = array_filter(array_map('trim', explode(',', $app['skills'] ?? '')));
                                        foreach (array_slice($skills, 0, 3) as $sk): 
                                        ?>
                                            <span class="skill-badge py-0 px-2 small"><?= e($sk) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?= date('d M Y', strtotime($app['applied_date'])) ?></div>
                                    <div class="small text-muted"><?= date('h:i A', strtotime($app['applied_date'])) ?></div>
                                </td>
                                <td>
                                    <?= getStatusBadge($app['status']) ?>
                                    <?php if (!empty($app['offer_letter_ref'])): ?>
                                        <div class="small text-muted mt-1">Ref: <code><?= e($app['offer_letter_ref']) ?></code></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-primary-custom" 
                                            data-bs-toggle="modal" data-bs-target="#statusModal"
                                            data-app-id="<?= $app['application_id'] ?>"
                                            data-student-name="<?= e($app['student_name']) ?>"
                                            data-current-status="<?= e($app['status']) ?>"
                                            data-notes="<?= e($app['notes'] ?? '') ?>"
                                            data-offer-ref="<?= e($app['offer_letter_ref'] ?? '') ?>">
                                        <i class="bi bi-pencil-square me-1"></i>Update Status
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Candidate Status Management Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= BASE_URL ?>/public/index.php?page=admin/application-update" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="job_id" value="<?= $job ? $job['job_id'] : 0 ?>">
                <input type="hidden" name="application_id" id="modal_app_id" value="">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold" id="statusModalLabel">
                        <i class="bi bi-sliders text-primary me-2"></i>Update Candidate Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="text-muted small">Candidate:</div>
                        <div class="fw-bold text-dark fs-6" id="modal_student_name">Student Name</div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_status_select" class="form-label small fw-semibold">Application Stage / Status <span class="text-danger">*</span></label>
                        <select name="status" id="modal_status_select" class="form-select" required>
                            <option value="applied">Applied (Initial Submission)</option>
                            <option value="under_review">Under Review (Resume Verification)</option>
                            <option value="shortlisted">Shortlisted (Selected for Interview / Test)</option>
                            <option value="selected">Selected / Placed (Offer Extended)</option>
                            <option value="rejected">Not Selected / Rejected</option>
                        </select>
                    </div>

                    <!-- Offer letter reference container (toggled when status is selected) -->
                    <div class="mb-3" id="offer_ref_container" style="display: none;">
                        <label for="modal_offer_ref" class="form-label small fw-semibold text-success">
                            <i class="bi bi-award-fill me-1"></i>Offer Letter Reference / Roll No.
                        </label>
                        <input type="text" name="offer_letter_ref" id="modal_offer_ref" class="form-control" placeholder="e.g. MSFT-2026-FTE-0982">
                    </div>

                    <div class="mb-3">
                        <label for="modal_notes" class="form-label small fw-semibold">Coordinator Remarks / Feedback</label>
                        <textarea name="notes" id="modal_notes" class="form-control" rows="3" placeholder="Notes on interview performance, round status, or offer terms..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="bi bi-check2-circle me-1"></i>Save Outcome
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
