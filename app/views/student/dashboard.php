<?php
$pageTitle = 'Student Dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <!-- Welcome Banner Hero Card -->
    <div class="card card-modern border-0 shadow-sm p-4 p-md-5 mb-4 text-white position-relative overflow-hidden" 
         style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8">
                <span class="badge bg-light text-primary px-3 py-1 mb-2 rounded-pill fw-semibold">
                    <i class="bi bi-mortarboard-fill me-1"></i><?= e($student['course']) ?> &bull; Semester <?= e($student['semester']) ?>
                </span>
                <h1 class="display-6 fw-bold mb-2">Welcome back, <?= e($student['name']) ?>!</h1>
                <p class="text-light text-opacity-75 mb-3" style="max-width: 620px;">
                    Track your active campus placement applications, explore new recruitment drives, and check automated eligibility for premier hiring partners.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-light text-primary fw-semibold px-3 py-2 rounded-3 shadow-sm">
                        <i class="bi bi-search me-1"></i>Browse Job Opportunities
                    </a>
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/applications" class="btn btn-outline-light px-3 py-2 rounded-3">
                        <i class="bi bi-file-earmark-check me-1"></i>My Applications (<?= $totalApplied ?>)
                    </a>
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/profile" class="btn btn-outline-light px-3 py-2 rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>Edit Profile
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="bg-white bg-opacity-10 backdrop-blur p-4 rounded-4 border border-white border-opacity-20 d-inline-block text-center shadow-sm">
                    <div class="text-light text-opacity-75 small text-uppercase fw-bold">Academic Standing</div>
                    <div class="display-5 fw-extrabold text-white my-1"><?= number_format((float)$student['cgpa'], 2) ?></div>
                    <div class="badge bg-success bg-opacity-75 px-3 py-1 rounded-pill">CGPA on 10.0 Scale</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card primary card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Applied</div>
                        <div class="fs-2 fw-bold text-dark mt-1"><?= $totalApplied ?></div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-send-check-fill"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">Drives participated in</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card cyan card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Shortlisted</div>
                        <div class="fs-2 fw-bold text-dark mt-1"><?= $shortlistedCount ?></div>
                    </div>
                    <div class="stat-icon cyan">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">Cleared preliminary screening</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card emerald card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Selected / Offers</div>
                        <div class="fs-2 fw-bold text-dark mt-1"><?= $selectedCount ?></div>
                    </div>
                    <div class="stat-icon emerald">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">Final recruitment offers</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stat-card amber card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Eligible Drives</div>
                        <div class="fs-2 fw-bold text-dark mt-1"><?= count($eligibleJobs) ?></div>
                    </div>
                    <div class="stat-icon amber">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="small text-muted mt-2">Open drives matching your profile</div>
            </div>
        </div>
    </div>

    <!-- Content Sections: Recommended Jobs & Profile Summary -->
    <div class="row g-4">
        <!-- Left: Top Recommended Opportunities -->
        <div class="col-lg-8">
            <div class="card card-modern shadow-sm h-100">
                <div class="card-header-clean d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Eligible Placement Drives
                    </h5>
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="text-primary small fw-semibold">
                        View All Opportunities <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    <?php if (empty($eligibleJobs)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check2-all text-muted display-4"></i>
                            <h6 class="mt-3 text-dark">You have applied to all currently eligible open drives!</h6>
                            <p class="text-muted small">Check back soon as coordinators publish new company recruitment drives.</p>
                            <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-outline-primary btn-sm mt-2">
                                Browse All Company Drives
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach (array_slice($eligibleJobs, 0, 4) as $eJob): ?>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100 d-flex flex-column bg-light bg-opacity-50 card-hover">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                                <?= e($eJob['job_type']) ?>
                                            </span>
                                            <span class="badge-eligible small py-1 px-2">
                                                <i class="bi bi-check-circle-fill me-1"></i>Eligible
                                            </span>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark"><?= e($eJob['title']) ?></h6>
                                        <div class="text-muted small mb-2"><i class="bi bi-building me-1"></i><?= e($eJob['company_name']) ?></div>
                                        
                                        <div class="small text-secondary mb-3 mt-auto">
                                            <div><i class="bi bi-cash-stack text-success me-1"></i>Package: <strong><?= e($eJob['salary_package']) ?></strong></div>
                                            <div><i class="bi bi-calendar-event text-danger me-1"></i>Deadline: <?= date('d M Y', strtotime($eJob['deadline'])) ?></div>
                                        </div>

                                        <a href="<?= BASE_URL ?>/public/index.php?page=student/job-detail&id=<?= $eJob['job_id'] ?>" class="btn btn-sm btn-primary-custom w-100">
                                            View & Apply <i class="bi bi-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Profile Snapshot & Skills -->
        <div class="col-lg-4">
            <div class="card card-modern shadow-sm h-100">
                <div class="card-header-clean d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-circle text-primary me-2"></i>Profile Snapshot</h5>
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/profile" class="btn btn-sm btn-outline-secondary">Edit</a>
                </div>
                <div class="p-4">
                    <div class="text-center pb-3 border-bottom mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px; font-size: 1.6rem; font-weight: 700;">
                            <?= strtoupper(substr($student['name'], 0, 1)) ?>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark"><?= e($student['name']) ?></h6>
                        <div class="text-muted small"><?= e($student['email']) ?></div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-secondary small py-1 border-bottom">
                            <span>Degree:</span>
                            <span class="fw-semibold text-dark"><?= e($student['course']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-secondary small py-1 border-bottom">
                            <span>Semester:</span>
                            <span class="fw-semibold text-dark">Sem <?= e($student['semester']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-secondary small py-1 border-bottom">
                            <span>CGPA:</span>
                            <span class="fw-bold text-success"><?= number_format((float)$student['cgpa'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-secondary small py-1 border-bottom">
                            <span>Contact:</span>
                            <span class="text-dark"><?= e($student['phone'] ?? 'Not set') ?></span>
                        </div>
                    </div>

                    <div>
                        <div class="fw-semibold text-dark small mb-2">Registered Skills:</div>
                        <?php 
                        $skillArr = array_filter(array_map('trim', explode(',', $student['skills'] ?? '')));
                        if (empty($skillArr)): 
                        ?>
                            <div class="text-muted small fst-italic">No skills added yet. Update your profile to improve drive matches!</div>
                        <?php else: ?>
                            <div class="d-flex flex-wrap">
                                <?php foreach ($skillArr as $sk): ?>
                                    <span class="skill-badge"><?= e($sk) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
