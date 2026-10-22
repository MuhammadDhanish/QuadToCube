<?php
$pageTitle = 'Manage Placement Drives';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Campus Placement Drives</h2>
            <p class="text-muted mb-0">Publish opportunities, configure automated eligibility criteria, and review candidate pipelines.</p>
        </div>
        <a href="<?= BASE_URL ?>/public/index.php?page=admin/job-form" class="btn btn-primary-custom shadow-sm">
            <i class="bi bi-plus-lg me-1"></i>Post New Opportunity
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="card card-modern shadow-sm p-3 mb-4">
        <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="page" value="admin/jobs">

            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Search by job title or required skill..." value="<?= e($search) ?>">
                </div>
            </div>

            <div class="col-md-3">
                <select name="company_id" class="form-select">
                    <option value="">-- All Companies --</option>
                    <?php foreach ($companies as $comp): ?>
                        <option value="<?= $comp['company_id'] ?>" <?= (int)$companyId === (int)$comp['company_id'] ? 'selected' : '' ?>>
                            <?= e($comp['company_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All Status</option>
                    <option value="open" <?= $status === 'open' ? 'selected' : '' ?>>Open Only</option>
                    <option value="closed" <?= $status === 'closed' ? 'selected' : '' ?>>Closed Only</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                <?php if (!empty($search) || !empty($companyId) || $status !== 'all'): ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/jobs" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Jobs Table -->
    <div class="card card-modern shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Job Title & Type</th>
                        <th>Recruiting Company</th>
                        <th>Package & Location</th>
                        <th>Eligibility Rules</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Applicants</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jobs)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">No placement drives found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark fs-6"><?= e($job['title']) ?></div>
                                    <span class="badge bg-light text-primary border border-primary border-opacity-25 small"><?= e($job['job_type']) ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= e($job['company_name']) ?></div>
                                    <div class="text-muted small"><?= e($job['industry']) ?></div>
                                </td>
                                <td>
                                    <div class="text-success fw-bold"><?= e($job['salary_package']) ?></div>
                                    <div class="text-muted small"><i class="bi bi-geo-alt"></i> <?= e($job['location']) ?></div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><span class="text-muted">Min CGPA:</span> <strong><?= number_format((float)$job['min_cgpa'], 2) ?></strong></div>
                                        <div><span class="text-muted">Courses:</span> <?= e($job['allowed_courses']) ?></div>
                                        <div><span class="text-muted">Semesters:</span> Sem <?= e($job['allowed_semesters']) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?= date('d M Y', strtotime($job['deadline'])) ?></div>
                                    <div class="small text-muted"><?= date('h:i A', strtotime($job['deadline'])) ?></div>
                                </td>
                                <td>
                                    <?= getStatusBadge($job['status']) ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/applications&job_id=<?= $job['job_id'] ?>" 
                                       class="btn btn-sm btn-outline-primary position-relative">
                                        <i class="bi bi-people-fill me-1"></i>Applicants
                                        <span class="badge bg-primary rounded-pill ms-1"><?= (int)$job['total_applicants'] ?></span>
                                    </a>
                                </td>
                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/job-form&id=<?= $job['job_id'] ?>" 
                                       class="btn btn-sm btn-outline-secondary me-1" title="Edit Drive">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>/public/index.php?page=admin/job-delete" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this job posting and all submitted student applications?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Drive">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
