@useronly
<?php $page = 'edit-employee'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Edit Employee</h4>
                        <h6>Edit Employee</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="{{url('employees-list')}}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to List</a>
                </div>
            </div>

            <form action="{{ route('update-employee', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <div class="accordion-card-one accordion" id="accordionExample">
                    <div class="accordion-item">
                        <div class="accordion-header p-3" id="headingOne">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"  aria-controls="collapseOne">
                                <div class="addproduct-icon d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="info" class="feather-edit text-primary me-2"></i><span>Employee Information</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="new-employee-field">
                               <div class="profile-pic-upload edit-pic">
                                    <div class="profile-pic text-center">
                                        <img id="preview-image"
                                            src="{{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : asset('default-avatar.png') }}"
                                            alt="Profile Image"
                                            style="max-width: 120px; border-radius: 50%;">
                                        <span style="cursor:pointer;" onclick="document.getElementById('profilePhoto').click();">
                                        </span>
                                    </div>

                                    <div class="input-blocks mb-0">
                                        <div class="image-upload mb-0">
                                            <input type="file" name="profile_photo" id="profilePhoto" accept="image/*" onchange="previewImage(this)" hidden>
                                            <div class="image-uploads" style="cursor:pointer;" onclick="document.getElementById('profilePhoto').click();">
                                                <h4>Change Image</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $employee->first_name) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $employee->last_name) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $employee->email) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Contact Number<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="contact_number" value="{{ old('contact_number', $employee->contact_number) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Emp Code<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="emp_code" value="{{ old('emp_code', $employee->emp_code) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks">
                                            <label class="form-label">Date of Birth<span class="text-danger ms-1">*</span></label>
                                            <div class="input-groupicon calender-input">
                                                <i data-feather="calendar" class="info-img"></i>
                                                <input type="text" class="datetimepicker form-control ps-2" name="dob" value="{{ old('dob') ? \Carbon\Carbon::parse(old('dob'))->format('d-m-Y') : \Carbon\Carbon::parse($employee->dob)->format('d-m-Y') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gender<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="gender">
                                                <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nationality<span class="text-danger ms-1">*</span></label>
                                            <select class="select">
                                                <option value="Kenya" {{ old('nationality', $employee->nationality) == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                                <option value="Other" {{ old('nationality', $employee->nationality) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks">
                                            <label class="form-label">Joining Date<span class="text-danger ms-1">*</span></label>
                                            <div class="input-groupicon calender-input">
                                                <i data-feather="calendar" class="info-img"></i>
                                                <input type="text" class="datetimepicker form-control ps-2" name="joining_date" value="{{ old('joining_date') ? \Carbon\Carbon::parse(old('joining_date'))->format('d-m-Y') : \Carbon\Carbon::parse($employee->joining_date)->format('d-m-Y') }}">
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <div class="add-newplus">
                                                <label class="form-label">Shift<span class="text-danger ms-1">*</span></label>
                                                <a href="{{ url('shift') }}"><span><i data-feather="plus-circle" class="plus-down-add"></i>Add new</span></a>
                                            </div>
                                            <select class="select" name="shift">
                                                <option value="">Select</option>
                                                @foreach($shifts as $shift)
                                                    <option value="{{ $shift->shift_name }}" 
                                                        {{ old('shift', $employee->shift) == $shift->shift_name ? 'selected' : '' }}>
                                                        {{ $shift->shift_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <div class="add-newplus">
                                                <label class="form-label">Department<span class="text-danger ms-1">*</span></label>
                                                <a href="{{ url('department-grid') }}"><span><i data-feather="plus-circle" class="plus-down-add"></i>Add new</span></a>
                                            </div>
                                            <select class="select" name="department">
                                                <option value="">Select</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->name }}" 
                                                        {{ old('department', $employee->department) == $department->name ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <div class="add-newplus">
                                                <label class="form-label">Designation<span class="text-danger ms-1">*</span></label>
                                                <a href="{{ url('designation') }}"><span><i data-feather="plus-circle" class="plus-down-add"></i>Add new</span></a>
                                            </div>
                                            <select class="select" name="designation">
                                                <option value="">Select</option>
                                                @foreach($designations as $designation)
                                                    <option value="{{ $designation->designation }}" 
                                                        {{ old('designation', $employee->designation) == $designation->designation ? 'selected' : '' }}>
                                                        {{ $designation->designation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Blood Group<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="blood_group">
                                                <option value="">Select</option>
                                                <option value="N/A" {{ old('blood_group', $employee->blood_group) == 'N/A' ? 'selected' : '' }}>N/A</option>
                                                <option value="A+" {{ old('blood_group', $employee->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_group', $employee->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_group', $employee->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_group', $employee->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="O+" {{ old('blood_group', $employee->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_group', $employee->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                                <option value="AB+" {{ old('blood_group', $employee->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_group', $employee->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                            <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                <span class="status-label">Status</span>
                                                <input type="checkbox" id="user6" class="check" name="status" value="1" {{ $employee->status ? 'checked' : '' }}>
                                                <label for="user6" class="checktoggle mb-0"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Editor -->
                                    <div class="col-lg-12">
                                        <div class="input-blocks summer-description-box transfer mb-3">
                                            <label>About</label>
                                            <textarea id="summernote" name="about">{{ old('about', $employee->about) }}</textarea>
                                            <p class="mt-1">Maximum 60 Characters</p>
                                        </div>
                                    </div>
                                    <!-- /Editor -->
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header p-3" id="heading3">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseThree"  aria-controls="collapseThree">
                                <div class="addproduct-icon d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="map-pin" class="feather-edit text-primary me-2"></i><span>Address Information</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="heading3" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="other-info">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address" value="{{ old('address', $employee->address) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Country</label>
                                            <select class="select">
                                                <option value="Kenya" {{ old('country', $employee->country) == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                                <option value="Other" {{ old('country', $employee->country) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Zipcode</label>
                                            <input type="text" class="form-control" name="zipcode" value="{{ old('zipcode', $employee->zipcode) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header p-3" id="heading4">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFour"  aria-controls="collapseFour">
                                <div class="addproduct-icon d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="info" class="feather-edit text-primary me-2"></i><span>Emergency Information</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="heading4" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="other-info">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Emergency Contact Number 1</label>
                                            <input type="text" class="form-control" name="emergency_contact1" value="{{ old('emergency_contact1', $employee->emergency_contact1) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Relation</label>
                                            <input type="text" class="form-control" name="emergency_relation1" value="{{ old('emergency_relation1', $employee->emergency_relation1) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="emergency_name1" value="{{ old('emergency_name1', $employee->emergency_name1) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Emergency Contact Number 2</label>
                                            <input type="text" class="form-control" name="emergency_contact2" value="{{ old('emergency_contact2', $employee->emergency_contact2) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Relation</label>
                                            <input type="text" class="form-control" name="emergency_relation2" value="{{ old('emergency_relation2', $employee->emergency_relation2) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="emergency_name1" value="{{ old('emergency_name2', $employee->emergency_name2) }}">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header p-3" id="heading5">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFive"  aria-controls="collapseFive">
                                <div class="addproduct-icon d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i class="ti ti-building-bank feather-edit text-primary me-2"></i><span>Bank Information</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="heading5" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="other-info">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" class="form-control" name="account_number" value="{{ old('account_number', $employee->account_number) }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Branch</label>
                                            <input type="text" class="form-control" name="branch" value="{{ old('branch', $employee->branch) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header p-3" id="heading6">
                            <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"  aria-controls="collapseTwo">
                                <div class="addproduct-icon d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="info" class="feather-edit text-primary me-2"></i><span>Password</span></h5>
                                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                                </div>
                            </div>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="heading6" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="pass-info">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks mb-md-0 mb-sm-3">
                                            <label>Password</label>
                                            <div class="pass-group">
                                                <input type="password" name="password" class="pass-input">
                                                <span class="fas toggle-password fa-eye-slash"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks mb-0">
                                            <label>Confirm Password</label>
                                            <div class="pass-group">
                                                <input type="password" name="password_confirmation" class="pass-inputa">
                                                <span class="fas toggle-passworda fa-eye-slash"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                </div>
                <!-- /product list -->

                <div class="text-end mb-3">
                    <button type="button" class="btn btn-secondary me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>
    <script>
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                document.getElementById('preview-image').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
@enduseronly