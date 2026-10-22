<?php
$pageTitle = 'Student Registration';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-10 text-success mb-3">
                    <i class="bi bi-mortarboard-fill fs-2"></i>
                </div>
                <h2 class="fw-bold mb-1">Student Registration</h2>
                <p class="text-muted">Create your profile to check eligibility and apply for campus drives</p>
            </div>

            <div class="card card-modern shadow-sm p-4 p-md-5">
                <form action="index.php?page=register" method="POST" id="registerForm">
                    <?= csrfField() ?>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Personal & Contact Information
                    </h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. John Doe">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">College Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="student@crms.edu">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 98765 43210">
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-book-fill text-primary me-2"></i>Academic Profile & Eligibility Data
                    </h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="course" class="form-label fw-semibold">Degree / Branch <span class="text-danger">*</span></label>
                            <select class="form-select" id="course" name="course" required onchange="handleCourseSelect(this)">
                                <option value="">-- Select Degree / Branch --</option>
                                <?php foreach (ALL_CRMS_COURSES as $code => $label): ?>
                                    <option value="<?= $code ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                                <option value="__custom__">+ Other (Enter Custom Degree / Branch)</option>
                            </select>
                            <div id="customCourseWrapper" class="mt-2 d-none">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="bi bi-mortarboard-fill text-primary"></i></span>
                                    <input type="text" id="customCourseInput" class="form-control" placeholder="Enter custom degree/branch (e.g. B.Tech Cyber Security)" onkeydown="if(event.key==='Enter'){event.preventDefault();applyCustomCourse();}">
                                    <button type="button" class="btn btn-primary" onclick="applyCustomCourse()">Apply</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="semester" class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="semester" name="semester" required>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i === 7 ? 'selected' : '' ?>>Sem <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cgpa" class="form-label fw-semibold">Current CGPA <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="10" class="form-control" id="cgpa" name="cgpa" required placeholder="e.g. 8.45">
                        </div>
                        <div class="col-12">
                            <label for="skills" class="form-label fw-semibold">Skills & Tech Stack</label>
                            <input type="text" class="form-control" id="skills" name="skills" placeholder="Comma-separated e.g. Java, Python, SQL, React, DSA">
                            <div class="form-text">Used by the smart eligibility system to calculate your match score with job drives.</div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                        <i class="bi bi-key-fill text-primary me-2"></i>Security & Credentials
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Min. 6 characters">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Re-enter password">
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary-custom py-2 fs-6 shadow-sm">
                            <i class="bi bi-check-circle-fill me-1"></i>Create Student Account
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <span class="text-muted small">Already registered? </span>
                        <a href="<?= BASE_URL ?>/public/index.php?page=login&role=student" class="fw-semibold text-primary small">Log in here</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
function handleCourseSelect(sel) {
    const wrap = document.getElementById('customCourseWrapper');
    if (sel.value === '__custom__') {
        wrap.classList.remove('d-none');
        document.getElementById('customCourseInput').focus();
    } else {
        wrap.classList.add('d-none');
    }
}

function applyCustomCourse() {
    const inp = document.getElementById('customCourseInput');
    const val = inp.value.trim();
    if (!val) {
        inp.focus();
        return;
    }
    const sel = document.getElementById('course');
    let found = false;
    for (let opt of sel.options) {
        if (opt.value.toLowerCase() === val.toLowerCase()) {
            opt.selected = true;
            found = true;
            break;
        }
    }
    if (!found) {
        const newOpt = new Option(val + ' (Custom)', val, true, true);
        sel.add(newOpt, sel.options[sel.options.length - 1]);
    }
    document.getElementById('customCourseWrapper').classList.add('d-none');
    inp.value = '';
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
    const sel = document.getElementById('course');
    if (sel.value === '__custom__') {
        const customVal = document.getElementById('customCourseInput').value.trim();
        if (customVal) {
            applyCustomCourse();
        } else {
            e.preventDefault();
            alert('Please enter your Degree / Branch name or select one from the list.');
            document.getElementById('customCourseInput').focus();
            return;
        }
    }

    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('confirm_password').value;
    if (p1 !== p2) {
        e.preventDefault();
        alert('Password and Confirm Password do not match.');
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
