<?php
$isEdit = !empty($job);
$pageTitle = $isEdit ? 'Edit Placement Drive' : 'Post New Placement Drive';
require_once __DIR__ . '/../layouts/header.php';

$allowedCoursesList = $isEdit ? array_map('trim', explode(',', $job['allowed_courses'])) : ['All'];
$isAllCourses = in_array('All', $allowedCoursesList);

$availableCourses = defined('ALL_CRMS_COURSES') ? array_keys(ALL_CRMS_COURSES) : [
    'B.Tech CSE', 'B.Tech IT', 'B.Tech AI & Data Science', 'B.Tech AI & ML', 'B.Tech ECE', 'B.Tech EEE', 
    'B.Tech Mechanical', 'B.Tech Civil', 'B.Tech Chemical', 'B.Tech Aerospace', 'B.Tech Biotechnology', 
    'B.Tech Robotics', 'BCA', 'MCA', 'B.Sc CS', 'B.Sc IT', 'M.Sc CS', 'M.Tech CSE', 'M.Tech AI & DS', 'M.Tech VLSI', 'BBA', 'MBA'
];
if ($isEdit) {
    foreach ($allowedCoursesList as $cName) {
        if ($cName !== 'All' && !empty($cName) && !in_array($cName, $availableCourses)) {
            $availableCourses[] = $cName;
        }
    }
}
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
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-semibold mb-0">Eligible Degrees & Branches</label>
                                <span class="text-muted small">Select all that apply or add a custom degree below</span>
                            </div>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex flex-wrap gap-2 mb-3" id="coursesContainer">
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
                                    <?php 
                                    // Also display any previously saved custom courses in this job if not in standard list
                                    foreach ($allowedCoursesList as $customCourse):
                                        if ($customCourse !== 'All' && !in_array($customCourse, $availableCourses)):
                                    ?>
                                        <div class="form-check me-2 custom-added-course">
                                            <input class="form-check-input course-item" type="checkbox" name="allowed_courses[]" value="<?= e($customCourse) ?>" id="course_<?= md5($customCourse) ?>" checked>
                                            <label class="form-check-label small badge bg-primary-subtle text-primary border" for="course_<?= md5($customCourse) ?>">
                                                <?= e($customCourse) ?>
                                            </label>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>

                                <!-- Dynamic Custom Degree / Branch Input -->
                                <div class="pt-2 border-top d-flex flex-wrap align-items-center gap-2">
                                    <span class="small text-muted fw-semibold"><i class="bi bi-plus-circle text-primary me-1"></i>Add Degree / Branch:</span>
                                    <div class="input-group input-group-sm" style="max-width: 380px;">
                                        <input type="text" id="newCourseInput" class="form-control" 
                                               placeholder="e.g. B.Tech Cyber Security, B.Arch" 
                                               onkeydown="handleCourseEnter(event)">
                                        <button type="button" class="btn btn-primary" onclick="addCustomDegree()">
                                            <i class="bi bi-plus-lg me-1"></i>Add
                                        </button>
                                    </div>
                                    <span id="courseAddFeedback" class="small text-success d-none fw-semibold">
                                        <i class="bi bi-check-circle me-1"></i>Degree added & checked!
                                    </span>
                                </div>
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

function handleCourseEnter(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addCustomDegree();
    }
}

function addCustomDegree() {
    const input = document.getElementById('newCourseInput');
    if (!input) return;
    const val = input.value.trim();
    if (!val) {
        input.focus();
        return;
    }

    // Check if this course is already listed
    const existing = document.querySelectorAll('.course-item');
    for (let item of existing) {
        if (item.value.toLowerCase() === val.toLowerCase()) {
            item.checked = true;
            item.disabled = false;
            item.scrollIntoView({ behavior: 'smooth', block: 'center' });
            input.value = '';
            showCourseFeedback('Degree already in list & now checked!');
            return;
        }
    }

    // Uncheck "All" if it was checked
    const allBox = document.getElementById('course_all');
    if (allBox && allBox.checked) {
        allBox.checked = false;
        toggleAllCourses(allBox);
    }

    // Generate unique ID
    const safeId = 'course_custom_' + Date.now();
    const container = document.getElementById('coursesContainer');
    
    // Create new checkbox column
    const col = document.createElement('div');
    col.className = 'col-sm-6 col-md-4';
    col.innerHTML = `
        <div class="form-check p-2 rounded bg-light border border-primary-subtle shadow-xs">
            <input class="form-check-input course-item" type="checkbox" name="allowed_courses[]" 
                   value="${escapeHtml(val)}" id="${safeId}" checked>
            <label class="form-check-label fw-semibold text-primary" for="${safeId}">
                <i class="bi bi-mortarboard-fill text-primary me-1"></i>${escapeHtml(val)}
                <span class="badge bg-primary-subtle text-primary ms-1" style="font-size: 0.65rem;">Custom</span>
            </label>
        </div>
    `;

    container.appendChild(col);
    input.value = '';
    col.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    showCourseFeedback('Added "' + val + '" and marked eligible!');
}

function showCourseFeedback(msg) {
    const fb = document.getElementById('courseAddFeedback');
    if (fb) {
        fb.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i>${msg}`;
        fb.classList.remove('d-none');
        setTimeout(() => fb.classList.add('d-none'), 3500);
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', () => {
    const allBox = document.getElementById('course_all');
    if (allBox && allBox.checked) {
        toggleAllCourses(allBox);
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
