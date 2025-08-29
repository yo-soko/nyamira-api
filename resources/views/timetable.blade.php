@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
  <div class="content">

    <div class="page-header d-flex justify-content-between align-items-center">
      <h4>Class Timetable</h4>
      <a href="{{ route('timetable.autogenerate') }}" class="btn btn-primary"
         onclick="return confirm('Are you sure you want to auto-generate the timetable? This will overwrite existing timetable data.')">
        Auto Generate Timetable
      </a>
    </div>

    {{-- Success / Error Messages --}}
    @if(session('success'))
      <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <div class="mt-4">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Class</th>
            <th>Subject</th>
            <th>Teacher</th>
            <th>Day</th>
            <th>Start Time</th>
            <th>End Time</th>
          </tr>
        </thead>
        <tbody>
          @forelse($timetables as $timetable)
          <tr>
            {{-- Fix column names to match your DB --}}
            <td>{{ $timetable->schoolClass->class_name ?? 'N/A' }}</td>
            <td>{{ $timetable->subject->subject_name ?? 'N/A' }}</td>
            <td>
              @if($timetable->teacher)
                {{ $timetable->teacher->first_name }} {{ $timetable->teacher->last_name }}
              @else
                N/A
              @endif
            </td>
            <td>{{ ucfirst($timetable->day_of_week) }}</td>
            <td>{{ $timetable->start_time }}</td>
            <td>{{ $timetable->end_time }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted">No timetable generated yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
