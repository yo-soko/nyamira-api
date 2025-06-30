@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="container py-4">

        {{-- Welcome --}}
        <div class="mb-4">
            <h2 class="fw-bold text-primary">Welcome, {{ Auth::user()->name }}</h2>
            <p class="text-muted">Here’s your teaching overview.</p>
        </div>

        {{-- Stats boxes --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow text-center p-3">
                    <h5 class="fw-bold">Subjects Assigned</h5>
                    <h2>{{ $subject_count }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-3">
                    <h5 class="fw-bold">Submitted Exams</h5>
                    <h2>{{ $submitted_exams }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow text-center p-3">
                    <h5 class="fw-bold">Pending Exams</h5>
                    <h2>{{ $pending_exams }}</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Subjects assigned --}}
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white fw-bold">Your Subjects & Classes</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($assignedSubjects as $subject)
                                @php
                                    $class = \App\Models\SchoolClass::find($subject->pivot->class_id);
                                @endphp
                                <li class="list-group-item">
                                    {{ $subject->subject_name }}
                                    @if($class)
                                        ({{ $class->level->level_name ?? '-' }} {{ $class->stream->name ?? '-' }})
                                    @else
                                        (Class: Unknown)
                                    @endif
                                </li>
                            @empty
                                <li class="list-group-item">No subjects assigned.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Submitted exams --}}
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-success text-white fw-bold">Submitted Exams</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Exam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submittedExams as $entry)
                                    @php
                                        $class = \App\Models\SchoolClass::find($entry->class_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $entry->subject_name }}</td>
                                        <td>
                                            @if($class)
                                                {{ $class->level->level_name ?? '-' }} {{ $class->stream->name ?? '-' }}
                                            @else
                                                Unknown
                                            @endif
                                        </td>
                                        <td>{{ $entry->exam_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No submitted exams found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pending exams --}}
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-danger text-white fw-bold">Pending Exams</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Exam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingExams as $entry)
                                    @php
                                        $class = \App\Models\SchoolClass::find($entry->class_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $entry->subject_name }}</td>
                                        <td>
                                            @if($class)
                                                {{ $class->level->level_name ?? '-' }} {{ $class->stream->name ?? '-' }}
                                            @else
                                                Unknown
                                            @endif
                                        </td>
                                        <td>{{ $entry->exam_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No pending exams found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
