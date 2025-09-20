@hasanyrole('admin|developer|manager|director|supervisor')
<?php $page = 'users'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">Users</h4>
                        <h6>Manage your users</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-user"><i class="ti ti-circle-plus me-1"></i>Add User</a>
                </div>
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

                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Status
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
                                    <th>User Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('users.show', $user->id) }}" class="avatar avatar-md me-2">
                                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('build/img/users/default.png') }}" alt="user">
                                            </a>
                                            <a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a>
                                        </div>
                                    </td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <span class="d-inline-flex align-items-center p-1 pe-2 rounded-1 text-white bg-{{ $user->status ? 'success' : 'danger' }} fs-10">
                                            <i class="ti ti-point-filled me-1 fs-11"></i>
                                            {{ $user->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                        <a href="javascript:void(0);" class="edit-btn"
                                            data-id="{{ $user->id }}"
                                            data-name="{{$user->name}}"
                                            data-image="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('build/img/users/default.png') }}"
                                            data-email="{{ $user->email }}"
                                            data-phone="{{ $user->phone }}"
                                            data-profile_picture="{{ $user->profile_picture }}"
                                            data-role="{{ $user->role }}"
                                            data-status="{{ $user->status }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#edit-user">
                                                <i data-feather="edit" class="feather-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="delete-btn"
                                            data-id="{{ $user->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete-modal">
                                                <i data-feather="trash-2" class="feather-trash"></i>
                                        </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const email = this.dataset.email;
                        const phone = this.dataset.phone;
                        const role = this.dataset.role;
                        const status = this.dataset.status;
                        const image = this.dataset.image;


                        // Set the form action dynamically
                        const form = document.getElementById('editForm');
                        form.action = form.action.replace(/\/\d+$/, '/' + id); // update route

                        // Set form values
                        document.getElementById('edit-id').value = id;
                        document.getElementById('edit-name').value = name;
                        document.getElementById('edit-email').value = email;
                        document.getElementById('edit-phone').value = phone;
                        document.getElementById('edit-role').value = role;
                        document.getElementById('edit-status').checked = status === "1" || status === 1;

                         // Set preview image and store default
                        const previewImg = document.getElementById('preview-image');
                        previewImg.src = image;
                        previewImg.setAttribute('data-default-src', image);
                    });
                });
            });
            function previewFile(input) {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-image').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            function removeImage() {
                const defaultImage = document.getElementById('preview-image').getAttribute('data-default-src');
                document.getElementById('preview-image').src = defaultImage;
                document.getElementById('profile_picture').value = '';
            }


            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        document.getElementById('delete-id').value = id;
                    });
                });
            });
        </script>


        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9"> &copy;  All Right Reserved</p>
            <!-- <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p> -->
        </div>
    </div>
@endsection
@endhasanyrole
