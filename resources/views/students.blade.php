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
                                            <a href="#" class="edit-btn"
                                                data-id="{{ $student->id }}"
                                                data-first_name="{{ $student->first_name }}"
                                                data-second_name="{{ $student->second_name }}"
                                                data-last_name="{{ $student->last_name }}"
                                                data-reg="{{ $student->student_reg_number }}"
                                                data-age="{{ $student->student_age }}"
                                                data-class="{{ $student->class_id }}"
                                                data-status="{{ $student->status }}"
                                                data-term="{{ $student->term_id }}"
                                                data-gender="{{ $student->gender }}"
                                                data-about="{{ $student->about }}"
                                                data-meals="{{ $student->meal ? '1' : '0' }}"
                                                data-meal_id="{{ $student->meal->meal_plan_id ?? '-' }}"
                                                data-meal_fee="{{ $student->meal->meal_fee ?? '0' }}"
                                                data-transport="{{ $student->transport ? '1' : '0' }}"
                                                data-subjects="{{ json_encode($student->subjects->pluck('id')) }}"
                                                data-transport_option="{{ $student->transport->transport_type ?? '' }}"
                                                data-transport_route="{{ $student->transport->route_id ?? '' }}"
                                                data-guardian_first="{{ $student->guardian->guardian_first_name ?? '-'  }}"
                                                data-guardian_last="{{ $student->guardian->guardian_last_name ?? '-'  }}"
                                                data-relationship="{{ $student->guardian->guardian_relationship ?? '-'  }}"
                                                data-phone1="{{ $student->guardian->first_phone ?? '-' }}"
                                                data-phone2="{{ $student->guardian->second_phone ?? '-' }}"
                                                data-id_number="{{ $student->guardian->id_number ?? '-'  }}"
                                                data-email="{{ $student->guardian->email ?? '-'  }}"
                                                data-address="{{ $student->guardian->address ?? '-'  }}"
                                                data-guardian_about="{{ $student->guardian->guardian_about ?? '-'  }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>

                                            <a href="javascript:void(0);"
                                                class="delete-btn bg-danger                                                                                            -danger"
                                                data-id="{{ $student->id }}"
                                                data-bs-target="#delete-modal"
                                                data-bs-toggle="modal">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
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
<!-- edit student -->
 <!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form method="POST" action="{{ route('students.update') }}" id="editStudentForm">
        @csrf
        @method('PUT')

        <input type="hidden" name="student_id" id="edit_student_id">

        <div class="modal-header">
          <h5 class="modal-title"><b>Edit Student Details</b></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <h4><b>Student Details</b></h4>
          <div class="row">
            <!-- Name Fields -->
            <div class="col-md-6">
              <label class="form-label"><b>First Name</b></label>
              <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Second Name</b></label>
              <input type="text" class="form-control" name="second_name" id="edit_second_name">
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Last Name</b></label>
              <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
            </div>
            <div class="col-md-3">
              <label class="form-label"><b>Date Of Birth</b></label>
              <input type="date" class="form-control" name="student_age" id="edit_student_age" required>
            </div>
            <div class="col-md-3">
              <label class="form-label"><b>Class</b></label>
              <select class="form-control select2" name="student_class" id="edit_student_class" required>
                <option value="">Select Class</option>
                @foreach($classes as $class)
                  <option value="{{ $class->id }}">{{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label"><b>Registration Number</b></label>
              <input type="text" class="form-control" name="student_reg_number" id="edit_student_reg_number" required>
            </div>
            <div class="col-md-3">
              <label class="form-label"><b>Status</b></label>
              <select class="form-select" name="studentStatus" id="edit_student_status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label"><b>Term</b></label>
              <select class="form-select" name="studentTerm" id="edit_student_term" required>
                <option value="">Select Term</option>
                @foreach($terms as $term)
                  <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Gender</b></label>
              <select class="form-select" name="studentGender" id="edit_student_gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>About</b></label>
              <textarea class="form-control" name="student_about" id="edit_student_about" rows="2"></textarea>
            </div>

            <!-- Meals -->
            <div class="col-md-6">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="edit_needs_meals" name="needs_meals" value="1" onchange="toggleEditMealFields()">
                <label class="form-check-label" for="edit_needs_meals">Student requires school meals</label>
              </div>
              <div id="edit_meal_fields" style="display: none;">
                <label><b>Select Meal Plan</b></label>
                <select name="meal_plan_id" class="form-control" id="edit_meal_plan_id" onchange="updateEditMealFeeDisplay()">
                  <option value="">Select Meal Plan</option>
                  @foreach($mealPlans as $meal)
                    <option value="{{ $meal->id }}" data-fee="{{ $meal->fee }}">{{ $meal->plan_name }} - KSh {{ number_format($meal->fee, 2) }}</option>
                  @endforeach
                </select>
                <div class="mt-2">
                  <strong>Meal Fee:</strong> <span id="edit_meal_fee_display">KSh 0.00</span>
                </div>
              </div>
            </div>

            <!-- Transport -->
            <div class="col-md-6">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="edit_needs_transport" name="needs_transport" value="1"  onchange="toggleEditTransportFields()">
                <label class="form-check-label" for="edit_needs_transport">Student requires school transport</label>
              </div>
              <div id="edit_transport_fields" style="display: none;">
                <label><b>Transport Type</b></label>
                <select name="transport_option" class="form-control mb-2" id="edit_transport_option" onchange="calculateEditTransportFee()">
                  <option value="two_way">Two Way</option>
                  <option value="one_way">One Way</option>
                </select>

                <label><b>Select Route</b></label>
                <select name="transport_route_id" class="form-control mb-2" id="edit_transport_route_id" onchange="calculateEditTransportFee()">
                  <option value="">Select Route</option>
                  @foreach($transportRoutes as $route)
                    <option value="{{ $route->id }}" data-fee="{{ $route->fee }}">{{ $route->route_name }} - KSh {{ number_format($route->fee, 2) }}</option>
                  @endforeach
                </select>
                <div class="mt-2">
                  <strong>Estimated Transport Fee:</strong> <span id="edit_transport_fee_display">KSh 0.00</span>
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

          <h4><b>Parent / Guardian Details</b></h4>
          <div class="row">
            <div class="col-md-6">
              <label class="form-label"><b>First Name</b></label>
              <input type="text" class="form-control" name="guardian_first_name" id="edit_guardian_first_name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Last Name</b></label>
              <input type="text" class="form-control" name="guardian_last_name" id="edit_guardian_last_name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Relationship</b></label>
              <select class="form-select" name="guardian_relationship" id="edit_guardian_relationship" required>
                <option value="Father">Father</option>
                <option value="Mother">Mother</option>
                <option value="Other Relative">Other Relative</option>
                <option value="Guardian">Guardian</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>First Phone Number</b></label>
              <input type="text" class="form-control" name="guardian_first_phone" id="edit_guardian_first_phone" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Second Phone Number</b></label>
              <input type="text" class="form-control" name="guardian_second_phone" id="edit_guardian_second_phone">
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>ID Number</b></label>
              <input type="text" class="form-control" name="id_number" id="edit_id_number" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Email</b></label>
              <input type="email" class="form-control" name="email" id="edit_email">
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>Address</b></label>
              <input type="text" class="form-control" name="address" id="edit_address">
            </div>
            <div class="col-md-6">
              <label class="form-label"><b>About</b></label>
              <textarea class="form-control" name="guardian_about" id="edit_guardian_about" rows="2"></textarea>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
   <div class="modal fade" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content p-5 px-3 text-center">
                    <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2">
                        <i class="ti ti-trash fs-24 text-danger"></i>
                    </span>
                    <h4 class="fs-20 text-gray-9 fw-bold mb-2 mt-1">Delete this Learner</h4>
                    <p class="text-gray-6 mb-0 fs-16">Are you sure you want to delete this Learner?</p>
                    <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                        <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteStudentForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger fs-13 fw-medium p-2 px-3">Yes Delete</button>
                        </form>
                    </div>						
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleEditMealFields() {
        const mealCheck = document.getElementById('edit_needs_meals');
        document.getElementById('edit_meal_fields').style.display = mealCheck.checked ? 'block' : 'none';
        updateEditMealFeeDisplay();
    }

    function updateEditMealFeeDisplay() {
        const mealSelect = document.getElementById('edit_meal_plan_id');
        const selectedOption = mealSelect.options[mealSelect.selectedIndex];
        const fee = parseFloat(selectedOption.getAttribute('data-fee')) || 0;
        document.getElementById('edit_meal_fee_display').innerText = 'KSh ' + fee.toFixed(2);
    }

    function toggleEditTransportFields() {
        const transportCheck = document.getElementById('edit_needs_transport');
        document.getElementById('edit_transport_fields').style.display = transportCheck.checked ? 'block' : 'none';
        calculateEditTransportFee();
    }

    function calculateEditTransportFee() {
        const option = document.getElementById('edit_transport_option').value;
        const routeSelect = document.getElementById('edit_transport_route_id');
        const route = routeSelect.options[routeSelect.selectedIndex];
        const baseFee = parseFloat(route.getAttribute('data-fee')) || 0;
        let fee = option === 'two_way' ? baseFee : (baseFee / 2) + 500;
        document.getElementById('edit_transport_fee_display').innerText = 'KSh ' + fee.toFixed(2);
    }


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
  const deleteButtons = document.querySelectorAll('.delete-btn');
  const deleteForm = document.getElementById('deleteStudentForm');

  deleteButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const studentId = btn.getAttribute('data-id');
      // Update form action dynamically, assuming route: students.destroy
      deleteForm.action = `/students/${studentId}`;
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Basic student info
            document.getElementById('edit_student_id').value = this.dataset.id;
            document.getElementById('edit_first_name').value = this.dataset.first_name;
            document.getElementById('edit_second_name').value = this.dataset.second_name || '';
            document.getElementById('edit_last_name').value = this.dataset.last_name;
            document.getElementById('edit_student_reg_number').value = this.dataset.reg;
            document.getElementById('edit_student_age').value = this.dataset.age;
            document.getElementById('edit_student_class').value = this.dataset.class;
            document.getElementById('edit_student_status').value = this.dataset.status;
            document.getElementById('edit_student_term').value = this.dataset.term;
            document.getElementById('edit_student_gender').value = this.dataset.gender;
            document.getElementById('edit_student_about').value = this.dataset.about || '';

            // Guardian info
            document.getElementById('edit_guardian_first_name').value = this.dataset.guardian_first;
            document.getElementById('edit_guardian_last_name').value = this.dataset.guardian_last;
            document.getElementById('edit_guardian_relationship').value = this.dataset.relationship;
            document.getElementById('edit_guardian_first_phone').value = this.dataset.phone1;
            document.getElementById('edit_guardian_second_phone').value = this.dataset.phone2 || '';
            document.getElementById('edit_id_number').value = this.dataset.id_number;
            document.getElementById('edit_email').value = this.dataset.email || '';
            document.getElementById('edit_address').value = this.dataset.address || '';
            document.getElementById('edit_guardian_about').value = this.dataset.guardian_about || '';

            // Meals
            const needsMeals = this.dataset.meals === "1";
            document.getElementById('edit_needs_meals').checked = needsMeals;
            toggleEditMealFields();
            if (needsMeals) {
                document.getElementById('edit_meal_plan_id').value = this.dataset.meal_id;
                updateEditMealFeeDisplay();
            }

            // Transport
            const needsTransport = this.dataset.transport === "1";
            document.getElementById('edit_needs_transport').checked = needsTransport;
            toggleEditTransportFields();
            if (needsTransport) {
                document.getElementById('edit_transport_option').value = this.dataset.transport_option;
                document.getElementById('edit_transport_route_id').value = this.dataset.transport_route;
                calculateEditTransportFee();
            }
            const subjectIds = JSON.parse(this.dataset.subjects || '[]');
              // Uncheck all checkboxes first
            document.querySelectorAll('#editStudentModal input[name="studentSubjects[]"]').forEach(chk => {
                chk.checked = false;
            });

            // Re-check subjects assigned to this student
            subjectIds.forEach(id => {
                const subjectCheckbox = document.querySelector(`#editStudentModal #subject_${id}`);
                if (subjectCheckbox) subjectCheckbox.checked = true;
            });
        });
    });
});
</script>


@endsection
