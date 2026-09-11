<?php
$pageTitle = 'Recruitment Reports & Analytics';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <!-- Header with CSV Export Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Recruitment Analytics & Reports</h2>
            <p class="text-muted mb-0">Tabular analytics, branch-wise hiring percentages, and corporate partner performance.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/public/index.php?page=admin/export&type=placed_students" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-filetype-csv me-1"></i>Export Placed Students CSV
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?page=admin/export&type=companies" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-download me-1"></i>Export Company Stats CSV
            </a>
        </div>
    </div>

    <!-- Summary Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card emerald">
                <div class="text-muted small fw-semibold text-uppercase">Placed Candidates</div>
                <div class="fs-2 fw-bold text-dark mt-1"><?= $summary['placed_students'] ?></div>
                <div class="small text-muted mt-2">Distinct students placed</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card primary">
                <div class="text-muted small fw-semibold text-uppercase">Placement Success Rate</div>
                <div class="fs-2 fw-bold text-primary mt-1"><?= $summary['placement_rate'] ?>%</div>
                <div class="small text-muted mt-2">Overall cohort percentage</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card amber">
                <div class="text-muted small fw-semibold text-uppercase">Total Drives Posted</div>
                <div class="fs-2 fw-bold text-dark mt-1"><?= $summary['active_jobs'] ?> Active</div>
                <div class="small text-muted mt-2">Corporate opportunities</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card cyan">
                <div class="text-muted small fw-semibold text-uppercase">Total Applications</div>
                <div class="fs-2 fw-bold text-dark mt-1"><?= $summary['total_applications'] ?></div>
                <div class="small text-muted mt-2">Submitted student applications</div>
            </div>
        </div>
    </div>

    <!-- Report Section 1: Branch-wise Placement Breakdown -->
    <div class="card card-modern shadow-sm p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3">
            <i class="bi bi-mortarboard-fill text-primary me-2"></i>Branch-Wise Placement Breakdown
        </h5>
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Degree & Branch</th>
                        <th>Registered Students</th>
                        <th>Placed Students</th>
                        <th>Placement Rate (%)</th>
                        <th>Progress Bar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($branchStats)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No branch statistics available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($branchStats as $b): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= e($b['course']) ?></td>
                                <td><?= (int)$b['total_students'] ?></td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        <?= (int)$b['placed_count'] ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-primary"><?= $b['rate'] ?>%</td>
                                <td style="width: 30%;">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: <?= $b['rate'] ?>%;" 
                                             aria-valuenow="<?= $b['rate'] ?>" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Report Section 2: Partner Company Performance -->
    <div class="card card-modern shadow-sm p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3">
            <i class="bi bi-building-check text-primary me-2"></i>Recruiting Partner Hiring Metrics
        </h5>
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Industry</th>
                        <th>Location</th>
                        <th>Drives Conducted</th>
                        <th>Total Applicants</th>
                        <th>Students Hired</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($companyReport)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No company recruitment records available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($companyReport as $cr): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= e($cr['company_name']) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= e($cr['industry']) ?></span></td>
                                <td><?= e($cr['location'] ?? 'N/A') ?></td>
                                <td><?= (int)$cr['total_jobs'] ?></td>
                                <td><?= (int)$cr['total_applicants'] ?></td>
                                <td>
                                    <span class="badge bg-success px-3 py-1 rounded-pill">
                                        <?= (int)$cr['total_hired'] ?> Placed
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Report Section 3: Placed Students Directory -->
    <div class="card card-modern shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-award-fill text-warning me-2"></i>Placed Candidates Directory
            </h5>
            <span class="badge bg-light text-dark border px-3 py-1">Total Offers: <?= count($placedStudents) ?></span>
        </div>
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Student Name & Email</th>
                        <th>Branch & CGPA</th>
                        <th>Hiring Company</th>
                        <th>Role & Package</th>
                        <th>Offer Ref No.</th>
                        <th>Date Placed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($placedStudents)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No candidates marked as placed yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($placedStudents as $ps): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($ps['student_name']) ?></div>
                                    <div class="small text-muted"><?= e($ps['student_email']) ?></div>
                                </td>
                                <td>
                                    <div><?= e($ps['course']) ?></div>
                                    <div class="small text-success fw-semibold">CGPA: <?= number_format((float)$ps['cgpa'], 2) ?></div>
                                </td>
                                <td class="fw-semibold text-primary"><?= e($ps['company_name']) ?></td>
                                <td>
                                    <div><?= e($ps['job_title']) ?></div>
                                    <div class="small text-success fw-bold"><?= e($ps['salary_package']) ?></div>
                                </td>
                                <td>
                                    <code><?= e($ps['offer_letter_ref'] ?? 'N/A') ?></code>
                                </td>
                                <td class="small text-muted">
                                    <?= date('d M Y', strtotime($ps['selection_date'])) ?>
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
