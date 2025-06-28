@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="container py-4">

        {{-- Welcome --}}
        <div class="mb-4">
            <h2 class="fw-bold text-primary">Welcome, {{ Auth::user()->name }}</h2>
            <p class="text-muted">Here’s your teaching overview.</p>
        </div>

        <div class="row g-4 mb-4">
            {{-- Stats Boxes --}}
            <div class="col-md-4">
                <div class="card shadow border-0 text-center p-3">
                    <h5 class="fw-bold">Subjects Assigned</h5>
                    <h2>{{ $subject_count }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0 text-center p-3">
                    <h5 class="fw-bold">Submitted Exams</h5>
                    <h2>{{ $submitted_exams }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0 text-center p-3">
                    <h5 class="fw-bold">Pending Exams</h5>
                    <h2>{{ $pending_exams }}</h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Assigned Subjects --}}
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-primary text-white fw-bold">Your Subjects</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($assignedSubjects as $subject)
                                <li class="list-group-item">{{ $subject->subject_name }}</li>
                            @empty
                                <li class="list-group-item">No subjects assigned.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Submitted Exams --}}
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-success text-white fw-bold">Submitted Exams</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submittedExams as $entry)
                                    <tr>
                                        <td>{{ $entry->subject->subject_name ?? 'N/A' }}</td>
                                        <td>{{ $entry->exam->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2">No submitted exams.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pending Exams --}}
            <div class="col-md-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-danger text-white fw-bold">Pending Exams</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingExams as $entry)
                                    <tr>
                                        <td>{{ $entry->subject->subject_name ?? 'N/A' }}</td>
                                        <td>{{ $entry->exam->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2">No pending exams.</td></tr>
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
