@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
  <div class="content">
    <div class="page-header d-flex justify-content-between align-items-center">
      <div>
        <h4>Class Timetable</h4>
        <h6>Manage timetable schedules</h6>
      </div>
    </div>

    {{-- Filter by Class --}}
    <form method="GET" action="{{ route('timetable.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-4">
          <select name="class_id" class="form-control" onchange="this.form.submit()">
            <option value="">-- Select Class --</option>
            @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                {{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </form>

    {{-- Create New Timetable Entry --}}
    <div class="card mb-4">
      <div class="card-header">
        <h5>Add Timetable Entry</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('timetable.store') }}">
          @csrf
          <div class="row mb-3">
            <div class="col-md-3">
              <label>Class</label>
              <select name="class_id" class="form-control" required>
                <option value="">-- Select Class --</option>
                @foreach($classes as $class)
                  <option value="{{ $class->id }}">{{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label>Subject</label>
              <select name="subject_id" class="form-control" required>
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                  <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label>Teacher</label>
              <select name="teacher_id" class="form-control" required>
                <option value="">-- Select Teacher --</option>
                @foreach($teachers as $teacher)
                  <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label>Day of Week</label>
              <select name="day_of_week" class="form-control" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-3">
              <label>Start Time</label>
              <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label>End Time</label>
              <input type="time" name="end_time" class="form-control" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Save Timetable</button>
        </form>
      </div>
    </div>

    {{-- Display Timetable --}}
    @foreach($timetables as $day => $entries)
      <div class="card mb-3">
        <div class="card-header">
          <h5>{{ $day }}</h5>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Class</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Start</th>
                <th>End</th>
              </tr>
            </thead>
            <tbody>
              @forelse($entries as $entry)
                <!-- <tr>{{ $class->level->level_name ?? '' }} - {{ $class->stream->name ?? '' }} -->
                  <td>{{ $entry->class->level->level_name }} - {{ $entry->class->stream->name }} </td>
                  <td>{{ $entry->subject->subject_name }}</td>
                  <td>{{ $entry->teacher->first_name }} {{ $entry->teacher->last_name }}</td>
                  <td>{{ $entry->start_time }}</td>
                  <td>{{ $entry->end_time }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">No entries for this day</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @endforeach

  </div>
</div>
@endsection
