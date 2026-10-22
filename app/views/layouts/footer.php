<?php
/**
 * Campus Recruitment Management System (CRMS)
 * Shared Footer Layout
 */
?>
</main>

<footer class="py-4 mt-auto">
    <div class="container-fluid px-lg-4">
        <div class="row align-items-center g-3">
            <div class="col-md-6 text-center text-md-start">
                <span class="fw-bold text-light"><?= APP_NAME ?></span>
                <div class="small text-secondary mt-1">Streamlining campus placements, eligibility automation, and recruiter workflows.</div>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="small text-secondary">
                    <span class="badge bg-dark border border-secondary text-secondary me-2">PHP 8+ &bull; MySQL &bull; Bootstrap 5</span>
                    &copy; <?= date('Y') ?> <?= APP_INSTITUTION ?>.
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js for analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Custom JS -->
<script src="<?= BASE_URL ?>/public/assets/js/main.js"></script>

</body>
</html>
