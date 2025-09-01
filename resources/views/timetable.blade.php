@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
  <div class="content">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div>
        <h4>Manage Timetable</h4>
        <h6>Select a class, auto-generate, and view its timetable.</h6>
      </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Select Class --}}
    <form method="GET" action="{{ route('timetable.index') }}" class="mb-3">
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label for="class_id" class="form-label">Select Class</label>
          <select name="class_id" id="class_id" class="form-control" onchange="this.form.submit()">
            <option value="">-- Select Class --</option>
            <option value="all" {{ $classId === 'all' ? 'selected' : '' }}>All Classes</option>
            @foreach($classes as $class)
              <option
                value="{{ $class->id }}"
                {{ (string)$classId === (string)$class->id ? 'selected' : '' }}
              >
                {{ $class->level->level_name ?? '' }}
                -
                {{ $class->stream->stream_name ?? $class->stream->name ?? '' }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </form>

    {{-- Auto-generate --}}
    @if($classId)
      <div class="mb-3">
        <form method="POST" action="{{ route('timetable.autogenerate') }}">
          @csrf
          <input type="hidden" name="class_id" value="{{ $classId }}">
          <button type="submit" class="btn btn-success">
            Auto Generate Timetable
          </button>
        </form>

        @if($selectedClass && $classId !== 'all')
          <small class="text-muted">
            Target: {{ $selectedClass->level->level_name ?? '' }}
            {{ $selectedClass->stream->stream_name ?? $selectedClass->stream->name ?? '' }}
          </small>
        @elseif($classId === 'all')
          <small class="text-muted">Target: All Classes</small>
        @endif
      </div>
    @endif

    {{-- Timetable Table --}}
    @if($classId === 'all')
      <div class="mt-4">
        <h5 class="mb-3">All Classes</h5>

        {{-- Show a list of classes with links instead of one big table --}}
        <div class="list-group">
          @foreach($classes as $class)
            <a href="{{ route('timetable.index', ['class_id' => $class->id]) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              <span>
                {{ $class->level->level_name ?? '' }}
                -
                {{ $class->stream->stream_name ?? $class->stream->name ?? '' }}
              </span>
              <span class="badge bg-primary">View Timetable</span>
            </a>
          @endforeach
        </div>
      </div>

    @elseif($selectedClass)
      <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">
            Timetable —
            {{ $selectedClass->level->level_name ?? '' }}
            {{ $selectedClass->stream->stream_name ?? $selectedClass->stream->name ?? '' }}
          </h5>
          {{-- Back to all classes --}}
          <a href="{{ route('timetable.index', ['class_id' => 'all']) }}" class="btn btn-outline-secondary btn-sm">
            ← Back to All Classes
          </a>
        </div>

        @if($timetables->isNotEmpty())
          <div class="table-responsive">
            <table class="table table-bordered text-center">
              <thead class="table-light">
                <tr>
                  <th>Day</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Subject</th>
                  <th>Teacher</th>
                </tr>
              </thead>
              <tbody>
                @foreach($timetables as $row)
                  <tr>
                    <td>{{ $row->day_of_week }}</td>
                    <td>{{ $row->start_time }}</td>
                    <td>{{ $row->end_time }}</td>
                    <td>{{ $row->subject->subject_name ?? 'N/A' }}</td>
                    <td>
                      @if($row->teacher)
                        {{ $row->teacher->first_name }} {{ $row->teacher->last_name }}
                      @else
                        N/A
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="alert alert-warning">No timetable entries for this class yet.</div>
        @endif
      </div>
    @else
      <div class="mt-3 alert alert-info">Select a class to view its timetable or auto-generate one.</div>
    @endif

  </div>
</div>
@endsection
