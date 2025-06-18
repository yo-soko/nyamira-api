document.addEventListener('DOMContentLoaded', function () {
    // Open Add Modal
    document.getElementById('addFeeStructureBtn')?.addEventListener('click', () => {
        document.querySelector('#feeStructureForm').reset();
        document.querySelector('#feeStructureForm input[name="fee_id"]').value = '';
        document.querySelector('#feeStructureModal .modal-title').textContent = 'Add Fee Structure';
        document.getElementById('saveFeeStructureBtn').textContent = 'Save Fee';
    });

    // Edit Modal Logic
    document.querySelectorAll('.editFeeStructureBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/fee-structure/${id}`)
                .then(response => response.json())
                .then(data => {
                    const form = document.querySelector('#feeStructureForm');
                    form.querySelector('input[name="fee_id"]').value = data.id;
                    form.querySelector('select[name="level_id"]').value = data.class_id;
                    form.querySelector('select[name="term_id"]').value = data.term_id;
                    form.querySelector('input[name="amount"]').value = data.amount;
                    form.querySelector('input[name="description"]').value = data.description;
                    form.querySelector('select[name="feeStatus"]').value = data.status;

                    const modal = new bootstrap.Modal(document.getElementById('feeStructureModal'));
                    document.querySelector('#feeStructureModal .modal-title').textContent = 'Edit Fee Structure';
                    document.getElementById('saveFeeStructureBtn').textContent = 'Update Fee';
                    modal.show();
                });
        });
    });

    // Delete Fee
    document.querySelectorAll('.deleteFeeStructureBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this fee structure?')) {
                fetch(`/fee-structure/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to delete. Try again.');
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
        const feeId = formData.get('fee_id');
        const url = feeId ? `/fee-structure/${feeId}` : '/fee-structure';
        const method = feeId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error saving fee structure. Check your inputs.');
            }
        });
    });
});
