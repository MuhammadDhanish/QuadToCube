<?php
$pageTitle = 'Placement Opportunities';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <!-- Header with Search & Eligibility Filter -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Campus Placement Drives</h2>
            <p class="text-muted mb-0">Browse open recruitment drives. The automated eligibility engine evaluates your profile in real time.</p>
        </div>
        
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="btn-group shadow-sm" role="group" aria-label="Eligibility filter">
                <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs&filter=all&search=<?= urlencode($search) ?>" 
                   class="btn btn-sm <?= $filterEligibility !== 'eligible_only' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    All Drives
                </a>
                <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs&filter=eligible_only&search=<?= urlencode($search) ?>" 
                   class="btn btn-sm <?= $filterEligibility === 'eligible_only' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="bi bi-check2-circle me-1"></i>Eligible Only
                </a>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="card card-modern shadow-sm p-3 mb-4">
        <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="page" value="student/jobs">
            <input type="hidden" name="filter" value="<?= e($filterEligibility) ?>">
            
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Search by role, company name, or technology skills (e.g. Python, Cloud, Java)..." 
                           value="<?= e($search) ?>">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Search</button>
                <?php if (!empty($search) || $filterEligibility !== 'all'): ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-outline-secondary" title="Clear filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Job Cards Grid -->
    <?php if (empty($processedJobs)): ?>
        <div class="card card-modern shadow-sm text-center py-5">
            <div class="py-4">
                <i class="bi bi-briefcase text-muted display-3 mb-3"></i>
                <h4 class="fw-bold text-dark">No Placement Drives Found</h4>
                <p class="text-muted" style="max-width: 480px; margin: 0 auto;">
                    <?= $filterEligibility === 'eligible_only' ? 'There are no open drives currently matching your academic profile or CGPA. Try viewing All Drives.' : 'No opportunities match your search criteria. Try a broader search term.' ?>
                </p>
                <div class="mt-3">
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-primary btn-sm">Reset Filters</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($processedJobs as $job): ?>
                <?php 
                $eligibility = $job['eligibility'];
                $hasApplied = $job['has_applied'];
                $appStatus = $job['application_status'];
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card card-modern card-hover shadow-sm h-100 d-flex flex-column border-top-0" 
                         style="border-top: 4px solid <?= $hasApplied ? '#3b82f6' : ($eligibility['is_eligible'] ? '#10b981' : '#f43f5e') ?> !important;">
                        
                        <div class="p-4 flex-grow-1 d-flex flex-column">
                            <!-- Card Top Meta -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-dark border px-2 py-1 small">
                                    <i class="bi bi-briefcase me-1 text-primary"></i><?= e($job['job_type']) ?>
                                </span>

                                <?php if ($hasApplied): ?>
                                    <span class="badge bg-primary px-3 py-1 rounded-pill">
                                        <i class="bi bi-check-all me-1"></i>Applied: <?= strtoupper(str_replace('_', ' ', $appStatus)) ?>
                                    </span>
                                <?php elseif ($eligibility['is_eligible']): ?>
                                    <span class="badge-eligible small py-1 px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i>Eligible (<?= $eligibility['skill_match_pct'] ?>% Match)
                                    </span>
                                <?php else: ?>
                                    <span class="badge-ineligible small py-1 px-3" data-bs-toggle="tooltip" title="<?= e(implode(' ', $eligibility['reasons'])) ?>">
                                        <i class="bi bi-x-circle-fill me-1"></i>Not Eligible
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Title & Company -->
                            <h5 class="fw-bold text-dark mb-1 mt-1"><?= e($job['title']) ?></h5>
                            <div class="text-muted small mb-3">
                                <i class="bi bi-building text-secondary me-1"></i><strong><?= e($job['company_name']) ?></strong> &bull; <?= e($job['industry']) ?>
                            </div>

                            <!-- Key Specs / Badges -->
                            <div class="bg-light rounded-3 p-2 mb-3 small">
                                <div class="row g-1 text-secondary">
                                    <div class="col-6">
                                        <i class="bi bi-cash-stack text-success me-1"></i><?= e($job['salary_package']) ?>
                                    </div>
                                    <div class="col-6 text-end">
                                        <i class="bi bi-geo-alt text-danger me-1"></i><?= e($job['location']) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Eligibility Criteria Preview -->
                            <div class="mb-3 small">
                                <div class="text-dark fw-semibold mb-1">Eligibility Overview:</div>
                                <ul class="list-unstyled text-secondary mb-0 small">
                                    <li class="mb-1">
                                        <i class="bi <?= $student['cgpa'] >= $job['min_cgpa'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> me-1"></i>
                                        Min CGPA: <strong><?= number_format((float)$job['min_cgpa'], 2) ?></strong> (You: <?= number_format((float)$student['cgpa'], 2) ?>)
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi <?= $eligibility['checks']['course']['passed'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> me-1"></i>
                                        Courses: <?= e($job['allowed_courses']) ?>
                                    </li>
                                    <li>
                                        <i class="bi <?= $eligibility['checks']['semester']['passed'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> me-1"></i>
                                        Semesters: Sem <?= e($job['allowed_semesters']) ?>
                                    </li>
                                </ul>

                                <?php if (!$eligibility['is_eligible'] && !empty($eligibility['reasons'])): ?>
                                    <div class="alert alert-danger p-2 mt-2 mb-0 small border-0 bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= e($eligibility['reasons'][0]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Skills preview -->
                            <?php if (!empty($job['required_skills'])): ?>
                                <div class="mt-auto mb-3">
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php 
                                        $reqSkills = array_slice(array_map('trim', explode(',', $job['required_skills'])), 0, 4);
                                        $studentSkillsLower = array_map('strtolower', array_map('trim', explode(',', $student['skills'] ?? '')));
                                        foreach ($reqSkills as $sk): 
                                            $matched = in_array(strtolower($sk), $studentSkillsLower);
                                        ?>
                                            <span class="skill-badge <?= $matched ? 'matched' : '' ?> py-0 px-2 small">
                                                <?= $matched ? '<i class="bi bi-check text-primary me-0"></i>' : '' ?><?= e($sk) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Bottom Action / Deadline -->
                            <div class="pt-3 border-top mt-auto d-flex justify-content-between align-items-center">
                                <span class="small text-muted">
                                    <i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($job['deadline'])) ?>
                                </span>
                                <a href="<?= BASE_URL ?>/public/index.php?page=student/job-detail&id=<?= $job['job_id'] ?>" 
                                   class="btn btn-sm <?= $hasApplied ? 'btn-outline-primary' : ($eligibility['is_eligible'] ? 'btn-primary-custom' : 'btn-outline-secondary') ?>">
                                    <?= $hasApplied ? 'View Application' : ($eligibility['is_eligible'] ? 'View & Apply' : 'View Details') ?>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
