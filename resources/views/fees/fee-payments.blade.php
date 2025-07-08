@extends('layout.mainlayout')


@section('content')
<div class="page-wrapper">
    <div class="content">
    <!-- @include('layout.toast') -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Fee Payments</h4>
                <h6>Manage Student Payments</h6>
            </div>
            @hasanyrole('admin|developer|manager|director|supervisor')
            <div class="page-btn d-flex gap-2">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Fee Payment
                </a>

                <a href="{{ route('print.fee.balances') }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-printer me-1"></i> Print Balances
                </a>
            </div>

            @endhasanyrole
        </div>




        <div class="card">


            <div class="card-body p-0">



            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payments Table</h5>

                <div>
                    <a href="#" data-bs-toggle="tooltip" title="PDF"><img src="{{ asset('build/img/icons/pdf.svg') }}" alt="PDF"></a>
                    <a href="#" data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('build/img/icons/excel.svg') }}" alt="Excel"></a>
                    <a href="#" data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a>
                </div>
            </div>
                <form method="GET" action="{{ route('fee-payments.index') }}" class="mb-3 row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search student..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
                @if(request('search'))
                    <div class="alert alert-info">
                        Showing results for "<strong>{{ request('search') }}</strong>".
                        <a href="{{ route('fee-payments.index') }}">Clear search</a>
                    </div>
                @endif



                <div class="table-responsive">
                    @include('fees.partials.payment_table')
                </div>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {


        const addPaymentForm = document.getElementById('addPaymentForm');
        const savePaymentBtn = document.getElementById('savePaymentBtn');

        addPaymentForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            savePaymentBtn.disabled = true;
            savePaymentBtn.textContent = 'Saving...';

            const formData = new FormData(addPaymentForm);

            fetch(addPaymentForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error("Network response not ok");
                return res.json(); // Expecting JSON response
            })

            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addPaymentModal'));
                    modal.hide();

                    // Reset form
                    addPaymentForm.reset();

                    // // Show success toast
                    // const toastEl = document.getElementById('successToast');
                    // const toast = new bootstrap.Toast(toastEl);
                    // toast.show();

                    // Show success toast
                    const toastEl = document.createElement('div');
                    toastEl.className = 'toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 p-3';
                    toastEl.role = 'alert';
                    toastEl.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">Payment saved successfully!</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    `;
                    document.body.appendChild(toastEl);
                    new bootstrap.Toast(toastEl).show();
                    setTimeout(() => toastEl.remove(), 2000); //



                    // Refresh the payments table
                    fetch("{{ route('fee-payments.index') }}")
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, "text/html");
                            const newTableHtml = doc.querySelector(".table-responsive").innerHTML;
                            document.querySelector(".table-responsive").innerHTML = newTableHtml;
                        });

                } else {
                    alert('Something went wrong: ' + (data.message || 'Unknown error'));
                }
            })


            .catch(err => {
                alert('Submission failed: ' + err.message);
            })
            .finally(() => {
                savePaymentBtn.disabled = false;
                savePaymentBtn.textContent = 'Save Payment';
            });
        });

        //end
        let classSelect = document.getElementById('class_id');
        let termSelect = document.getElementById('term_id');
        let studentSelect = document.getElementById('student_id');
        let balanceInput = document.getElementById('outstanding_balance');

        function fetchStudents() {
            const classId = classSelect.value;
            const termId = termSelect.value;

            if (classId && termId) {
                fetch("{{ route('get.students') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ class_id: classId, term_id: termId })
                })
                .then(res => res.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
                    data.forEach(student => {
                        studentSelect.innerHTML += `<option value="${student.id}">${student.name}</option>`;
                    });
                });
            }
        }

        function fetchBalance() {
            const classId = classSelect.value;
            const termId = termSelect.value;
            const studentId = studentSelect.value;

            if (classId && termId && studentId) {
                fetch("{{ route('get.balance') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        class_id: classId,
                        term_id: termId,
                        student_id: studentId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    balanceInput.value = data.balance;
                });
            }
        }

        function fetchPaymentOptions() {
            const classId = classSelect.value;
            const termId = termSelect.value;
            const studentId = studentSelect.value;
            const descriptionSelect = document.getElementById('description');

            descriptionSelect.innerHTML = '<option value="">Loading...</option>';

            if (classId && termId && studentId) {
                fetch("{{ route('get.payment.options') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        class_id: classId,
                        term_id: termId,
                        student_id: studentId
                    })
                })
                .then(res => res.json())
                .then(options => {
                    descriptionSelect.innerHTML = '<option value="">-- Select Payment Purpose --</option>';
                    options.forEach(option => {
                        let value = option;
                        descriptionSelect.innerHTML += `<option value="${value}">${option}</option>`;
                    });

                    // Pre-select "Tuition Fee" if it exists
                    if (options.includes('Tuition Fee')) {
                        descriptionSelect.value = 'Tuition Fee';
                    }
                })
                .catch(err => {
                    console.error("Failed to fetch payment options:", err);
                    descriptionSelect.innerHTML = '<option value="">-- Error Loading --</option>';
                });
            }
        }


        classSelect.addEventListener('change', fetchStudents);
        termSelect.addEventListener('change', fetchStudents);
        studentSelect.addEventListener('change', function () {
            fetchBalance();
            fetchPaymentOptions();
        });

    });
    </script>



    {{-- Footer --}}
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3 mt-4">
        <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

{{-- Add Payment Modal --}}
@include('fees.partials.payment_modal')



@endsection




