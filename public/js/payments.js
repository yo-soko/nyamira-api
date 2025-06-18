document.addEventListener('DOMContentLoaded', function () {
    const paymentForm = document.getElementById('paymentForm');

    document.querySelectorAll('.editPaymentBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch(`/fee-payments/${id}`)
                .then(res => res.json())
                .then(data => {
                    const payment = data.data;
                    paymentForm.action = `/fee-payments/${payment.id}`;
                    paymentForm.querySelector('[name="class_id"]').value = payment.class_id;
                    paymentForm.querySelector('[name="term_id"]').value = payment.term_id;
                    paymentForm.querySelector('[name="student_id"]').value = payment.student_id;
                    paymentForm.querySelector('[name="receipt_number"]').value = payment.receipt_number;
                    paymentForm.querySelector('[name="amount_paid"]').value = payment.amount_paid;
                    document.getElementById('payment_id').value = payment.id;
                    new bootstrap.Modal(document.getElementById('addPaymentModal')).show();
                });
        });
    });

    document.querySelectorAll('.deletePaymentBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            if (confirm('Are you sure you want to delete this payment?')) {
                fetch(`/fee-payments/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                }).then(() => location.reload());
            }
        });
    });

    paymentForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(paymentForm);
        const id = document.getElementById('payment_id').value;
        const method = id ? 'PUT' : 'POST';
        const url = id ? `/fee-payments/${id}` : '/fee-payments';

        fetch(url, {
            method: method,
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(res => res.json())
        .then(() => location.reload());
    });
});
