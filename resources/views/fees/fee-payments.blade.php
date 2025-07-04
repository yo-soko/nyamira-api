@extends('layout.mainlayout')


@section('content')
<div class="page-wrapper">
    <div class="content">
    @include('layout.toast')
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Fee Payments</h4>
                <h6>Manage Student Payments</h6>
            </div>
            @hasanyrole('admin|developer|manager|director|supervisor')
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Fee Payment
                </a>
            </div>
            @endhasanyrole
        </div>




        <div class="card">


            <div class="card-body p-0">

            <!-- <div class="text-center mb-3">
                <h5 class="fw-bold">Filter Student Payment History</h5>
            </div> -->
            {{-- Filter form --}}
            <!-- <form method="GET" action="{{ route('fee-payments.index') }}" class="row justify-content-center g-3 mb-4">
                <div class="col-md-4">
                    <label for="filter_class_id" class="form-label">Stream</label>
                    <select id="filter_class_id" name="filter_class_id" class="form-select">
                        <option value="">-- All Streams --</option>
                        @foreach($classLevels as $class)
                            <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->level->level_name ?? 'No Level' }} - {{ $class->stream->name ?? 'No Stream' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="filter_student_id" class="form-label">Student</label>
                    <select id="filter_student_id" name="student_id" class="form-select" o>
                        <option value="">-- All Students --</option>
                        {{-- JS will populate this --}}
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form> -->


            @if(request('student_id'))
                <div class="alert alert-info text-center">
                    Showing payment history for <strong>{{ $students->firstWhere('id', request('student_id'))?->full_name ?? 'Selected Student' }}</strong>
                </div>
            @endif

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payments Table</h5>

                <div>
                    <a href="#" data-bs-toggle="tooltip" title="PDF"><img src="{{ asset('build/img/icons/pdf.svg') }}" alt="PDF"></a>
                    <a href="#" data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('build/img/icons/excel.svg') }}" alt="Excel"></a>
                    <a href="#" data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a>
                </div>
            </div>

                <div class="table-responsive">
                    @include('fees.partials.payment_table')
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {


        // Filter Section Elements
        const filterClassSelect = document.getElementById('filter_class_id');
        const filterStudentSelect = document.getElementById('filter_student_id');

        function loadFilteredStudents(classId) {
            filterStudentSelect.innerHTML = '<option value="">-- All Students --</option>';
            if (!classId) return;

            fetch(`/filter/students/by-class/${classId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.full_name;

                        if (student.id == "{{ request('student_id') }}") {
                            option.selected = true;
                        }

                        filterStudentSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Failed to load students:', error);
                });
        }

        if (filterClassSelect.value) {
            loadFilteredStudents(filterClassSelect.value);
        }

        filterClassSelect.addEventListener('change', function () {
            loadFilteredStudents(this.value);
        });


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


