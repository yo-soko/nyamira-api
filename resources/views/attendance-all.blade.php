<?php $page = 'employees-grid'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast') 
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Attendancies Corner</h4>
                    <h6>Mark attendancies</h6>
                </div>
            </div>
        </div>
      
        <!-- /product list -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set mb-0">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                            <input type="search" class="form-control" placeholder="Search">
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- /product list -->
        
        <div class="employee-grid-widget">
            <div class="row">
               @foreach($classes as $class) 
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 mb-4">
                        <form action="{{ route('attendance-all') }}" method="POST" class="card" style="cursor:pointer;">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $class->id }}">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-2">
                                    <div class="form-check form-check-md">
                                        <input class="form-check-input" type="checkbox" name="selected_classes[]" value="{{ $class->id }}">
                                    </div>
                                    <button type="submit" class="avatar avatar-xl avatar-rounded border p-1 rounded-circle" style="border:none; background:none; padding:0;">
                                        <img src="{{ $class->classTeacher && $class->classTeacher->profile_photo ? asset('storage/' . $class->classTeacher->profile_photo) : asset('build/img/users/profile.jpg') }}" />
                                    </button>
                                    <div class="dropdown">
                                        <a href="#" class="action-icon border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical" class="feather-user"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button type="submit" class="dropdown-item" style="border: none; background: none; padding: 0; width: 100%; text-align: left;">
                                                    <i class="ti ti-circle-check me-2"></i> Mark attendance
                                                </button>
                                            </li>		
                                        </ul>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-primary mb-2">Teacher: {{ $class->classTeacher->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-center mb-3">
                                    <button type="submit" style="border: none; background: none; padding: 0; color: #0d6efd; text-decoration: none;">
                                        {{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }}
                                    </button>                              
                                </div>
                                <div class="d-flex align-items-center justify-content-between bg-light rounded p-3">
                                    <p class="mb-1"> {{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }}</p>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endforeach

            </div>
        </div>

    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

@endsection
