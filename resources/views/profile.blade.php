<?php $page = 'profile'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Profile</h4>
                    <h6>User Profile</h6>
                </div>
            </div>
            <!-- /product list -->
            <div class="card">
                <div class="card-header">
                <h4>Profile</h4>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="card-body profile-body">
                    @csrf
                    @method('PUT')
                    <h5 class="mb-2"><i class="ti ti-user text-primary me-1"></i>Basic Information</h5>
                        <div class="profile-pic-upload image-field">
                            <div class="profile-pic p-2">
                            <img id="profilePreview" src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('build/img/users/default.png') }}" class="object-fit-cover h-100 rounded-1" alt="Img">
                            <button type="button" class="close rounded-1">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="mb-3">
                               <div class="image-upload mb-0 d-inline-flex">
                                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="d-none">
                                    <label for="profile_picture" class="btn btn-primary fs-13">Change Image</label>
                                </div>
                                <p class="mt-2">Upload an image below 2 MB, Accepted File format JPG, PNG</p>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="name"  class="form-control" value="{{ auth()->user()->name }}">
                            </div>
                        </div>
                       
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label>Email<span class="text-danger ms-1">*</span></label>
                                <input type="email" name="email"  class="form-control" value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Phone Number<span class="text-danger ms-1">*</span></label>
                                <input type="text" name="phone"  value="{{ auth()->user()->phone }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">User Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                                <div class="pass-group">
                                    <input type="password" name="password" class="pass-input form-control">
                                    <i class="ti ti-eye-off toggle-password"></i>
                                </div>
                            </div>
                        </div>
                          <div class="col-lg-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password<span class="text-danger ms-1">*</span></label>
                                <div class="pass-group">
                                    <input type="password" name="password_confirmation" class="pass-input form-control">
                                    <i class="ti ti-eye-off toggle-password"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <a href="javascript:void(0);" class="btn btn-secondary me-2 shadow-none">Cancel</a>
                            <button type="submit" class="btn btn-primary shadow-none">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /product list -->
        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9"> &copy;  All Right Reserved</p>
            <!-- <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p> -->
        </div>
    </div>
    <script>
    document.getElementById('profile_picture').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    </script>

@endsection
