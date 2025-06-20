<?php $page = 'shift'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Learners Corner</h4>
                    <h6>Manage your learners</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            @hasanyrole('admin|developer|manager|director|supervisor')
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal"><i class="ti ti-circle-plus me-1"></i>Add Learner</a>
            </div>
            @endhasanyrole
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Select Status
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Active</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Inactive</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Sort By : Last 7 Days
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recently Added</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Ascending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Learner Name</th> 
                                <th>ADM No</th>
                                <th>Class</th>
                                <th>Term</th>
                                <th>Gender</th>
                                <th>Status</th>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <th>Fee Balance</th>
                                <th class="no-sort"></th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</span>
                                    </td>
                                    <td>{{ $student->student_reg_number }}</td>
                                    <td>{{ $student->class->level->level_name ?? 'N/A' }} {{ $student->class->stream->initials ?? '' }}</td>
                                    <td>{{ $student->term->term_name ?? 'N/A' }}</td>
                                    <td>{{ $student->gender }}</td>
                                    <td>
                                        @if($student->status)
                                            <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                                <i class="ti ti-point-filled me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger d-inline-flex align-items-center badge-xs">
                                                <i class="ti ti-point-filled me-1"></i>Inactive
                                            </span>
                                        @endif
                                    </td>
                                    @hasanyrole('admin|developer|manager|director|supervisor')
                                    <td>{{ number_format($student->current_balance, 2) ?? 'N/A'}}</td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                        
                                        </div>
                                    </td>
                                    @endhasanyrole
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /product list -->
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div> 
<!-- Student Registration Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl"> 
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTitle"><b>Student Registration Form</b></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form method="POST" action="{{ route('students.store') }}" id="registerForm">
                  @csrf
                  <input type="hidden" name="student_id" value="">

                  <h4 class="mb-3"><b>Student Details</b></h4>
                  <div class="row">
                      <div class="col-md-6">
                          <label class="form-label"><b>First Name</b></label>
                          <input type="text" class="form-control" name="first_name" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Second Name</b></label>
                          <input type="text" class="form-control" name="second_name">
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Last Name</b></label>
                          <input type="text" class="form-control" name="last_name" required>
                      </div>
                      <div class="col-md-3">
                          <label class="form-label"><b>Date Of Birth</b></label>
                          <input type="date" class="form-control" name="student_age" required>
                      </div>
                      <div class="col-md-3">
                          <label class="form-label"><b>Class</b></label>
                          <select class="form-control select2" name="student_class" required>
                              <option value="">Select Class</option>
                              @foreach($classes as $class)
                                  <option value="{{ $class->id }}">
                                 {{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }}
                                  </option>
                              @endforeach
                          </select>
                      </div>

                      <div class="col-md-6">
                          <label class="form-label"><b>Registration Number</b></label>
                          <input type="text" class="form-control" name="student_reg_number" required>
                      </div>
                      <div class="col-md-3">
                          <label class="form-label"><b>Student Status</b></label>
                          <select class="form-select" name="studentStatus" required>
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label class="form-label"><b>Term</b></label>
                          <select class="form-select" name="studentTerm" required>
                              <option value="">Select Term</option>
                              @foreach($terms as $term)
                                  <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                              @endforeach
                          </select>
                      </div>

                      <div class="col-md-6">
                          <label class="form-label"><b>Gender</b></label>
                          <select class="form-select" name="studentGender" required>
                              <option value="male">Male</option>
                              <option value="female">Female</option>
                              <option value="other">Other</option>
                          </select>
                      </div>

                      <div class="col-md-6">
                          <label class="form-label"><b>About</b></label>
                          <textarea class="form-control" name="student_about" rows="2"></textarea>
                      </div>

                      <div class="col-md-6">
                          <div class="form-check mb-2">
                              <input class="form-check-input" type="checkbox" id="needs_meals" name="needs_meals" onchange="toggleMealFields()">
                              <label class="form-check-label" for="needs_meals">Student requires school meals</label>
                          </div>
                          <div id="meal_fields" style="display: none;">
                              <label><b>Select Meal Plan</b></label>
                              <select name="meal_plan_id" class="form-control" id="meal_plan_id" onchange="updateMealFeeDisplay()">
                                  <option value="">Select Meal Plan</option>
                                  @foreach($mealPlans as $meal)
                                      <option value="{{ $meal->id }}" data-fee="{{ $meal->fee }}">
                                          {{ $meal->plan_name }} - KSh {{ number_format($meal->fee, 2) }}
                                      </option>
                                  @endforeach
                              </select>
                              <div class="mt-2">
                                  <strong>Meal Fee:</strong> <span id="meal_fee_display">KSh 0.00</span>
                              </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-check mb-2">
                              <input class="form-check-input" type="checkbox" id="needs_transport" name="needs_transport" onchange="toggleTransportFields()">
                              <label class="form-check-label" for="needs_transport">Student requires school transport</label>
                          </div>
                          <div id="transport_fields" style="display: none;">
                              <label><b>Transport Type</b></label>
                              <select name="transport_option" class="form-control mb-2" id="transport_option" onchange="calculateTransportFee()">
                                  <option value="two_way">Two Way</option>
                                  <option value="one_way">One Way</option>
                              </select>

                              <label><b>Select Route</b></label>
                              <select name="transport_route_id" class="form-control mb-2" id="transport_route_id" onchange="calculateTransportFee()">
                                  <option value="">Select Route</option>
                                  @foreach($transportRoutes as $route)
                                      <option value="{{ $route->id }}" data-fee="{{ $route->fee }}">
                                          {{ $route->route_name }} - KSh {{ number_format($route->fee, 2) }}
                                      </option>
                                  @endforeach
                              </select>
                              <div class="mt-2">
                                  <strong>Estimated Transport Fee:</strong> <span id="transport_fee_display">KSh 0.00</span>
                              </div>
                          </div>
                      </div>
                  </div>

                  <hr class="my-4">

                    <h4 class="mb-3"><b>Subjects Enrollment</b></h4>
                    <div class="mb-3">
                        <label class="form-label">Subjects to Enroll In</label>
                        <div class="row">
                            @foreach($subjects as $subject)
                                <div class="col-md-3"> {{-- 4 columns per row (12/3 = 4) --}}
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="studentSubjects[]" value="{{ $subject->id }}" id="subject_{{ $subject->id }}">
                                        <label class="form-check-label" for="subject_{{ $subject->id }}">{{ $subject->subject_name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                  <hr class="my-4">

                  <h4 class="mb-3"><b>Parent / Guardian Details</b></h4>
                  <div class="row">
                      <div class="col-md-6">
                          <label class="form-label"><b>First Name</b></label>
                          <input type="text" class="form-control" name="guardian_first_name" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Last Name</b></label>
                          <input type="text" class="form-control" name="guardian_last_name" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Relationship to Student</b></label>
                          <select class="form-select" name="guardian_relationship" required>
                              <option value="Father">Father</option>
                              <option value="Mother">Mother</option>
                              <option value="Other Relative">Other Relative</option>
                              <option value="Guardian">Guardian</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>First Phone Number</b></label>
                          <input type="text" class="form-control" name="guardian_first_phone" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Second Phone Number</b></label>
                          <input type="text" class="form-control" name="guardian_second_phone">
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>ID Number</b></label>
                          <input type="text" class="form-control" name="id_number" required>
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Email</b></label>
                          <input type="email" class="form-control" name="email">
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>Address</b></label>
                          <input type="text" class="form-control" name="address">
                      </div>
                      <div class="col-md-6">
                          <label class="form-label"><b>About</b></label>
                          <textarea class="form-control" name="guardian_about" rows="2"></textarea>
                      </div>
                  </div>

                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Register</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

<!-- JS to show/hide meal & transport fields -->
<script>
    function toggleMealFields() {
        const checkbox = document.getElementById('needs_meals');
        const mealSection = document.getElementById('meal_fields');
        mealSection.style.display = checkbox.checked ? 'block' : 'none';
        updateMealFeeDisplay();
    }

    function updateMealFeeDisplay() {
        const mealSelect = document.getElementById('meal_plan_id');
        const selectedOption = mealSelect.options[mealSelect.selectedIndex];
        const fee = parseFloat(selectedOption.getAttribute('data-fee')) || 0;
        document.getElementById('meal_fee_display').innerText = 'KSh ' + fee.toFixed(2);
    }

    function toggleTransportFields() {
        const checkbox = document.getElementById('needs_transport');
        const transportSection = document.getElementById('transport_fields');
        transportSection.style.display = checkbox.checked ? 'block' : 'none';
        calculateTransportFee();
    }

    function calculateTransportFee() {
        const transportOption = document.getElementById('transport_option').value;
        const routeSelect = document.getElementById('transport_route_id');
        const selectedRoute = routeSelect.options[routeSelect.selectedIndex];
        const baseFee = parseFloat(selectedRoute.getAttribute('data-fee')) || 0;
        
        let calculatedFee = 0;
        if (transportOption === 'two_way') {
            calculatedFee = baseFee;
        } else if (transportOption === 'one_way') {
            calculatedFee = (baseFee / 2) + 500;
        }

        document.getElementById('transport_fee_display').innerText = 'KSh ' + calculatedFee.toFixed(2);
    }
</script>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
    let days = [];

    ['day4', 'day5', 'day6', 'day7'].forEach((id) => {
        if (document.getElementById(id).checked) {
            let sessions = [];
            document.querySelectorAll(`#${id} ~ .status-label`)
                .forEach((el) => {
                    let wrapper = el.closest('tr').querySelectorAll('.modal-table-check input');
                    wrapper.forEach((chk, index) => {
                        if (chk.checked) sessions.push(chk.parentElement.innerText.trim());
                    });
                });

            days.push({ name: document.querySelector(`label[for=${id}] + span`).innerText.trim(), sessions });
        }
    });

    // Append to form as hidden input
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'days';
    input.value = JSON.stringify(days);
    this.appendChild(input);
});


