document.addEventListener('DOMContentLoaded', function () {
    // Open Add Modal
    document.getElementById('addFeeStructureBtn')?.addEventListener('click', () => {
        const form = document.querySelector('#feeStructureForm');
        form.reset();
        form.querySelector('input[name="fee_id"]').value = '';
        document.querySelector('#feeStructureModal .modal-title').textContent = 'Add Fee Structure';
        document.getElementById('saveFeeStructureBtn').textContent = 'Save Fee';
    });

    // Edit Modal Logic
    document.querySelectorAll('.editFeeStructureBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch('/fee-structure/show', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ fee_id: id })
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    const form = document.querySelector('#feeStructureForm');
                    form.querySelector('input[name="fee_id"]').value = data.id;
                    form.querySelector('select[name="level_id"]').value = data.level_id;
                    form.querySelector('select[name="term_id"]').value = data.term_id;
                    form.querySelector('input[name="amount"]').value = data.amount;
                    form.querySelector('input[name="description"]').value = data.description;
                    form.querySelector('select[name="feeStatus"]').value = data.status;

                    const modal = new bootstrap.Modal(document.getElementById('feeStructureModal'));
                    document.querySelector('#feeStructureModal .modal-title').textContent = 'Edit Fee Structure';
                    document.getElementById('saveFeeStructureBtn').textContent = 'Update Fee';
                    modal.show();
                } else {
                    alert(res.error || 'Failed to load fee structure.');
                }
            });
        });
    });

    // Delete Fee
    document.querySelectorAll('.deleteFeeStructureBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this fee structure?')) {
                fetch('/fee-structure/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ fee_id: id })
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert(res.error || 'Failed to delete.');
                    }
                });
            }
        });
    });

    // Submit Form (Create or Update)
    const form = document.querySelector('#feeStructureForm');
    form?.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const isEdit = formData.get('fee_id');
        const url = isEdit ? '/fee-structure/update' : '/fee-structure/store';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                location.reload();
            } else {
                alert(res.error || 'Failed to save fee structure.');
            }
        })
        .catch(() => alert('Server error. Please try again.'));
    });
});
