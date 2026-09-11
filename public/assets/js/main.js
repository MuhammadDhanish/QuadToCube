/**
 * Campus Recruitment Management System (CRMS)
 * Interactive Client Logic & Enhancements
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // 2. Client-side Instant Table/List Filtering
    const searchInputs = document.querySelectorAll('[data-filter-target]');
    searchInputs.forEach(input => {
        const targetSelector = input.getAttribute('data-filter-target');
        const rows = document.querySelectorAll(`${targetSelector}`);

        input.addEventListener('keyup', (e) => {
            const query = e.target.value.toLowerCase().trim();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // 3. Status Change Modal Data Population (Admin side)
    const statusModal = document.getElementById('statusModal');
    if (statusModal) {
        statusModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            const appId = button.getAttribute('data-app-id');
            const studentName = button.getAttribute('data-student-name');
            const currentStatus = button.getAttribute('data-current-status');
            const notes = button.getAttribute('data-notes') || '';
            const offerRef = button.getAttribute('data-offer-ref') || '';

            document.getElementById('modal_app_id').value = appId;
            document.getElementById('modal_student_name').textContent = studentName;
            document.getElementById('modal_status_select').value = currentStatus;
            document.getElementById('modal_notes').value = notes;
            
            const offerField = document.getElementById('modal_offer_ref');
            if (offerField) {
                offerField.value = offerRef;
                toggleOfferField(currentStatus);
            }
        });

        const statusSelect = document.getElementById('modal_status_select');
        if (statusSelect) {
            statusSelect.addEventListener('change', (e) => {
                toggleOfferField(e.target.value);
            });
        }
    }

    function toggleOfferField(status) {
        const container = document.getElementById('offer_ref_container');
        if (container) {
            if (status === 'selected') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }
    }

    // 4. Confirmation dialogs
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', (e) => {
            const message = el.getAttribute('data-confirm') || 'Are you sure you want to proceed?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
