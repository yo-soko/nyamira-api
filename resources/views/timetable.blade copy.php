@extends('layout.mainlayout')
@section('content')
<?php $page = 'timetable'; ?>
@include('layout.toast')

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content">

        <!-- Header -->
        <div class="add-item d-flex justify-content-between align-items-center mb-4">
            <div class="page-title">
                <h4>School Timetable Management</h4>
                <h6>Manage classes, teachers, rooms, and schedules</h6>
            </div>
            <div class="page-btn">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEntryModal">
                    <i class="ti ti-circle-plus me-1"></i> Add Entry
                </button>
                <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i class="ti ti-wand me-1"></i> Generate
                </button>
                <div class="btn-group ms-2">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ti ti-printer me-1"></i> Print
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" id="printClassBtn">Class Timetable</a>
                        <a class="dropdown-item" href="#" id="printTeacherBtn">Teacher Timetable</a>
                        <a class="dropdown-item" href="#" id="printRoomBtn">Room Timetable</a>
                    </div>
                </div>
                <button class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#manageResourcesModal">
                    <i class="ti ti-settings me-1"></i> Manage Resources
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <ul class="nav nav-tabs nav-tabs-bottom" id="timetableTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                            <i class="ti ti-grid me-1"></i> Master View
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="by-class-tab" data-bs-toggle="tab" data-bs-target="#by-class" type="button" role="tab">
                            <i class="ti ti-school me-1"></i> By Class
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="by-teacher-tab" data-bs-toggle="tab" data-bs-target="#by-teacher" type="button" role="tab">
                            <i class="ti ti-user me-1"></i> By Teacher
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="by-room-tab" data-bs-toggle="tab" data-bs-target="#by-room" type="button" role="tab">
                            <i class="ti ti-building me-1"></i> By Room
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4 border border-top-0 rounded-bottom" id="timetableTabsContent">
                    <!-- Master View Tab -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        <div class="mb-3">
                            <select class="form-select w-auto" id="classFilter">
                                <option value="">All Classes</option>
                                @foreach($classLevels as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="masterTimetable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Day/Time</th>
                                        @foreach($timeSlots as $timeSlot)
                                        <th class="text-center">{{ $timeSlot->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $dayIndex => $dayName)
                                    <tr>
                                        <td class="fw-bold">{{ $dayName }}</td>
                                        @foreach($timeSlots as $timeSlot)
                                        <td class="timetable-cell" data-day="{{ $dayIndex + 1 }}" data-time="{{ $timeSlot->id }}">
                                            @php
                                            $entries = $timetables
                                            ->where('day_of_week', $dayIndex + 1)
                                            ->where('time_slot_id', $timeSlot->id);
                                            @endphp

                                            @foreach($entries as $entry)
                                            <div class="timetable-entry mb-2" data-entry-id="{{ $entry->id }}">
                                                <div class="timetable-entry-content">
                                                    <strong class="d-block">{{ $entry->classLevel->name }}</strong>
                                                    <small class="text-muted d-block">{{ $entry->subject->name }}</small>
                                                    <small class="text-muted d-block">{{ $entry->teacher->name }}</small>
                                                    <small class="text-muted">{{ $entry->room->name }}</small>
                                                </div>
                                                <div class="entry-actions mt-2 text-center">
                                                    <button class="btn btn-sm btn-outline-primary btn-icon edit-entry" data-entry-id="{{ $entry->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger btn-icon delete-entry" data-entry-id="{{ $entry->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Class View Tab -->
                    <div class="tab-pane fade" id="by-class" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Select Class</label>
                                    <select class="form-select" id="classSelector">
                                        <option value="">Select Class</option>
                                        @foreach($classLevels as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="classTimetableContainer" class="bg-light p-4 rounded"></div>
                    </div>

                    <!-- Teacher View Tab -->
                    <div class="tab-pane fade" id="by-teacher" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Select Teacher</label>
                                    <select class="form-select" id="teacherSelector">
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="teacherTimetableContainer" class="bg-light p-4 rounded"></div>
                    </div>

                    <!-- Room View Tab -->
                    <div class="tab-pane fade" id="by-room" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Select Room</label>
                                    <select class="form-select" id="roomSelector">
                                        <option value="">Select Room</option>
                                        @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="roomTimetableContainer" class="bg-light p-4 rounded"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Entry Modal -->
        <div class="modal fade" id="createEntryModal" tabindex="-1" aria-labelledby="createEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEntryModalLabel">Create Timetable Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createEntryForm" method="POST" action="{{ route('timetable.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Class</label>
                                        <select class="form-select" name="class_level_id" required>
                                            <option value="">Select Class</option>
                                            @foreach($classLevels as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <select class="form-select" name="subject_id" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Teacher</label>
                                        <select class="form-select" name="teacher_id" required>
                                            <option value="">Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="room_id" required>
                                            <option value="">Select Room</option>
                                            @foreach($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }}) - Cap: {{ $room->capacity }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Day of Week</label>
                                        <select class="form-select" name="day_of_week" required>
                                            <option value="">Select Day</option>
                                            <option value="1">Monday</option>
                                            <option value="2">Tuesday</option>
                                            <option value="3">Wednesday</option>
                                            <option value="4">Thursday</option>
                                            <option value="5">Friday</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Time Slot</label>
                                        <select class="form-select" name="time_slot_id" required>
                                            <option value="">Select Time Slot</option>
                                            @foreach($timeSlots as $timeSlot)
                                            <option value="{{ $timeSlot->id }}">{{ $timeSlot->name }} ({{ \Carbon\Carbon::parse($timeSlot->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('g:i A') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Entry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Entry Modal -->
        <div class="modal fade" id="editEntryModal" tabindex="-1" aria-labelledby="editEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEntryModalLabel">Edit Timetable Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editEntryForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Class</label>
                                        <select class="form-select" name="class_level_id" id="editClass" required>
                                            @foreach($classLevels as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <select class="form-select" name="subject_id" id="editSubject" required>
                                            @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Teacher</label>
                                        <select class="form-select" name="teacher_id" id="editTeacher" required>
                                            @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="room_id" id="editRoom" required>
                                            @foreach($rooms as $room)
                                            <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->code }}) - Cap: {{ $room->capacity }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Day of Week</label>
                                        <select class="form-select" name="day_of_week" id="editDay" required>
                                            <option value="1">Monday</option>
                                            <option value="2">Tuesday</option>
                                            <option value="3">Wednesday</option>
                                            <option value="4">Thursday</option>
                                            <option value="5">Friday</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Time Slot</label>
                                        <select class="form-select" name="time_slot_id" id="editTimeSlot" required>
                                            @foreach($timeSlots as $timeSlot)
                                            <option value="{{ $timeSlot->id }}">{{ $timeSlot->name }} ({{ \Carbon\Carbon::parse($timeSlot->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('g:i A') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Entry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteEntryModal" tabindex="-1" aria-labelledby="deleteEntryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEntryModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this timetable entry?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteEntryForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Entry</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Timetable Modal -->
        <div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateModalLabel">Generate Timetable</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="generateForm" action="{{ route('timetable.generate') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="generateFor" class="form-label">Generate For</label>
                                        <select class="form-select" id="generateFor" name="generate_for" required>
                                            <option value="">Select Option</option>
                                            <option value="all">All Classes</option>
                                            @foreach($classLevels as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="clearExisting" class="form-label">Clear Existing Entries</label>
                                        <select class="form-select" id="clearExisting" name="clear_existing" required>
                                            <option value="1">Yes, clear existing entries</option>
                                            <option value="0">No, keep existing entries</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Generation Constraints</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="respectTeacherAvailability" name="respect_teacher_availability" checked>
                                    <label class="form-check-label" for="respectTeacherAvailability">
                                        Respect teacher availability
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="respectRoomCapacity" name="respect_room_capacity" checked>
                                    <label class="form-check-label" for="respectRoomCapacity">
                                        Respect room capacity
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="preferSpecialRooms" name="prefer_special_rooms" checked>
                                    <label class="form-check-label" for="preferSpecialRooms">
                                        Prefer special rooms for practical subjects
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Generate Timetable</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Manage Resources Modal -->
        <div class="modal fade" id="manageResourcesModal" tabindex="-1" aria-labelledby="manageResourcesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manageResourcesModalLabel">Manage Resources</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs nav-tabs-bottom" id="resourcesTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab">
                                    <i class="ti ti-school me-1"></i> Classes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab">
                                    <i class="ti ti-user me-1"></i> Teachers
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab">
                                    <i class="ti ti-book me-1"></i> Subjects
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rooms-tab" data-bs-toggle="tab" data-bs-target="#rooms" type="button" role="tab">
                                    <i class="ti ti-building me-1"></i> Rooms
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="timeslots-tab" data-bs-toggle="tab" data-bs-target="#timeslots" type="button" role="tab">
                                    <i class="ti ti-clock me-1"></i> Time Slots
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="resourcesTabsContent">
                            <!-- Classes Tab -->
                            <div class="tab-pane fade show active" id="classes" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Class Levels</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                                        <i class="ti ti-plus me-1"></i> Add Class
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Students</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classLevels as $class)
                                            <tr>
                                                <td>{{ $class->name }}</td>
                                                <td>{{ $class->code }}</td>
                                                <td>{{ $class->students_count }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-class" data-class-id="{{ $class->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-class" data-class-id="{{ $class->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Teachers Tab -->
                            <div class="tab-pane fade" id="teachers" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Teachers</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                                        <i class="ti ti-plus me-1"></i> Add Teacher
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Subjects</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teachers as $teacher)
                                            <tr>
                                                <td>{{ $teacher->name }}</td>
                                                <td>{{ $teacher->email }}</td>
                                                <td>{{ $teacher->phone }}</td>
                                                <td>
                                                    @foreach($teacher->subjects as $subject)
                                                    <span class="badge bg-primary">{{ $subject->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-teacher" data-teacher-id="{{ $teacher->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-teacher" data-teacher-id="{{ $teacher->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Subjects Tab -->
                            <div class="tab-pane fade" id="subjects" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Subjects</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                        <i class="ti ti-plus me-1"></i> Add Subject
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Type</th>
                                                <th>Teachers</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($subjects as $subject)
                                            <tr>
                                                <td>{{ $subject->name }}</td>
                                                <td>{{ $subject->code }}</td>
                                                <td>{{ ucfirst($subject->type) }}</td>
                                                <td>
                                                    @foreach($subject->teachers as $teacher)
                                                    <span class="badge bg-info">{{ $teacher->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-subject" data-subject-id="{{ $subject->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-subject" data-subject-id="{{ $subject->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Rooms Tab -->
                            <div class="tab-pane fade" id="rooms" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Rooms</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                                        <i class="ti ti-plus me-1"></i> Add Room
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Type</th>
                                                <th>Capacity</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rooms as $room)
                                            <tr>
                                                <td>{{ $room->name }}</td>
                                                <td>{{ $room->code }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $room->type)) }}</td>
                                                <td>{{ $room->capacity }}</td>
                                                <td>
                                                    @if($room->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                    @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-room" data-room-id="{{ $room->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-room" data-room-id="{{ $room->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Time Slots Tab -->
                            <div class="tab-pane fade" id="timeslots" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Time Slots</h6>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTimeSlotModal">
                                        <i class="ti ti-plus me-1"></i> Add Time Slot
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Duration</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($timeSlots as $timeSlot)
                                            <tr>
                                                <td>{{ $timeSlot->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($timeSlot->start_time)->format('g:i A') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($timeSlot->end_time)->format('g:i A') }}</td>
                                                <td>
                                                    @php
                                                    $start = \Carbon\Carbon::parse($timeSlot->start_time);
                                                    $end = \Carbon\Carbon::parse($timeSlot->end_time);
                                                    echo $start->diffInMinutes($end) . ' minutes';
                                                    @endphp
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-timeslot" data-timeslot-id="{{ $timeSlot->id }}">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-timeslot" data-timeslot-id="{{ $timeSlot->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Resource Modals would go here -->
        <!-- These would include modals for adding/editing classes, teachers, subjects, rooms, and timeslots -->
        <!-- The implementation would be similar to the entry modals above -->

        <!-- Print Selection Modal -->
        <div class="modal fade" id="printSelectionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printModalLabel">Print Timetable</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3" id="classPrintSection">
                            <label class="form-label">Select Class</label>
                            <select class="form-select" id="printClassSelector">
                                <option value="">Select Class</option>
                                @foreach($classLevels as $class)
                                <option value="{{ route('timetable.print.class', $class->id) }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="teacherPrintSection">
                            <label class="form-label">Select Teacher</label>
                            <select class="form-select" id="printTeacherSelector">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                <option value="{{ route('timetable.print.teacher', $teacher->id) }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="roomPrintSection">
                            <label class="form-label">Select Room</label>
                            <select class="form-select" id="printRoomSelector">
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                <option value="{{ route('timetable.print.room', $room->id) }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmPrint">Print</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0">2025 &copy; JavaPA. All Rights Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize the master timetable view
        initMasterTimetable();

        // Load class timetable when selection changes
        $('#classSelector').change(function() {
            loadClassTimetable();
        });

        // Load teacher timetable when selection changes
        $('#teacherSelector').change(function() {
            loadTeacherTimetable();
        });

        // Load room timetable when selection changes
        $('#roomSelector').change(function() {
            loadRoomTimetable();
        });

        // Filter master timetable by class
        $('#classFilter').change(function() {
            filterMasterTimetable($(this).val());
        });

        // Print buttons handling
        $('#printClassBtn').click(function(e) {
            e.preventDefault();
            $('#printSelectionModal').modal('show');
            $('#classPrintSection').removeClass('d-none');
            $('#teacherPrintSection').addClass('d-none');
            $('#roomPrintSection').addClass('d-none');
            $('#printModalLabel').text('Print Class Timetable');
        });

        $('#printTeacherBtn').click(function(e) {
            e.preventDefault();
            $('#printSelectionModal').modal('show');
            $('#teacherPrintSection').removeClass('d-none');
            $('#classPrintSection').addClass('d-none');
            $('#roomPrintSection').addClass('d-none');
            $('#printModalLabel').text('Print Teacher Timetable');
        });

        $('#printRoomBtn').click(function(e) {
            e.preventDefault();
            $('#printSelectionModal').modal('show');
            $('#roomPrintSection').removeClass('d-none');
            $('#classPrintSection').addClass('d-none');
            $('#teacherPrintSection').addClass('d-none');
            $('#printModalLabel').text('Print Room Timetable');
        });

        $('#confirmPrint').click(function() {
            let printUrl = '';
            if (!$('#classPrintSection').hasClass('d-none')) {
                printUrl = $('#printClassSelector').val();
            } else if (!$('#teacherPrintSection').hasClass('d-none')) {
                printUrl = $('#printTeacherSelector').val();
            } else if (!$('#roomPrintSection').hasClass('d-none')) {
                printUrl = $('#printRoomSelector').val();
            }

            if (printUrl) {
                window.open(printUrl, '_blank');
                $('#printSelectionModal').modal('hide');
            } else {
                alert('Please select an option first');
            }
        });

        // Edit entry button click handler
        $(document).on('click', '.edit-entry', function() {
            const entryId = $(this).data('entry-id');
            loadEntryForEdit(entryId);
        });

        // Delete entry button click handler
        $(document).on('click', '.delete-entry', function() {
            const entryId = $(this).data('entry-id');
            $('#deleteEntryForm').attr('action', `/timetable/${entryId}`);
            $('#deleteEntryModal').modal('show');
        });

        // Form submission handlers
        $('#createEntryForm').submit(function(e) {
            e.preventDefault();
            submitEntryForm($(this));
        });

        $('#editEntryForm').submit(function(e) {
            e.preventDefault();
            submitEntryForm($(this));
        });

        // Initialize the first view
        if ($('#classSelector').val()) {
            loadClassTimetable();
        }

        // Function to initialize master timetable
        function initMasterTimetable() {
            $('#masterTimetable .timetable-cell').hover(
                function() {
                    $(this).css('background-color', '#f8f9fa');
                    $(this).find('.entry-actions').show();
                },
                function() {
                    $(this).css('background-color', '');
                    $(this).find('.entry-actions').hide();
                }
            );
        }

        // Function to load class timetable
        function loadClassTimetable() {
            const classId = $('#classSelector').val();
            if (classId) {
                $('#classTimetableContainer').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
                $('#classTimetableContainer').load(`/timetable/class/${classId}`);
            } else {
                $('#classTimetableContainer').html('<div class="alert alert-warning">Please select a class first</div>');
            }
        }

        // Function to load teacher timetable
        function loadTeacherTimetable() {
            const teacherId = $('#teacherSelector').val();
            if (teacherId) {
                $('#teacherTimetableContainer').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
                $('#teacherTimetableContainer').load(`/timetable/teacher/${teacherId}`);
            } else {
                $('#teacherTimetableContainer').html('<div class="alert alert-warning">Please select a teacher first</div>');
            }
        }

        // Function to load room timetable
        function loadRoomTimetable() {
            const roomId = $('#roomSelector').val();
            if (roomId) {
                $('#roomTimetableContainer').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
                $('#roomTimetableContainer').load(`/timetable/room/${roomId}`);
            } else {
                $('#roomTimetableContainer').html('<div class="alert alert-warning">Please select a room first</div>');
            }
        }

        // Function to filter master timetable by class
        function filterMasterTimetable(classId) {
            if (classId) {
                $('#masterTimetable .timetable-entry').hide();
                $(`#masterTimetable .timetable-entry[data-class="${classId}"]`).show();
            } else {
                $('#masterTimetable .timetable-entry').show();
            }
        }

        // Function to load entry data for editing
        function loadEntryForEdit(entryId) {
            $.get(`/timetable/${entryId}/edit`, function(data) {
                $('#editClass').val(data.class_level_id);
                $('#editSubject').val(data.subject_id);
                $('#editTeacher').val(data.teacher_id);
                $('#editRoom').val(data.room_id);
                $('#editDay').val(data.day_of_week);
                $('#editTimeSlot').val(data.time_slot_id);
                $('#editEntryForm').attr('action', `/timetable/${entryId}`);
                $('#editEntryModal').modal('show');
            }).fail(function() {
                alert('Failed to load entry data');
            });
        }

        // Function to submit entry form
        function submitEntryForm(form) {
            const url = form.attr('action');
            const method = form.attr('method');
            const data = form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Operation failed');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        }
    });
</script>

<style>
    /* Timetable specific styles */
    .timetable-entry {
        padding: 8px;
        background-color: #f8f9fa;
        border-radius: 6px;
        min-height: 80px;
        transition: all 0.3s ease;
        border-left: 3px solid #3b82f6;
        margin-bottom: 5px;
    }

    .timetable-entry:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .timetable-entry-content {
        margin-bottom: 4px;
    }

    .entry-actions {
        display: none;
        text-align: center;
    }

    .timetable-cell {
        min-width: 120px;
        vertical-align: middle !important;
        position: relative;
    }

    .timetable-cell:hover .entry-actions {
        display: block;
    }

    .table th {
        background-color: #f1f5f9;
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 500;
        border: none;
        padding: 12px 20px;
    }

    .nav-tabs .nav-link.active {
        color: #3b82f6;
        background-color: transparent;
        border-bottom: 2px solid #3b82f6;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        color: #334155;
        border-bottom: 2px solid #e2e8f0;
    }

    .tab-content {
        background-color: #fff;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .timetable-entry {
            min-height: 60px;
            padding: 6px;
            font-size: 0.8rem;
        }

        .timetable-cell {
            min-width: 80px;
        }

        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .timetable-entry {
            min-height: 50px;
            font-size: 0.7rem;
        }

        .entry-actions .btn {
            padding: 0.25rem 0.4rem;
            font-size: 0.7rem;
        }
    }
</style>
@endsection
