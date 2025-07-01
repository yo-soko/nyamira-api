@extends('layout.mainlayout')

@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="row mb-4 p-3 mt-3">
        <div class="col-md-12">
            <h2>Transport Management</h2>
        </div>
    </div>

    <!-- Transport Dashboard Cards -->
    <div class="row mb-4 p-3">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Routes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $routes->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-route fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Students Using Transport</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $studentCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Today's Attendance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayAttendance }}/{{ $studentCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Transport Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Transport System</h6>
            <a href="{{ route('transport.reports') }}" class="btn btn-sm btn-info">
                <i class="fas fa-file-alt mr-1"></i> View Reports
            </a>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="transportTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="routes-tab" data-toggle="tab" href="#routes" role="tab">Routes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance" role="tab">Attendance</a>
                </li>
            </ul>

            <div class="tab-content" id="transportTabsContent">
                <!-- Routes Tab -->
                <div class="tab-pane fade show active" id="routes" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h5>Transport Routes</h5>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addRouteModal">
                            <i class="fas fa-plus"></i> Add Route
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="routesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Route Name</th>
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routes as $route)
                                <tr>
                                    <td>{{ $route->route_name }}</td>
                                    <td>KSh {{ number_format($route->fee, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $route->status ? 'success' : 'danger' }}">
                                            {{ $route->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-route" data-id="{{ $route->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-route" data-id="{{ $route->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Attendance Tab -->
                <div class="tab-pane fade" id="attendance" role="tabpanel">
                    <div class="row mb-3 mt-3">
                        <div class="col-md-4">
                            <input type="date" class="form-control" id="attendanceDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="attendanceRoute">
                                <option value="">Select Route</option>
                                @foreach($routes as $route)
                                <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="session-indicator ml-auto">
                                    <span class="badge badge-info">
                                        <i class="fas fa-clock mr-1"></i>
                                        Current Session:
                                        <span id="currentSession">
                                            {{ now()->hour < 12 ? 'Pickup' : 'Dropoff' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="attendanceResults" class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th id="sessionHeader">{{ now()->hour < 12 ? 'Pickup Status' : 'Dropoff Status' }}</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTableBody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                        <p>Select a route to view students</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS SECTION -->
    <!-- Add Route Modal -->
    <div class="modal fade" id="addRouteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Route</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="routeForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="route_name">Route Name</label>
                            <input type="text" class="form-control" id="route_name" name="route_name" required>
                        </div>

                        <div class="form-group">
                            <label for="fee">Transport Fee (KSh)</label>
                            <input type="number" step="0.01" class="form-control" id="fee" name="fee" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Route</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Route Modal -->
    <div class="modal fade" id="editRouteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Route</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editRouteForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="route_id" id="edit_route_id">
                        <div class="form-group">
                            <label for="edit_route_name">Route Name</label>
                            <input type="text" class="form-control" id="edit_route_name" name="route_name" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_fee">Transport Fee (KSh)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_fee" name="fee" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_status">Status</label>
                            <select class="form-control" id="edit_status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Route</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Attendance Record Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Record Attendance</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="attendanceForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="attendance_id" id="attendance_id">
                        <input type="hidden" name="student_id" id="student_id">
                        <input type="hidden" name="route_id" id="route_id">
                        <input type="hidden" name="date" id="record_date">
                        <input type="hidden" name="session_type" id="session_type">

                        <div class="form-group">
                            <label>Student</label>
                            <p class="form-control-static font-weight-bold" id="student_name"></p>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="time">Time</label>
                            <input type="time" class="form-control" id="time" name="time">
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Enter any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#routesTable').DataTable();

        // Current session management
        let currentSession = getCurrentSession();

        function getCurrentSession() {
            const hours = new Date().getHours();
            return hours < 12 ? 'pickup' : 'dropoff';
        }

        function updateSessionHeader() {
            currentSession = getCurrentSession();
            $('#currentSession').text(currentSession === 'pickup' ? 'Pickup' : 'Dropoff');
            $('#sessionHeader').text(currentSession === 'pickup' ? 'Pickup Status' : 'Dropoff Status');
            return currentSession;
        }

        // Auto-load students when route is selected
        $('#attendanceRoute').change(function() {
            loadStudentsForRoute();
        });

        // Auto-load students when date changes
        $('#attendanceDate').change(function() {
            if ($('#attendanceRoute').val()) {
                loadStudentsForRoute();
            }
        });

        // Function to load students for selected route
        function loadStudentsForRoute() {
            const routeId = $('#attendanceRoute').val();
            const date = $('#attendanceDate').val();
            const sessionType = updateSessionHeader();

            if (!routeId) {
                showEmptyState('Select a route to view students');
                return;
            }

            showLoadingState();

            $.ajax({
                url: '/transport/attendance',
                type: 'GET',
                data: {
                    route_id: routeId,
                    date: date,
                    session_type: sessionType
                },
                success: function(response) {
                    if (response.error) {
                        showErrorState(response.message || 'Failed to load students');
                        return;
                    }

                    if (!response.students || response.students.length === 0) {
                        showEmptyState('No students found for this route');
                        return;
                    }

                    renderStudentsTable(response.students, sessionType);
                },
                error: function(xhr) {
                    showErrorState('Failed to load students. Please try again.');
                }
            });
        }

        // Helper functions for UI states
        function showLoadingState() {
            $('#attendanceTableBody').html(`
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Loading students...</p>
                    </td>
                </tr>
            `);
        }

        function showEmptyState(message) {
            $('#attendanceTableBody').html(`
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>${message}</p>
                    </td>
                </tr>
            `);
        }

        function showErrorState(message) {
            $('#attendanceTableBody').html(`
                <tr>
                    <td colspan="4" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                        <p>${message}</p>
                        <button class="btn btn-sm btn-primary mt-2" onclick="loadStudentsForRoute()">
                            <i class="fas fa-sync-alt"></i> Retry
                        </button>
                    </td>
                </tr>
            `);
        }

        function renderStudentsTable(students, sessionType) {
            let html = '';

            students.forEach(student => {
                const attendance = student.attendance || {};
                const isPickupMarked = attendance.pickup_status !== null && attendance.pickup_status !== undefined;
                const isDropoffMarked = attendance.dropoff_status !== null && attendance.dropoff_status !== undefined;

                if ((sessionType === 'pickup' && isPickupMarked) ||
                    (sessionType === 'dropoff' && isDropoffMarked)) {
                    return;
                }

                const currentStatus = sessionType === 'pickup' ?
                    (attendance.pickup_status || 'absent') :
                    (attendance.dropoff_status || 'absent');

                html += `
                <tr id="student-row-${student.id}" data-student-id="${student.id}">
                    <td>${student.full_name || 'Unknown Student'}</td>
                    <td>${student.class_name || 'N/A'}</td>
                    <td>
                        <span class="badge badge-${currentStatus === 'present' ? 'success' : 'danger'}">
                            ${currentStatus === 'present' ? 'Present' : 'Absent'}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-success mark-present"
                                data-student-id="${student.id}"
                                data-attendance-id="${attendance.id || ''}">
                                <i class="fas fa-check"></i> Present
                            </button>
                            <button class="btn btn-danger mark-absent"
                                data-student-id="${student.id}"
                                data-attendance-id="${attendance.id || ''}">
                                <i class="fas fa-times"></i> Absent
                            </button>
                        </div>
                    </td>
                </tr>`;
            });

            if (html === '') {
                showEmptyState(`All students already marked for ${sessionType}`);
            } else {
                $('#attendanceTableBody').html(html);
            }
        }

        // Handle attendance marking
        $(document).on('click', '.mark-present, .mark-absent', function() {
            const studentId = $(this).data('student-id');
            const attendanceId = $(this).data('attendance-id');
            const routeId = $('#attendanceRoute').val();
            const date = $('#attendanceDate').val();
            const sessionType = currentSession;
            const status = $(this).hasClass('mark-present') ? 'present' : 'absent';

            const studentRow = $(`#student-row-${studentId}`);
            const studentName = studentRow.find('td:eq(0)').text();

            if (!routeId || !studentId) {
                toastr.error('Missing required data to mark attendance');
                return;
            }

            getCurrentLocation(function(location) {
                const data = {
                    student_id: studentId,
                    route_id: routeId,
                    date: date,
                    session_type: sessionType,
                    _token: '{{ csrf_token() }}'
                };

                if (sessionType === 'pickup') {
                    data.pickup_status = status;
                    data.pickup_location = location;
                } else {
                    data.dropoff_status = status;
                    data.dropoff_location = location;
                }

                const url = attendanceId ? `/transport/attendance/${attendanceId}` : '/transport/attendance';
                const method = attendanceId ? 'PUT' : 'POST';

                console.log('Sending attendance data:', data); // Debug log

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        console.log('Attendance response:', response); // Debug log

                        if (!response || !response.success) {
                            toastr.error(response?.message || 'Failed to mark attendance');
                            return;
                        }

                        studentRow.fadeOut(300, function() {
                            $(this).remove();
                            if ($('#attendanceTableBody tr').length === 0) {
                                showEmptyState(`All students marked for ${sessionType}`);
                            }
                        });

                        toastr.success(`${studentName} marked as ${status} for ${sessionType}`);
                    },
                    error: function(xhr) {
                        console.error('Full error response:', xhr.responseJSON); // Detailed error log
                        toastr.error('Failed to mark attendance. ' +
                            (xhr.responseJSON?.message || 'Please try again.'));
                    }
                });
            });
        });

        /**
         * Gets the current geolocation (lat, lng) and passes a human-readable fallback.
         * @param {Function} callback Receives location string e.g. "Lat: -1.2833, Lng: 36.8167"
         */
        const OPENCAGE_API_KEY = 'acbfa6ada6af4021824a67c0b9ebdaa6';

        function getCurrentLocation(callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    async (position) => {
                            const {
                                latitude,
                                longitude
                            } = position.coords;

                            try {
                                const response = await fetch(
                                    `https://api.opencagedata.com/geocode/v1/json?q=${latitude}+${longitude}&key=${OPENCAGE_API_KEY}`
                                );
                                const data = await response.json();
                                const result = data?.results?.[0];
                                const components = result?.components || {};

                                // Prioritize specific local identifiers
                                const areaName =
                                    components.neighbourhood || // e.g., Jogoo, Mwembe
                                    components.suburb || // e.g., Daraja Mbili
                                    components.village || // fallback if rural
                                    components.town || // e.g., Kisii
                                    components.city || // e.g., Kisii Town
                                    result.formatted || // fallback to full address
                                    `Lat: ${latitude.toFixed(5)}, Lng: ${longitude.toFixed(5)}`;

                                callback(areaName);
                            } catch (err) {
                                console.error('OpenCage error:', err);
                                callback(`Lat: ${latitude.toFixed(5)}, Lng: ${longitude.toFixed(5)}`);
                            }
                        },
                        (error) => {
                            console.warn('Geolocation error:', error.message);
                            const fallback = currentSession === 'pickup' ? 'School Gate' : 'Designated Stop';
                            callback(fallback);
                        }
                );
            } else {
                const fallback = currentSession === 'pickup' ? 'School Gate' : 'Designated Stop';
                console.warn('Geolocation not supported.');
                callback(fallback);
            }
        }

        // Route CRUD Operations
        $('.edit-route').click(function() {
            const routeId = $(this).data('id');
            $.get(`/transport/routes/${routeId}/edit`, function(data) {
                $('#edit_route_id').val(data.id);
                $('#edit_route_name').val(data.route_name);
                $('#edit_fee').val(data.fee);
                $('#edit_status').val(data.status);
                $('#editRouteModal').modal('show');
            }).fail(function(xhr) {
                toastr.error('Failed to load route data');
            });
        });

        $('.delete-route').click(function() {
            const routeId = $(this).data('id');
            $('#confirmMessage').html('Are you sure you want to delete this route?');
            $('#confirmDelete').data('id', routeId).data('type', 'route');
            $('#confirmModal').modal('show');
        });

        // Add Route Form Submission
        $('#routeForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.post('/transport/routes', formData)
                .done(function(response) {
                    $('#addRouteModal').modal('hide');
                    location.reload();
                })
                .fail(function(xhr) {
                    toastr.error('Error: ' + (xhr.responseJSON?.message || 'Failed to save route'));
                });
        });

        // Edit Route Form Submission
        $('#editRouteForm').submit(function(e) {
            e.preventDefault();
            const routeId = $('#edit_route_id').val();
            const formData = $(this).serialize();

            $.ajax({
                url: `/transport/routes/${routeId}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editRouteModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error('Error: ' + (xhr.responseJSON?.message || 'Failed to update route'));
                }
            });
        });

        // Delete Confirmation
        $('#confirmDelete').click(function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            let url = '';

            switch (type) {
                case 'route':
                    url = `/transport/routes/${id}`;
                    break;
            }

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#confirmModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error('Error: ' + (xhr.responseJSON?.message || 'Failed to delete'));
                }
            });
        });

        // Update session header every minute
        updateSessionHeader();
        setInterval(updateSessionHeader, 60000);
    });
</script>

<style>
    /* Enhanced Attendance Table Styles */
    #attendanceTableBody tr {
        transition: all 0.3s ease;
    }

    #attendanceTableBody tr.marked {
        background-color: #e8f5e9;
    }

    #attendanceTableBody tr.marked td {
        text-decoration: line-through;
        opacity: 0.7;
    }

    .mark-present,
    .mark-absent {
        transition: all 0.2s;
    }

    .mark-present:hover,
    .mark-absent:hover {
        transform: scale(1.05);
    }

    /* Custom styling for attendance features */
    #attendanceTableBody tr {
        transition: all 0.3s ease;
    }

    #attendanceTableBody tr.removing {
        transform: translateX(100%);
        opacity: 0;
    }

    .toast-success {
        background-color: #28a745;
    }

    .session-indicator {
        padding: 8px 12px;
        border-radius: 4px;
        background-color: #e8f4ff;
        border-left: 3px solid #4e73df;
    }

    .badge-present {
        background-color: #28a745;
    }

    .badge-absent {
        background-color: #dc3545;
    }

    #attendanceTableBody tr:hover {
        background-color: #f8f9fc;
    }

    .mark-present,
    .mark-absent {
        transition: all 0.2s;
    }

    .mark-present:hover,
    .mark-absent:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    @media (max-width: 768px) {
        .session-indicator {
            margin-top: 10px;
            width: 100%;
        }

        .nav-tabs .nav-link {
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        .btn-group-sm>.btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    }

    .modal-content {
        border-radius: 0.5rem;
    }

    .modal-header {
        background-color: #4e73df;
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 600;
    }

    .btn-close {
        color: white;
    }

    .table-responsive {
        overflow-x: auto;
    }

    #attendanceResults table {
        min-width: 100%;
    }

    .badge-primary {
        background-color: #4e73df;
    }

    .badge-info {
        background-color: #36b9cc;
    }

    .badge-success {
        background-color: #1cc88a;
    }

    .badge-danger {
        background-color: #e74a3b;
    }

    .badge-warning {
        background-color: #f6c23e;
    }

    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #4e73df;
    }

    .progress {
        height: 20px;
    }

    .progress-bar {
        line-height: 20px;
    }

    #attendanceResults table td {
        vertical-align: middle;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
</style>
@endsection
