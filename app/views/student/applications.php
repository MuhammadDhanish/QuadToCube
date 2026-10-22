<?php
$pageTitle = 'My Applications';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Application Status</h2>
            <p class="text-muted mb-0">Track your progress across all campus recruitment and internship drives.</p>
        </div>
        <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-primary-custom shadow-sm">
            <i class="bi bi-plus-circle me-1"></i>Explore More Drives
        </a>
    </div>

    <?php if (empty($applications)): ?>
        <div class="card card-modern shadow-sm text-center py-5">
            <div class="py-4">
                <i class="bi bi-journal-x text-muted display-3 mb-3"></i>
                <h4 class="fw-bold text-dark">No Applications Submitted Yet</h4>
                <p class="text-muted" style="max-width: 480px; margin: 0 auto;">
                    You haven't submitted any job applications. Check out the open recruitment drives and check your automated eligibility.
                </p>
                <div class="mt-4">
                    <a href="<?= BASE_URL ?>/public/index.php?page=student/jobs" class="btn btn-primary-custom">
                        <i class="bi bi-briefcase me-1"></i>Browse Available Drives
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($applications as $app): ?>
                <?php
                $status = $app['status'];
                $isPending = ($status === 'applied');
                $isReview = ($status === 'under_review');
                $isShortlisted = ($status === 'shortlisted');
                $isSelected = ($status === 'selected');
                $isRejected = ($status === 'rejected');

                // Compute stepper step index (1 to 4)
                $stepIndex = 1;
                if ($isReview) $stepIndex = 2;
                if ($isShortlisted) $stepIndex = 3;
                if ($isSelected || $isRejected) $stepIndex = 4;
                ?>
                <div class="col-12">
                    <div class="card card-modern shadow-sm p-4 border-start-0" 
                         style="border-left: 6px solid <?= $isSelected ? '#10b981' : ($isRejected ? '#f43f5e' : ($isShortlisted ? '#4f46e5' : '#0ea5e9')) ?> !important;">
                        
                        <div class="row align-items-center g-3">
                            <!-- Job and Company details -->
                            <div class="col-lg-4">
                                <div class="d-flex align-items-center mb-1">
                                    <h5 class="fw-bold text-dark mb-0 me-2"><?= e($app['job_title']) ?></h5>
                                </div>
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-building me-1"></i><strong><?= e($app['company_name']) ?></strong> &bull; <?= e($app['industry']) ?>
                                </div>
                                <div class="small text-secondary">
                                    <div><i class="bi bi-cash-stack text-success me-1"></i>Package: <?= e($app['salary_package']) ?></div>
                                    <div><i class="bi bi-calendar-check text-primary me-1"></i>Applied: <?= date('d M Y, h:i A', strtotime($app['applied_date'])) ?></div>
                                </div>
                            </div>

                            <!-- Stepper Graphic Flow -->
                            <div class="col-lg-6">
                                <div class="timeline-stepper">
                                    <!-- Step 1: Applied -->
                                    <div class="step-item <?= $stepIndex >= 1 ? 'completed' : '' ?>">
                                        <div class="step-circle"><i class="bi bi-check"></i></div>
                                        <div class="step-label">Applied</div>
                                    </div>
                                    <!-- Step 2: Under Review -->
                                    <div class="step-item <?= $stepIndex > 2 ? 'completed' : ($stepIndex === 2 ? 'active' : '') ?>">
                                        <div class="step-circle"><?= $stepIndex > 2 ? '<i class="bi bi-check"></i>' : '2' ?></div>
                                        <div class="step-label">Under Review</div>
                                    </div>
                                    <!-- Step 3: Shortlisted -->
                                    <div class="step-item <?= $stepIndex > 3 ? 'completed' : ($stepIndex === 3 ? 'active' : '') ?>">
                                        <div class="step-circle"><?= $stepIndex > 3 ? '<i class="bi bi-check"></i>' : '3' ?></div>
                                        <div class="step-label">Shortlisted</div>
                                    </div>
                                    <!-- Step 4: Final Outcome -->
                                    <div class="step-item <?= $isSelected ? 'completed' : ($isRejected ? 'rejected' : '') ?>">
                                        <div class="step-circle">
                                            <?php if ($isSelected): ?>
                                                <i class="bi bi-trophy-fill"></i>
                                            <?php elseif ($isRejected): ?>
                                                <i class="bi bi-x"></i>
                                            <?php else: ?>
                                                4
                                            <?php endif; ?>
                                        </div>
                                        <div class="step-label"><?= $isSelected ? 'Selected' : ($isRejected ? 'Not Selected' : 'Final Selection') ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action & Status Badge -->
                            <div class="col-lg-2 text-lg-end">
                                <div class="mb-2">
                                    <?= getStatusBadge($status) ?>
                                </div>

                                <?php if ($isPending): ?>
                                    <form action="<?= BASE_URL ?>/public/index.php?page=student/withdraw" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to withdraw this application?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-x-circle me-1"></i>Withdraw
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Selection Outcome / Coordinator Remarks Box -->
                        <?php if ($isSelected): ?>
                            <div class="alert alert-success mt-3 mb-0 border-0 p-3 bg-success bg-opacity-10 text-success d-flex align-items-center">
                                <i class="bi bi-award-fill fs-3 me-3 text-success"></i>
                                <div>
                                    <div class="fw-bold">Congratulations! You have been selected by <?= e($app['company_name']) ?>!</div>
                                    <?php if (!empty($app['offer_letter_ref'])): ?>
                                        <div class="small mt-1">Offer Reference / ID: <strong><?= e($app['offer_letter_ref']) ?></strong></div>
                                    <?php endif; ?>
                                    <?php if (!empty($app['selection_remarks'])): ?>
                                        <div class="small text-muted mt-1">Coordinator Remarks: <em>"<?= e($app['selection_remarks']) ?>"</em></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php elseif ($isRejected && !empty($app['selection_remarks'])): ?>
                            <div class="alert alert-secondary mt-3 mb-0 border-0 p-3 small">
                                <i class="bi bi-info-circle me-1"></i>Feedback: <?= e($app['selection_remarks']) ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
