<?php
$pageTitle = 'Login Portal';
require_once __DIR__ . '/../layouts/header.php';

$activeRole = $_GET['role'] ?? 'student';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h2 class="fw-bold mb-1">Welcome to CRMS</h2>
                <p class="text-muted">Sign in to your campus recruitment account</p>
            </div>

            <!-- Role Selector Pills -->
            <ul class="nav nav-pills nav-fill mb-4 p-1 bg-white border rounded-3 shadow-sm" id="loginRoleTabs">
                <li class="nav-item">
                    <button class="nav-link <?= $activeRole === 'student' ? 'active' : '' ?>" id="tab-student" onclick="setRole('student')">
                        <i class="bi bi-person-fill me-1"></i>Student Login
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link <?= $activeRole === 'admin' ? 'active' : '' ?>" id="tab-admin" onclick="setRole('admin')">
                        <i class="bi bi-shield-shaded me-1"></i>Placement Admin
                    </button>
                </li>
            </ul>

            <div class="card card-modern shadow-sm p-4">
                <form action="<?= BASE_URL ?>/public/index.php?page=login" method="POST" id="loginForm">
                    <?= csrfField() ?>
                    <input type="hidden" name="role_type" id="role_type_input" value="<?= e($activeRole) ?>">

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="name@crms.edu">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-semibold mb-0">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6 shadow-sm mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
                    </button>
                </form>

                <div class="text-center border-top pt-3" id="registerPrompt">
                    <span class="text-muted small">New student? </span>
                    <a href="<?= BASE_URL ?>/public/index.php?page=register" class="fw-semibold text-primary small">Register here</a>
                </div>
            </div>

            <!-- Demo Credentials Quick Helper Card -->
            <div class="card bg-light border mt-4 p-3 rounded-3 shadow-none">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i>
                    <span class="fw-bold text-dark small">Demo Test Credentials (Pre-seeded)</span>
                </div>
                <div class="small text-muted mb-2">Click to instantly populate credentials:</div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fillCredentials('admin@crms.edu', 'Admin@123', 'admin')">
                        <i class="bi bi-shield-fill-check me-1 text-danger"></i>Admin Coordinator
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fillCredentials('rahul.sharma@crms.edu', 'Student@123', 'student')">
                        <i class="bi bi-person-fill me-1 text-primary"></i>Rahul (B.Tech CSE, 8.85 CGPA)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fillCredentials('priya.patel@crms.edu', 'Student@123', 'student')">
                        <i class="bi bi-person-fill me-1 text-primary"></i>Priya (B.Tech IT, 8.20 CGPA)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="fillCredentials('amit.verma@crms.edu', 'Student@123', 'student')">
                        <i class="bi bi-person-fill me-1 text-primary"></i>Amit (B.Tech ECE, 7.20 CGPA)
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function setRole(role) {
    document.getElementById('role_type_input').value = role;
    if (role === 'admin') {
        document.getElementById('tab-admin').classList.add('active');
        document.getElementById('tab-student').classList.remove('active');
        document.getElementById('registerPrompt').style.display = 'none';
    } else {
        document.getElementById('tab-student').classList.add('active');
        document.getElementById('tab-admin').classList.remove('active');
        document.getElementById('registerPrompt').style.display = 'block';
    }
}

function fillCredentials(email, password, role) {
    setRole(role);
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}

// Ensure role is set properly on load
document.addEventListener('DOMContentLoaded', () => {
    setRole('<?= e($activeRole) ?>');
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
