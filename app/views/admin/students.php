<?php
$pageTitle = 'Student Directory';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Student Directory</h2>
            <p class="text-muted mb-0">Search, filter, and inspect registered candidates and their placement standings.</p>
        </div>
        <div class="badge bg-light text-dark border px-3 py-2 fs-6">
            Total Students: <strong><?= count($students) ?></strong>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="card card-modern shadow-sm p-3 mb-4">
        <form action="<?= BASE_URL ?>/public/index.php" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="page" value="admin/students">

            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="Search by student name, email, or skill..." value="<?= e($search) ?>">
                </div>
            </div>

            <div class="col-md-3">
                <select name="course" class="form-select">
                    <option value="">-- All Courses / Branches --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= e($c) ?>" <?= $course === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" step="0.1" min="0" max="10" name="min_cgpa" class="form-control" 
                       placeholder="Min CGPA" value="<?= e($minCgpa) ?>">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                <?php if (!empty($search) || !empty($course) || !empty($minCgpa)): ?>
                    <a href="<?= BASE_URL ?>/public/index.php?page=admin/students" class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Student Table -->
    <div class="card card-modern shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Student Name & Email</th>
                        <th>Course & Branch</th>
                        <th>Semester</th>
                        <th>CGPA</th>
                        <th>Technical Skills</th>
                        <th>Applications</th>
                        <th>Placement Outcome</th>
                        <th class="text-end">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">No student records matching criteria.</td></tr>
                    <?php else: ?>
                        <?php foreach ($students as $st): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 36px; height: 36px;">
                                            <?= strtoupper(substr($st['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($st['name']) ?></div>
                                            <div class="text-muted small"><?= e($st['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= e($st['course']) ?></span></td>
                                <td>Sem <?= e($st['semester']) ?></td>
                                <td>
                                    <span class="fw-bold text-success"><?= number_format((float)$st['cgpa'], 2) ?></span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 260px;">
                                        <?php 
                                        $skillsList = array_filter(array_map('trim', explode(',', $st['skills'] ?? '')));
                                        foreach (array_slice($skillsList, 0, 3) as $sk): 
                                        ?>
                                            <span class="skill-badge py-0 px-2 small"><?= e($sk) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($skillsList) > 3): ?>
                                            <span class="small text-muted">+<?= count($skillsList) - 3 ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        <?= (int)$st['total_applications'] ?> applied
                                    </span>
                                </td>
                                <td>
                                    <?php if ((int)$st['selected_count'] > 0): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Placed (<?= $st['selected_count'] ?>)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">In Process</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#studentModal<?= $st['student_id'] ?>">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>

                            <!-- Student Info Modal -->
                            <div class="modal fade" id="studentModal<?= $st['student_id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold">
                                                <i class="bi bi-person-badge text-primary me-2"></i><?= e($st['name']) ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <div class="text-muted small">Email:</div>
                                                <div class="fw-semibold text-dark"><?= e($st['email']) ?></div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="text-muted small">Phone:</div>
                                                <div class="text-dark"><?= e($st['phone'] ?? 'Not provided') ?></div>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <div class="text-muted small">Degree / Branch:</div>
                                                    <div class="fw-semibold text-dark"><?= e($st['course']) ?></div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-muted small">Semester & CGPA:</div>
                                                    <div class="fw-bold text-success">Sem <?= e($st['semester']) ?> &bull; <?= number_format((float)$st['cgpa'], 2) ?> CGPA</div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="text-muted small">Skills:</div>
                                                <div class="d-flex flex-wrap gap-1 mt-1">
                                                    <?php foreach ($skillsList as $sk): ?>
                                                        <span class="skill-badge"><?= e($sk) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($st['resume_link'])): ?>
                                                <div class="mt-3 pt-3 border-top">
                                                    <a href="<?= e($st['resume_link']) ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                                        <i class="bi bi-file-earmark-pdf me-1"></i>View Resume / Portfolio
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
