document.addEventListener('DOMContentLoaded', function () {
    // Open Add Modal
    document.getElementById('addFeeStructureBtn')?.addEventListener('click', () => {
        const form = document.querySelector('#feeStructureForm');
        form.reset();
        form.querySelector('input[name="fee_id"]').value = '';
        document.querySelector('#feeStructureModal .modal-title').textContent = 'Add Fee Structure';
        document.getElementById('saveFeeStructureBtn').textContent = 'Save Fee';
    });


});
