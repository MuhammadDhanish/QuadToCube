<?php
$isEdit = !empty($job);
$pageTitle = $isEdit ? 'Edit Placement Drive' : 'Post New Placement Drive';
require_once __DIR__ . '/../layouts/header.php';

$allowedCoursesList = $isEdit ? array_map('trim', explode(',', $job['allowed_courses'])) : ['All'];
$isAllCourses = in_array('All', $allowedCoursesList);

$availableCourses = [
    'B.Tech CSE', 'B.Tech IT', 'B.Tech ECE', 'B.Tech Mechanical', 'B.Tech Civil', 'MCA', 'M.Tech CSE'
];
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1"><?= $pageTitle ?></h2>
                    <p class="text-muted mb-0">Define drive specifications and configure the automated eligibility rules.</p>
                </div>
                <a href="<?= BASE_URL ?>/public/index.php?page=admin/jobs" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back to Drives
                </a>
            </div>

            <div class="card card-modern shadow-sm p-4 p-md-5">
                <form action="<?= BASE_URL ?>/public/index.php?page=admin/job-save" method="POST">
                    <?= csrfField() ?>
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                    <?php endif; ?>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-briefcase-fill text-primary me-2"></i>Opportunity & Company Details
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="company_id" class="form-label fw-semibold">Recruiting Company <span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="form-select" required>
                                <option value="">-- Choose Partner Company --</option>
                                <?php foreach ($companies as $c): ?>
                                    <option value="<?= $c['company_id'] ?>" <?= ($isEdit && (int)$job['company_id'] === (int)$c['company_id']) ? 'selected' : '' ?>>
                                        <?= e($c['company_name']) ?> (<?= e($c['industry']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="title" class="form-label fw-semibold">Job / Position Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" required 
                                   placeholder="e.g. Graduate Software Engineer" value="<?= $isEdit ? e($job['title']) : '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="job_type" class="form-label fw-semibold">Opportunity Type</label>
                            <select name="job_type" id="job_type" class="form-select">
                                <option value="Full-time" <?= ($isEdit && $job['job_type'] === 'Full-time') ? 'selected' : '' ?>>Full-time</option>
                                <option value="Internship" <?= ($isEdit && $job['job_type'] === 'Internship') ? 'selected' : '' ?>>Internship</option>
                                <option value="FTE + Internship" <?= ($isEdit && $job['job_type'] === 'FTE + Internship') ? 'selected' : '' ?>>FTE + Internship</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="salary_package" class="form-label fw-semibold">Compensation / Package (CTC)</label>
                            <input type="text" name="salary_package" id="salary_package" class="form-control" 
                                   placeholder="e.g. 12 LPA or 40,000 / month" value="<?= $isEdit ? e($job['salary_package']) : '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="location" class="form-label fw-semibold">Job Location</label>
                            <input type="text" name="location" id="location" class="form-control" 
                                   placeholder="e.g. Bengaluru / Hybrid" value="<?= $isEdit ? e($job['location']) : 'Bengaluru / Pan-India' ?>">
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Job Description & Responsibilities <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="5" required 
                                      placeholder="Detail the technical expectations, day-to-day responsibilities, and interview rounds..."><?= $isEdit ? e($job['description']) : '' ?></textarea>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-shield-check text-primary me-2"></i>Automated Eligibility Rules Configurator
                    </h5>
                    <p class="text-muted small mb-3">
                        These criteria will automatically evaluate applicant profiles to permit or restrict student submissions.
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="min_cgpa" class="form-label fw-semibold">Minimum CGPA Required <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="10" name="min_cgpa" id="min_cgpa" class="form-control" required
                                   value="<?= $isEdit ? number_format((float)$job['min_cgpa'], 2) : '6.50' ?>">
                            <div class="form-text">Students below this threshold cannot submit applications.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="allowed_semesters" class="form-label fw-semibold">Eligible Semesters</label>
                            <input type="text" name="allowed_semesters" id="allowed_semesters" class="form-control" 
                                   placeholder="e.g. 7,8 or All" value="<?= $isEdit ? e($job['allowed_semesters']) : '7,8' ?>">
                            <div class="form-text">Comma-delimited semester numbers or 'All'.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="deadline" class="form-label fw-semibold">Application Deadline <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="deadline" id="deadline" class="form-control" required
                                   value="<?= $isEdit ? date('Y-m-d\TH:i', strtotime($job['deadline'])) : date('Y-m-d\T23:59', strtotime('+30 days')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Eligible Degrees & Branches</label>
                            <div class="d-flex flex-wrap gap-2 p-3 bg-light rounded-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" name="allowed_courses[]" value="All" id="course_all"
                                           <?= $isAllCourses ? 'checked' : '' ?> onchange="toggleAllCourses(this)">
                                    <label class="form-check-label fw-bold text-primary" for="course_all">All Degrees & Branches</label>
                                </div>
                                <?php foreach ($availableCourses as $ac): ?>
                                    <div class="form-check me-2">
                                        <input class="form-check-input course-item" type="checkbox" name="allowed_courses[]" value="<?= $ac ?>" id="course_<?= md5($ac) ?>"
                                               <?= (!$isAllCourses && in_array($ac, $allowedCoursesList)) ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="course_<?= md5($ac) ?>"><?= $ac ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label for="required_skills" class="form-label fw-semibold">Required & Preferred Skills</label>
                            <input type="text" name="required_skills" id="required_skills" class="form-control" 
                                   placeholder="e.g. Java, Python, SQL, DSA, React" value="<?= $isEdit ? e($job['required_skills']) : '' ?>">
                            <div class="form-text">Comma-separated skills used to calculate candidate skill match percentage.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Drive Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="open" <?= ($isEdit && $job['status'] === 'open') ? 'selected' : '' ?>>Open (Accepting Applications)</option>
                                <option value="closed" <?= ($isEdit && $job['status'] === 'closed') ? 'selected' : '' ?>>Closed (Locked)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="<?= BASE_URL ?>/public/index.php?page=admin/jobs" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i><?= $isEdit ? 'Save Changes' : 'Publish Opportunity' ?>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
function toggleAllCourses(checkbox) {
    const items = document.querySelectorAll('.course-item');
    items.forEach(item => {
        if (checkbox.checked) {
            item.checked = false;
            item.disabled = true;
        } else {
            item.disabled = false;
        }
    });
}
document.addEventListener('DOMContentLoaded', () => {
    const allBox = document.getElementById('course_all');
    if (allBox && allBox.checked) {
        toggleAllCourses(allBox);
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
