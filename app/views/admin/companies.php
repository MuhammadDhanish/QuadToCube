<?php
$pageTitle = 'Manage Partner Companies';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid px-lg-4 py-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Recruiting Partner Companies</h2>
            <p class="text-muted mb-0">Manage corporate partners, industry sectors, and employer contact information.</p>
        </div>
        <button type="button" class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
            <i class="bi bi-plus-lg me-1"></i>Add Partner Company
        </button>
    </div>

    <!-- Companies Grid / Table -->
    <div class="card card-modern shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-crms align-middle">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Industry</th>
                        <th>Contact Person</th>
                        <th>Email & Phone</th>
                        <th>Location</th>
                        <th>Placement Drives</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($companies)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No recruiting companies registered yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($companies as $comp): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark fs-6"><?= e($comp['company_name']) ?></div>
                                    <?php if (!empty($comp['website'])): ?>
                                        <a href="<?= e($comp['website']) ?>" target="_blank" class="small text-primary text-decoration-none">
                                            <?= e($comp['website']) ?> <i class="bi bi-box-arrow-up-right small"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($comp['industry']) ?></span>
                                </td>
                                <td><?= e($comp['contact_person'] ?? 'N/A') ?></td>
                                <td>
                                    <div class="small"><?= e($comp['contact_email'] ?? 'N/A') ?></div>
                                    <div class="small text-muted"><?= e($comp['contact_phone'] ?? '') ?></div>
                                </td>
                                <td><?= e($comp['location'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                        <?= (int)$comp['total_jobs'] ?> Drives (<?= (int)$comp['active_jobs'] ?> Active)
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editCompanyModal<?= $comp['company_id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="<?= BASE_URL ?>/public/index.php?page=admin/company-delete" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete <?= e(addslashes($comp['company_name'])) ?> and its associated drives?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="company_id" value="<?= $comp['company_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Company Modal -->
                            <div class="modal fade" id="editCompanyModal<?= $comp['company_id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <form action="<?= BASE_URL ?>/public/index.php?page=admin/company-save" method="POST">
                                            <?= csrfField() ?>
                                            <input type="hidden" name="company_id" value="<?= $comp['company_id'] ?>">

                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold">Edit Company Profile</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Company Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="company_name" class="form-control" value="<?= e($comp['company_name']) ?>" required>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Industry</label>
                                                        <input type="text" name="industry" class="form-control" value="<?= e($comp['industry']) ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Location / HQ</label>
                                                        <input type="text" name="location" class="form-control" value="<?= e($comp['location'] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Contact Person</label>
                                                        <input type="text" name="contact_person" class="form-control" value="<?= e($comp['contact_person'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Contact Email</label>
                                                        <input type="email" name="contact_email" class="form-control" value="<?= e($comp['contact_email'] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Contact Phone</label>
                                                        <input type="tel" name="contact_phone" class="form-control" value="<?= e($comp['contact_phone'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Website URL</label>
                                                        <input type="url" name="website" class="form-control" value="<?= e($comp['website'] ?? '') ?>">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Company Overview</label>
                                                    <textarea name="description" class="form-control" rows="3"><?= e($comp['description'] ?? '') ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary-custom px-4">Update Details</button>
                                            </div>
                                        </form>
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

<!-- Add Company Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= BASE_URL ?>/public/index.php?page=admin/company-save" method="POST">
                <?= csrfField() ?>

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-building-add text-primary me-2"></i>Add Recruiting Company
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" required placeholder="e.g. Google Cloud India">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Industry</label>
                            <input type="text" name="industry" class="form-control" placeholder="e.g. IT & Software" value="Information Technology">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Location / HQ</label>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Bengaluru, India">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="HR / Campus Lead">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" placeholder="campus@company.com">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Contact Phone</label>
                            <input type="tel" name="contact_phone" class="form-control" placeholder="+91 ...">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Website URL</label>
                            <input type="url" name="website" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Company Overview</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief background about corporate profile, core products, and vision..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4">Create Company Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