</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('[data-day]').forEach(dayContainer => {
            const allBox = dayContainer.querySelector('.check-all');
            const sessionBoxes = dayContainer.querySelectorAll('.session-check');

            allBox.addEventListener('change', () => {
                sessionBoxes.forEach(cb => cb.checked = allBox.checked);
            });

            sessionBoxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    allBox.checked = [...sessionBoxes].every(input => input.checked);
                });
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mondayRow = document.querySelector('#days1').closest('tr'); // Get the whole row for Monday
        const allCheckbox = mondayRow.querySelector('label:has(span:contains("All")) input[type="checkbox"]');
        const sessionCheckboxes = Array.from(mondayRow.querySelectorAll('label:has(span:not(:contains("All"))) input[type="checkbox"]'));

        // When "All" is clicked
        allCheckbox.addEventListener('change', function () {
            sessionCheckboxes.forEach(cb => cb.checked = this.checked);
        });

        // When any session checkbox is clicked
        sessionCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                allCheckbox.checked = sessionCheckboxes.every(cb => cb.checked);
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const shift_name = this.dataset.shift_name;
            const start_time = this.dataset.start_time;
            const end_time = this.dataset.end_time;
            const morning_from = this.dataset.morning_from;
            const morning_to = this.dataset.morning_to;
            const evening_from = this.dataset.evening_from;
            const evening_to = this.dataset.evening_to;
            const lunch_from = this.dataset.lunch_from;
            const lunch_to = this.dataset.lunch_to;
            const day_off = this.dataset.day_off;
            const description = this.dataset.description;
            const status = this.dataset.status;
            const recurring = this.dataset.recurring;
            const days = this.dataset.days.split(','); // Make sure you're using `this.dataset.days`

            document.querySelectorAll('.check').forEach(cb => {
                cb.checked = days.includes(cb.value); });
            document.getElementById('edit-id').value = id;
            document.getElementById('shift_name').value = shift_name;
            document.getElementById('start_time').value = start_time;
            document.getElementById('end_time').value = end_time;
            document.getElementById('day_off').value = day_off;
            document.getElementById('morning_from').value = morning_from;
            document.getElementById('lunch_from').value = lunch_from;
            document.getElementById('evening_from').value = evening_from;
            document.getElementById('morning_to').value = morning_to;
            document.getElementById('lunch_to').value = lunch_to;
            document.getElementById('evening_to').value = evening_to;
            document.getElementById('description').value = description;
            document.getElementById('status').checked = status == 1;
            document.getElementById('recurring').checked = recurring == 1;

            // Set form action dynamically
            document.getElementById('editForm').action = `/shift/${id}`;
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        document.getElementById('delete-id').value = id;
                    });
                });
            });
</script>


@endsection
