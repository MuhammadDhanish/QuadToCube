<?php
$pageTitle = 'Placement Admin Dashboard';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Placement Cell Administration</h2>
            <p class="text-muted mb-0">Overview of campus recruitment metrics, student participation, and hiring progress.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/public/index.php?page=admin/job-form" class="btn btn-primary-custom shadow-sm">
                <i class="bi bi-plus-circle me-1"></i>Post New Drive
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?page=admin/companies" class="btn btn-outline-secondary">
                <i class="bi bi-building-add me-1"></i>Add Company
            </a>
            <a href="<?= BASE_URL ?>/public/index.php?page=admin/reports" class="btn btn-outline-primary">
                <i class="bi bi-bar-chart-fill me-1"></i>Full Reports
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-2">
            <div class="stat-card primary card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Students</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= $summary['total_students'] ?></div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="stat-card cyan card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Companies</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= $summary['total_companies'] ?></div>
                    </div>
                    <div class="stat-icon cyan">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="stat-card amber card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Active Drives</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= $summary['active_jobs'] ?></div>
                    </div>
                    <div class="stat-icon amber">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="stat-card primary card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Applications</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= $summary['total_applications'] ?></div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="stat-card emerald card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Placed</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= $summary['placed_students'] ?></div>
                    </div>
                    <div class="stat-icon emerald">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-2">
            <div class="stat-card rose card-hover">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Placement %</div>
                        <div class="fs-3 fw-bold text-dark mt-1"><?= $summary['placement_rate'] ?>%</div>
                    </div>
                    <div class="stat-icon rose">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Applications per Job Bar Chart -->
        <div class="col-lg-8">
            <div class="card card-modern shadow-sm h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Applications Received per Drive</h5>
                    <span class="badge bg-light text-secondary border">Top 10 Drives</span>
                </div>
                <div style="position: relative; height: 320px;">
                    <canvas id="jobApplicantsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Branch-wise Placement Donut Chart -->
        <div class="col-lg-4">
            <div class="card card-modern shadow-sm h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pie-chart-fill text-success me-2"></i>Placements by Branch</h5>
                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/reports" class="small text-primary">Details</a>
                </div>
                <div style="position: relative; height: 320px;">
                    <canvas id="branchPlacementChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links & Top Drives -->
    <div class="card card-modern shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-briefcase-fill text-primary me-2"></i>Active Placement Drives Overview</h5>
            <a href="<?= BASE_URL ?>/public/index.php?page=admin/jobs" class="text-primary small fw-semibold">
                Manage All Drives <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Opportunity</th>
                        <th>Company</th>
                        <th>Applicants</th>
                        <th>Hired</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applicationsPerJob)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No placement drives found.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($applicationsPerJob, 0, 5) as $job): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($job['title']) ?></div>
                                </td>
                                <td><?= e($job['company_name']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="bi bi-person-fill text-primary me-1"></i><?= $job['applicant_count'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i><?= (int)$job['selected_count'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/applications&job_id=<?= $job['job_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        Review Applicants (<?= $job['applicant_count'] ?>)
                                    </a>
                                </td>
                                <td><?= getStatusBadge('open') ?></td>
                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/job-form&id=<?= $job['job_id'] ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js Setup Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Applications Bar Chart
    const jobLabels = <?= json_encode(array_column($applicationsPerJob, 'title')) ?>;
    const applicantCounts = <?= json_encode(array_map('intval', array_column($applicationsPerJob, 'applicant_count'))) ?>;
    const selectedCounts = <?= json_encode(array_map('intval', array_column($applicationsPerJob, 'selected_count'))) ?>;

    const ctxJob = document.getElementById('jobApplicantsChart').getContext('2d');
    new Chart(ctxJob, {
        type: 'bar',
        data: {
            labels: jobLabels.map(t => t.length > 20 ? t.substring(0, 18) + '...' : t),
            datasets: [
                {
                    label: 'Total Applicants',
                    data: applicantCounts,
                    backgroundColor: '#4f46e5',
                    borderRadius: 6
                },
                {
                    label: 'Selected / Placed',
                    data: selectedCounts,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // 2. Branch Placement Chart
    const branchLabels = <?= json_encode(array_column($branchStats, 'course')) ?>;
    const placedCounts = <?= json_encode(array_map('intval', array_column($branchStats, 'placed_count'))) ?>;

    const ctxBranch = document.getElementById('branchPlacementChart').getContext('2d');
    new Chart(ctxBranch, {
        type: 'doughnut',
        data: {
            labels: branchLabels,
            datasets: [{
                data: placedCounts,
                backgroundColor: ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
