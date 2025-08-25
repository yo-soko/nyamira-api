@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
  <div class="content">

    <h4>Class Timetable</h4>

    <!-- Filter by Class -->
    <form method="GET" action="{{ route('timetable.index') }}" class="mb-3">
      <select name="class_id" onchange="this.form.submit()">
        <option value="">-- Select Class --</option>
        @foreach($classes as $class)
          <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
            {{ $class->name }}
          </option>
        @endforeach
      </select>
    </form>

    <!-- Add Timetable Entry -->
    <form method="POST" action="{{ route('timetable.store') }}" class="mb-4">
      @csrf
      <select name="subject_id" required>
        <option value="">-- Select Subject --</option>
        @foreach($subjects as $subject)
          <option value="{{ $subject->id }}">{{ $subject->name }}</option>
        @endforeach
      </select>

      <select name="teacher_id" required>
        <option value="">-- Select Teacher --</option>
        @foreach($teachers as $teacher)
          <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
        @endforeach
      </select>

      <select name="day_of_week" required>
        <option value="">-- Select Day --</option>
        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
          <option>{{ $day }}</option>
        @endforeach
      </select>

      <input type="time" name="start_time" required>
      <input type="time" name="end_time" required>

      <button type="submit" class="btn btn-primary">Add</button>
    </form>

    <!-- Timetable Display -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Day</th>
          <th>Lessons</th>
        </tr>
      </thead>
      <tbody>
        @forelse($timetables as $day => $entries)
          <tr>
            <td><b>{{ $day }}</b></td>
            <td>
              @foreach($entries as $tt)
                <div class="mb-2 p-2 border rounded">
                  {{ $tt->start_time }} - {{ $tt->end_time }} :
                  <b>{{ $tt->subject->name }}</b> 
                  ({{ $tt->teacher->first_name }} {{ $tt->teacher->last_name }})
                </div>
              @endforeach
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="2" class="text-center">No timetable entries found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

  </div>
</div>

@endsection
