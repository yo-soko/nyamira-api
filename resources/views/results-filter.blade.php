<?php $page = 'holidays'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')   
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Manage Assesment</h4>
                    <h6>Manage your assesments</h6>
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
            @can('add exams')
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-holiday"><i class="ti ti-circle-plus me-1"></i>Add Exams</a>
            </div>
            @endcan
        </div>
        <div class="container">
            <h2 class="mb-4">Filter Recorded Assesment</h2>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="filterTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-primary active" id="subject-tab" data-bs-toggle="tab" data-bs-target="#subject" type="button" role="tab">Per Learning Area</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-outline-primary" id="class-tab" data-bs-toggle="tab" data-bs-target="#class" type="button" role="tab">Per Stream</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-outline-primary" id="level-tab" data-bs-toggle="tab" data-bs-target="#level" type="button" role="tab">Per Grade</button>
                </li>
            </ul>


            <div class="tab-content p-3 border border-top-0" id="filterTabsContent">
                <!-- Per learning area -->
                <div class="tab-pane fade show active" id="subject" role="tabpanel">
                    <form action="{{ route('results-filter') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="mb-3">
                                <label>Stream</label>
                                <select name="class_id" class="form-select" required>
                                    <option value="">-- Select Stream --</option>
                                    @foreach($classes as $class)
                                       <option value="{{ $class->id }}">  {{ $class->level->level_name ?? 'No Level' }} - {{ $class->stream->name ?? 'No Stream' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Assesment</label>
                                <select name="exam_id" class="form-select" required>
                                    <option value="">-- Select Assesment --</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Term</label>
                                <select name="term_id" class="form-select" required>
                                    <option value="">-- Select Term --</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Learning Area</label>
                                <select name="subject_id" class="form-select" required>
                                    <option value="">-- Select learning area --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary">Filter</button>
                    </form>
                </div>

                <!-- Per Class -->
                <div class="tab-pane fade" id="class" role="tabpanel">
                    <form action="{{ route('results-filter') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                          
                            <div class="mb-3">
                                <label>Stream</label>
                                <select name="class_id" class="form-select" required>
                                    <option value="">-- Select Stream --</option>
                                    @foreach($classes as $class)
                                       <option value="{{ $class->id }}">  {{ $class->level->level_name ?? 'No Level' }} - {{ $class->stream->name ?? 'No Stream' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Assesment</label>
                                <select name="exam_id" class="form-select" required>
                                    <option value="">-- Select Assesment--</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Term</label>
                                <select name="term_id" class="form-select" required>
                                    <option value="">-- Select Term --</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-success">Filter</button>
                    </form>
                </div>

                <!-- Per Level -->
                <div class="tab-pane fade" id="level" role="tabpanel">
                    <form action="{{ route('results-filter') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="mb-3">
                                <label>Grade</label>
                                <select name="level_id" class="form-select" required>
                                    <option value="">-- Select Grade --</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->level_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Assesment</label>
                                <select name="exam_id" class="form-select" required>
                                    <option value="">-- Select Assesment--</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Term</label>
                                <select name="term_id" class="form-select" required>
                                    <option value="">-- Select Term --</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-info">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
    const tabs = document.querySelectorAll('#filterTabs .nav-link');

    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', () => {
            tabs.forEach(btn => btn.classList.remove('btn-primary', 'active'));
            tabs.forEach(btn => btn.classList.add('btn-outline-primary'));

            tab.classList.remove('btn-outline-primary');
            tab.classList.add('btn-primary', 'active');
        });
    });
</script>

@endsection
