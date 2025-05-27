@role('Admin')
<?php $page = 'add-employee'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Add Employee</h4>
                        <h6>Create new Employee</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="{{url('employees-list')}}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to List</a>
                </div>
            </div>
            <!-- /product list -->
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                 @csrf
                <div class="accordions-items-seperate" id="accordionExample">
                    <div class="accordion-item border mb-4">
                        <h2 class="accordion-header" id="headingOne">
                            <div class="accordion-button bg-white" data-bs-toggle="collapse" data-bs-target="#collapseOne"  aria-controls="collapseOne">
                                <div class="d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i class="ti ti-users text-primary me-2"></i><span>Employee Information</span></h5>
                                </div>
                            </div>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body border-top">
                            <div class="new-employee-field">
                                <div class="profile-pic-upload">
                                    <div class="profile-pic text-center">
                                        <img id="preview-image">
                                        <span style="cursor:pointer;" onclick="document.getElementById('profilePhoto').click();">
                                            
                                        </span>
                                    </div>

                                    <div class="input-blocks mb-0">
                                        <div class="image-upload mb-0">
                                            <input type="file" name="profile_photo" id="profilePhoto" accept="image/*" onchange="previewImage(this)" hidden>
                                            <div class="image-uploads" style="cursor:pointer;" onclick="document.getElementById('profilePhoto').click();">
                                                <h4>Add Image</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name<span class="text-danger ms-1">*</span></label>
                                            <input type="text" name="first_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name<span class="text-danger ms-1">*</span></label>
                                            <input type="text" name="last_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Contact Number<span class="text-danger ms-1">*</span></label>
                                            <input type="text" name="contact_number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Emp Code<span class="text-danger ms-1">*</span></label>
                                            <input type="text" name="emp_code" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks">
                                            <label class="form-label">Date of Birth<span class="text-danger ms-1">*</span></label>
                                            <div class="input-groupicon calender-input">
                                                <i data-feather="calendar" class="info-img"></i>
                                                <input type="text" name="dob" class="datetimepicker form-control" placeholder="Select Date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Gender<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="gender">
                                                <option>Select</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nationality<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="nationality">
                                                <option>Select</option>
                                                <option value="Kenya">Kenya</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks">
                                            <label>Joining Date<span class="text-danger ms-1">*</span></label>
                                            <div class="input-groupicon calender-input">
                                                <i data-feather="calendar" class="info-img"></i>
                                                <input type="text" name="joining_date" class="datetimepicker form-control" placeholder="Select Date" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <div class="add-newplus">
                                                <label class="form-label">Shift<span class="text-danger ms-1">*</span></label>
                                                <a href="{{url('shift')}}"><span><i data-feather="plus-circle" class="plus-down-add"></i>Add new</span></a>
                                            </div>
                                            <select class="select" name="shift_id">
                                                <option>Select</option>
                                                @foreach($shifts as $shift)
                                                    <option value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                          <div class="add-newplus">
                                            <label class="form-label" for="department">Department<span class="text-danger ms-1">*</span></label>
                                            <a href="{{url('department-grid')}}"><span><i data-feather="plus-circle" class="plus-down-add"></i>Add new</span></a>
                                            </div>
                                            <select class="select" name="department" required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <div class="add-newplus">
                                                <label class="form-label">Designation<span class="text-danger ms-1">*</span></label>
                                                <a href="{{url('designation')}}"><span><i data-feather="plus-circle" class="plus-down-add"></i>Add new</span></a>
                                            </div>
                                            <select class="select" name="designation">
                                            @foreach($designations as $designation)
                                                <option value="{{ $designation->designation }}">{{ $designation->designation }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Blood Group<span class="text-danger ms-1">*</span></label>
                                            <select class="select" name="blood_group">
                                                <option>Select</option>
                                                <option value="N/A">N/A</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                        <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label">Status</span>
                                            <input type="checkbox" id="user6" class="check" name="status" value="1" checked>
                                            <label for="user6" class="checktoggle mb-0"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Editor -->
                                <div class="col-lg-12">
                                    <div class="input-blocks summer-description-box transfer mb-3">
                                        <label>About</label>
                                        <textarea id="summernote" name="about"></textarea>
                                        <p class="mt-1">Maximum 60 Characters</p>
                                    </div>
                                </div>
                                <!-- /Editor -->
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border mb-4">
                        <div class="accordion-header" id="headingThree">
                            <div class="accordion-button bg-white" data-bs-toggle="collapse" data-bs-target="#collapseThree"  aria-controls="collapseThree">
                                <div class="d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="map-pin" class="feather-edit text-primary me-2"></i><span>Address Information</span></h5>
                                </div>
                            </div>
                        </div>
                        <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body border-top">
                            <div class="other-info">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Country</label>
                                            <select class="select" name="country">
                                                <option></option>
                                                <option value="Kenya">Kenya</option>
                                                <option value="Other">Other</option>			
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Zipcode</label>
                                            <input type="text" name="zipcode" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>    
                    <div class="accordion-item border mb-4">
                        <div class="accordion-header" id="heading4">
                            <div class="accordion-button bg-white" data-bs-toggle="collapse" data-bs-target="#collapseFour"  aria-controls="collapseFour">
                                <div class="d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="info" class="feather-edit text-primary me-2"></i><span>Emergency Information</span></h5>
                                </div>
                            </div>
                        </div>
                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="heading4" data-bs-parent="#accordionExample">
                        <div class="accordion-body border-top">
                            <div class="other-info">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Emergency Contact Number 1</label>
                                            <input type="text" name="emergency_contact1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Relation</label>
                                            <input type="text" name="emergency_relation1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="emergency_name1"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Emergency Contact Number 2</label>
                                            <input type="text" name="emergency_contact2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Relation</label>
                                            <input type="text" name="emergency_relation2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="emergency_name2" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border mb-4">
                        <div class="accordion-header" id="heading5">
                            <div class="accordion-button bg-white" data-bs-toggle="collapse" data-bs-target="#collapseFive"  aria-controls="collapseFive">
                                <div class="d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i class="ti ti-building-bank feather-edit text-primary me-2"></i><span>Bank Information</span></h5>
                                </div>
                            </div>
                        </div>
                        <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="heading5" data-bs-parent="#accordionExample">
                        <div class="accordion-body border-top">
                            <div class="other-info">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text" name="bank_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" name="account_number" class="form-control">
                                        </div>
                                    </div>
                                  
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Branch</label>
                                            <input type="text" name="branch" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border mb-4">
                        <div class="accordion-header" id="heading6">
                            <div class="accordion-button bg-white" data-bs-toggle="collapse" data-bs-target="#collapseTwo"  aria-controls="collapseTwo">
                                <div class="d-flex align-items-center justify-content-between flex-fill">
                                    <h5 class="d-inline-flex align-items-center"><i data-feather="info" class="feather-edit text-primary me-2"></i><span>Password</span></h5>
                                </div>
                            </div>
                        </div>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="heading6" data-bs-parent="#accordionExample">
                        <div class="accordion-body border-top">
                            <div class="pass-info">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks mb-md-0 mb-sm-3">
                                            <label>Password</label>
                                            <input type="password" name="password" id="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="input-blocks mb-0">
                                            <label>Confirm Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
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
                    <button type="submit" class="btn btn-primary">Add Employee</button>
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
@endrole