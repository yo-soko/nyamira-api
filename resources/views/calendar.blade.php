<?php $page = 'calendar'; ?>
@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content">

        <!-- Header -->
        <div class="add-item d-flex justify-content-between align-items-center mb-4">
            <div class="page-title">
                <h4>Event Calendar</h4>
                <h6>Manage School Events</h6>
            </div>
            <div class="page-btn">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal" id="addEventBtn">
                    <i class="ti ti-circle-plus me-1"></i>Add Event
                </button>
            </div>
        </div>

        <!-- Calendar Display -->
        <div class="calendar-container bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <!-- Month/Year Navigation -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="me-3 mb-0" id="currentMonthYear">
                        {{ $currentDate->format('F Y') }}
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary" id="prevYear">
                            <i class="ti ti-chevrons-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="prevMonth">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="todayBtn">Today</button>
                        <button class="btn btn-sm btn-outline-secondary" id="nextMonth">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="nextYear">
                            <i class="ti ti-chevrons-right"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary active" id="monthViewBtn">Month</button>
                    <button class="btn btn-sm btn-outline-secondary" id="weekViewBtn">Week</button>
                    <button class="btn btn-sm btn-outline-secondary" id="dayViewBtn">Day</button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="mb-5">
                <!-- Weekday Headers (7 columns) -->
                <div class="row g-1 mb-2 weekday-header">
                    <div class="col text-center fw-medium text-gray-600 py-2">Sun</div>
                    <div class="col text-center fw-medium text-gray-600 py-2">Mon</div>
                    <div class="col text-center fw-medium text-gray-600 py-2">Tue</div>
                    <div class="col text-center fw-medium text-gray-600 py-2">Wed</div>
                    <div class="col text-center fw-medium text-gray-600 py-2">Thu</div>
                    <div class="col text-center fw-medium text-gray-600 py-2">Fri</div>
                    <div class="col text-center fw-medium text-gray-600 py-2">Sat</div>
                </div>

                <!-- Calendar Days (5 rows × 7 columns) -->
                <div class="row g-1 calendar-grid" id="calendarDays">
                    @php
                    // Get first day of current month
                    $firstDayOfMonth = $currentDate->copy()->startOfMonth();
                    // Find Sunday of the week containing the 1st of the month
                    $startDate = $firstDayOfMonth->copy()->startOfWeek();

                    // Generate exactly 35 days (5 weeks)
                    $totalDays = 35;
                    @endphp

                    @for($i = 0; $i < $totalDays; $i++)
                        @php
                        $currentDay = $startDate->copy()->addDays($i);
                        $isCurrentMonth = $currentDay->month == $currentDate->month;
                        $isToday = $currentDay->isToday();
                        $isSunday = $currentDay->dayOfWeek == Carbon\Carbon::SUNDAY;
                        $isSaturday = $currentDay->dayOfWeek == Carbon\Carbon::SATURDAY;

                        // Get events for this day
                        $dayEvents = $events->filter(function($event) use ($currentDay) {
                            return $event->event_date->format('Y-m-d') == $currentDay->format('Y-m-d');
                        });
                        @endphp

                        <div class="col day-cell text-start border
                            {{ !$isCurrentMonth ? 'empty-day' : '' }}
                            {{ $isSunday ? 'sunday' : '' }}
                            {{ $isSaturday ? 'saturday' : '' }}
                            {{ $isToday ? 'today' : '' }}
                            {{ $dayEvents->where('is_holiday', true)->count() > 0 ? 'holiday' : '' }}"
                            data-date="{{ $currentDay->format('Y-m-d') }}">

                            <div class="day-number fw-bold small
                                {{ $isSunday ? 'text-danger' : '' }}
                                {{ $isSaturday ? 'text-primary' : '' }}
                                {{ !$isSunday && !$isSaturday ? 'text-secondary' : '' }}">
                                {{ $currentDay->day }}
                            </div>

                            @if($isCurrentMonth)
                                @foreach($dayEvents->take(2) as $event)
                                <div class="badge d-block text-truncate px-1 mb-1 {{ $event->is_holiday ? 'holiday' : '' }}"
                                    style="background-color: {{ $event->event_color }}; color: {{ $event->contrast_color }};"
                                    data-event-id="{{ $event->id }}"
                                    draggable="true">
                                    {{ $event->event_name }}
                                    @if($event->start_time)
                                    <small>({{ $event->start_time->format('g:i A') }})</small>
                                    @endif
                                </div>
                                @endforeach

                                @if($dayEvents->count() > 2)
                                <div class="more-events small text-muted">
                                    +{{ $dayEvents->count() - 2 }} more
                                </div>
                                @endif
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Upcoming Events Section -->
            <div class="border-top pt-4">
                <h2 class="h5 mb-3 text-gray-900">Upcoming Events</h2>
                <div class="list-group list-group-flush" id="upcomingEvents">
                    @foreach($upcomingEvents->take(5) as $event)
                    <a href="#" class="list-group-item list-group-item-action" data-event-id="{{ $event->id }}">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $event->event_name }}</h6>
                            <small class="text-muted">{{ $event->event_date->format('M j, Y') }}</small>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="badge me-2" style="background-color: {{ $event->event_color }}">&nbsp;&nbsp;</span>
                            @if($event->start_time)
                            <small>{{ $event->start_time->format('g:i A') }}</small>
                            @endif
                            @if($event->event_location)
                            <small class="ms-2"><i class="ti ti-map-pin me-1"></i>{{ $event->event_location }}</small>
                            @endif
                        </div>
                        <p class="mb-1 text-muted">{{ Str::limit($event->description, 100) ?: 'No description' }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Event Modal -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="eventForm" method="POST" action="{{ route('school-calendar.store') }}">
                        @csrf
                        @if(isset($event) && $event->id)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="modal-body">
                            <input type="hidden" id="eventId" name="id" value="{{ $event->id ?? '' }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="eventName" class="form-label">Event Name *</label>
                                        <input type="text" class="form-control" id="eventName" name="event_name"
                                            value="{{ old('event_name', $event->event_name ?? '') }}" required>
                                        @error('event_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="eventColor" class="form-label">Color *</label>
                                        <input type="color" class="form-control form-control-color" id="eventColor"
                                            name="event_color" value="{{ old('event_color', $event->event_color ?? '#3b82f6') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventDate" class="form-label">Date *</label>
                                        <input type="date" class="form-control" id="eventDate" name="event_date"
                                            value="{{ old('event_date', $event->event_date ?? date('Y-m-d')) }}" required>
                                        @error('event_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventLocation" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="eventLocation" name="event_location"
                                            value="{{ old('event_location', $event->event_location ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="startTime" class="form-label">Start Time</label>
                                        <input type="time" class="form-control" id="startTime" name="start_time"
                                            value="{{ old('start_time', $event->start_time ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="endTime" class="form-label">End Time</label>
                                        <input type="time" class="form-control" id="endTime" name="end_time"
                                            value="{{ old('end_time', $event->end_time ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isHoliday" name="is_holiday"
                                        value="1" {{ old('is_holiday', $event->is_holiday ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isHoliday">
                                        Is this a holiday?
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $event->description ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger me-auto" id="deleteEventBtn" style="display: none;">Delete</button>
                            <button type="submit" class="btn btn-primary" id="saveEventBtn">Save Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Event Details Modal -->
        <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventDetailsTitle">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="eventDetailsContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="editEventBtn">Edit</button>
                        <button type="button" class="btn btn-danger" id="deleteEventDetailsBtn">Delete</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Current view state
        let currentView = {
            year: {{ $currentDate->format('Y') }},
            month: {{ $currentDate->format('m') - 1 }}, // JavaScript months are 0-indexed
            view: 'month'
        };

        // Initialize modals
        const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
        const eventDetailsModal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
        let currentEventId = null;

        // Set today's date as default in the form
        document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];

        // Navigation handlers
        document.getElementById('prevYear').addEventListener('click', () => {
            currentView.year--;
            loadCalendar();
        });

        document.getElementById('prevMonth').addEventListener('click', () => {
            currentView.month--;
            if (currentView.month < 0) {
                currentView.month = 11;
                currentView.year--;
            }
            loadCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentView.month++;
            if (currentView.month > 11) {
                currentView.month = 0;
                currentView.year++;
            }
            loadCalendar();
        });

        document.getElementById('nextYear').addEventListener('click', () => {
            currentView.year++;
            loadCalendar();
        });

        document.getElementById('todayBtn').addEventListener('click', () => {
            const today = new Date();
            currentView.year = today.getFullYear();
            currentView.month = today.getMonth();
            loadCalendar();
        });

        // View switching handlers
        document.getElementById('monthViewBtn').addEventListener('click', () => {
            currentView.view = 'month';
            updateViewButtons();
            loadCalendar();
        });

        document.getElementById('weekViewBtn').addEventListener('click', () => {
            currentView.view = 'week';
            updateViewButtons();
            loadCalendar();
        });

        document.getElementById('dayViewBtn').addEventListener('click', () => {
            currentView.view = 'day';
            updateViewButtons();
            loadCalendar();
        });

        function updateViewButtons() {
            document.getElementById('monthViewBtn').classList.toggle('active', currentView.view === 'month');
            document.getElementById('weekViewBtn').classList.toggle('active', currentView.view === 'week');
            document.getElementById('dayViewBtn').classList.toggle('active', currentView.view === 'day');
        }

        // Load calendar data via AJAX
        function loadCalendar() {
            fetch(`/school-calendar/data?year=${currentView.year}&month=${currentView.month + 1}&view=${currentView.view}`)
                .then(response => response.json())
                .then(data => {
                    updateCalendarUI(data);
                })
                .catch(error => {
                    console.error('Error loading calendar:', error);
                    showToast('error', 'Failed to load calendar data');
                });
        }

        // Update the calendar UI with new data
        function updateCalendarUI(data) {
            // Update month/year display
            document.getElementById('currentMonthYear').textContent =
                `${new Date(data.currentYear, data.currentMonth).toLocaleString('default', { month: 'long' })} ${data.currentYear}`;

            // Update calendar days
            const calendarDays = document.getElementById('calendarDays');
            calendarDays.innerHTML = '';

            // Generate calendar grid (6 rows x 7 columns for full week view)
            const firstDay = new Date(data.currentYear, data.currentMonth, 1);
            const startDayOfWeek = firstDay.getDay();
            const daysInMonth = new Date(data.currentYear, data.currentMonth + 1, 0).getDate();
            const totalCells = 42; // 6 weeks * 7 days
            const currentDay = new Date(firstDay);
            currentDay.setDate(currentDay.getDate() - startDayOfWeek);

            let calendarHtml = '';
            let cellsAdded = 0;

            while (cellsAdded < totalCells) {
                const isCurrentMonth = currentDay.getMonth() === data.currentMonth;
                const isToday = isSameDay(currentDay, new Date());
                const isSunday = currentDay.getDay() === 0;
                const isSaturday = currentDay.getDay() === 6;
                const dateStr = currentDay.toISOString().split('T')[0];

                // Find events for this day
                const dayEvents = isCurrentMonth ?
                    data.events.filter(event => event.event_date === dateStr) : [];
                const isHoliday = dayEvents.some(event => event.is_holiday);

                // Build day cell HTML
                calendarHtml += `<div class="col day-cell text-start border
                    ${!isCurrentMonth ? 'empty-day' : ''}
                    ${isSunday ? 'sunday' : ''}
                    ${isSaturday ? 'saturday' : ''}
                    ${isToday ? 'today' : ''}
                    ${isHoliday ? 'holiday' : ''}"
                    data-date="${dateStr}">

                    <div class="day-number fw-bold small
                        ${isSunday ? 'text-danger' : ''}
                        ${isSaturday ? 'text-primary' : ''}
                        ${!isSunday && !isSaturday ? 'text-secondary' : ''}">
                        ${currentDay.getDate()}
                    </div>`;

                if (isCurrentMonth) {
                    dayEvents.slice(0, 3).forEach(event => {
                        const textColor = getContrastColor(event.event_color);
                        calendarHtml += `<div class="badge d-block text-truncate px-1 mb-1 ${event.is_holiday ? 'holiday' : ''}"
                            style="background-color: ${event.event_color}; color: ${textColor};"
                            data-event-id="${event.id}"
                            draggable="true">
                            ${event.event_name}
                            ${event.start_time ? `<small>(${formatTime(event.start_time)})</small>` : ''}
                        </div>`;
                    });

                    if (dayEvents.length > 3) {
                        calendarHtml += `<div class="event-indicator">
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <small>+${dayEvents.length - 3} more</small>
                        </div>`;
                    }
                }

                calendarHtml += `</div>`;
                cellsAdded++;

                // Move to next day
                currentDay.setDate(currentDay.getDate() + 1);
            }

            calendarDays.innerHTML = calendarHtml;

            // Add event listeners to new day cells
            document.querySelectorAll('.day-cell').forEach(cell => {
                cell.addEventListener('click', function() {
                    const date = this.dataset.date;
                    if (date) {
                        document.getElementById('eventDate').value = date;
                        resetEventForm();
                        eventModal.show();
                    }
                });
            });

            // Add event listeners to new event badges
            document.querySelectorAll('.badge[data-event-id]').forEach(badge => {
                badge.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const eventId = this.dataset.eventId;
                    showEventDetails(eventId);
                });
            });

            // Update upcoming events (limit to 5)
            const upcomingEvents = document.getElementById('upcomingEvents');
            upcomingEvents.innerHTML = '';

            data.upcomingEvents.slice(0, 5).forEach(event => {
                const eventItem = document.createElement('a');
                eventItem.className = 'list-group-item list-group-item-action';
                eventItem.dataset.eventId = event.id;
                eventItem.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${event.event_name}</h6>
                        <small class="text-muted">${formatDate(event.event_date)}</small>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge me-2" style="background-color: ${event.event_color}">&nbsp;&nbsp;</span>
                        ${event.start_time ? `<small>${formatTime(event.start_time)}</small>` : ''}
                        ${event.event_location ? `<small class="ms-2"><i class="ti ti-map-pin me-1"></i>${event.event_location}</small>` : ''}
                    </div>
                    <p class="mb-1 text-muted">${event.description ? truncate(event.description, 100) : 'No description'}</p>
                `;
                eventItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    showEventDetails(event.id);
                });
                upcomingEvents.appendChild(eventItem);
            });

            // Reinitialize drag and drop
            initDragAndDrop();
        }

        // Show event details in modal
        function showEventDetails(eventId) {
            fetch(`/school-calendar/events/${eventId}`)
                .then(response => response.json())
                .then(event => {
                    currentEventId = event.id;

                    // Build the event details HTML
                    let detailsHtml = `
                        <div class="mb-3">
                            <h6 class="text-primary">${event.event_name}</h6>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge me-2" style="background-color: ${event.event_color}">&nbsp;&nbsp;</span>
                                <span>${formatDate(event.event_date)}</span>
                            </div>
                            ${event.start_time ? `
                            <div class="mb-2">
                                <i class="ti ti-clock me-2"></i>
                                ${formatTime(event.start_time)}
                                ${event.end_time ? `- ${formatTime(event.end_time)}` : ''}
                            </div>` : ''}
                            ${event.event_location ? `
                            <div class="mb-2">
                                <i class="ti ti-map-pin me-2"></i>
                                ${event.event_location}
                            </div>` : ''}
                            ${event.is_holiday ? `
                            <div class="mb-2">
                                <i class="ti ti-alert-triangle me-2"></i>
                                <span class="text-danger">Holiday</span>
                            </div>` : ''}
                            <div class="mb-2">
                                <h6 class="mb-1">Description:</h6>
                                <p>${event.description || 'No description provided'}</p>
                            </div>
                        </div>
                    `;

                    document.getElementById('eventDetailsTitle').textContent = event.event_name;
                    document.getElementById('eventDetailsContent').innerHTML = detailsHtml;
                    eventDetailsModal.show();
                })
                .catch(error => {
                    console.error('Error fetching event details:', error);
                    showToast('error', 'Failed to load event details');
                });
        }

        // Form validation before submission
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            if (!validateEventForm()) {
                e.preventDefault();
            }
            // If validation passes, the form will submit normally
        });

        // Delete event button handler
        document.getElementById('deleteEventBtn').addEventListener('click', function() {
            const eventId = document.getElementById('eventId').value;
            if (eventId) {
                if (confirm('Are you sure you want to delete this event?')) {
                    // Create a form for deletion
                    const deleteForm = document.createElement('form');
                    deleteForm.method = 'POST';
                    deleteForm.action = '{{ route("calendar.delete", "") }}/' + eventId;

                    // Add CSRF token and method spoofing
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    deleteForm.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    deleteForm.appendChild(methodInput);

                    document.body.appendChild(deleteForm);
                    deleteForm.submit();
                }
            }
        });

        // Edit event button handler (in details modal)
        document.getElementById('editEventBtn').addEventListener('click', function() {
            if (currentEventId) {
                eventDetailsModal.hide();
                // Redirect to edit page or populate form
                window.location.href = '{{ route("school-calendar.edit", "") }}/' + currentEventId;
            }
        });

        // Delete event button handler (in details modal)
        document.getElementById('deleteEventDetailsBtn').addEventListener('click', function() {
            if (currentEventId) {
                if (confirm('Are you sure you want to delete this event?')) {
                    // Create a form for deletion
                    const deleteForm = document.createElement('form');
                    deleteForm.method = 'POST';
                    deleteForm.action = '{{ route("calendar.delete", "") }}/' + currentEventId;

                    // Add CSRF token and method spoofing
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    deleteForm.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    deleteForm.appendChild(methodInput);

                    document.body.appendChild(deleteForm);
                    deleteForm.submit();
                }
            }
        });

        // Helper functions
        function isSameDay(date1, date2) {
            return date1.getFullYear() === date2.getFullYear() &&
                   date1.getMonth() === date2.getMonth() &&
                   date1.getDate() === date2.getDate();
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function formatTime(timeStr) {
            if (!timeStr) return '';
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        function truncate(str, length) {
            return str && str.length > length ? str.substring(0, length) + '...' : str;
        }

        function getContrastColor(hexColor) {
            // Convert hex to RGB
            const r = parseInt(hexColor.substr(1, 2), 16);
            const g = parseInt(hexColor.substr(3, 2), 16);
            const b = parseInt(hexColor.substr(5, 2), 16);

            // Calculate luminance
            const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

            // Return black for light colors, white for dark colors
            return luminance > 0.5 ? '#000000' : '#ffffff';
        }

        function validateEventForm() {
            let isValid = true;
            const eventName = document.getElementById('eventName');
            const eventDate = document.getElementById('eventDate');

            // Reset validation states
            eventName.classList.remove('is-invalid');
            eventDate.classList.remove('is-invalid');

            // Validate required fields
            if (!eventName.value.trim()) {
                eventName.classList.add('is-invalid');
                isValid = false;
            }

            if (!eventDate.value) {
                eventDate.classList.add('is-invalid');
                isValid = false;
            }

            // Validate time range if both times are provided
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;

            if (startTime && endTime && startTime >= endTime) {
                showToast('error', 'End time must be after start time');
                isValid = false;
            }

            return isValid;
        }

        function editEvent(event) {
            resetEventForm();
            document.getElementById('modalTitle').textContent = 'Edit Event';
            document.getElementById('eventId').value = event.id;
            document.getElementById('eventName').value = event.event_name;
            document.getElementById('eventDate').value = event.event_date;
            document.getElementById('eventColor').value = event.event_color || '#3b82f6';
            document.getElementById('eventLocation').value = event.event_location || '';
            document.getElementById('description').value = event.description || '';

            if (event.start_time) {
                document.getElementById('startTime').value = event.start_time.substring(0, 5);
            }

            if (event.end_time) {
                document.getElementById('endTime').value = event.end_time.substring(0, 5);
            }

            if (event.is_holiday) {
                document.getElementById('isHoliday').checked = true;
            }

            document.getElementById('deleteEventBtn').style.display = 'block';
            eventModal.show();
        }

        function resetEventForm() {
            document.getElementById('eventForm').reset();
            document.getElementById('eventId').value = '';
            document.getElementById('modalTitle').textContent = 'Add New Event';
            document.getElementById('deleteEventBtn').style.display = 'none';
            document.getElementById('isHoliday').checked = false;
            document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
            document.getElementById('eventColor').value = '#3b82f6';

            // Reset validation states
            document.getElementById('eventName').classList.remove('is-invalid');
            document.getElementById('eventDate').classList.remove('is-invalid');
        }

        function showToast(type, message) {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            toastContainer.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Initialize drag and drop for events
        function initDragAndDrop() {
            const calendarDays = document.getElementById('calendarDays');

            calendarDays.addEventListener('dragstart', function(e) {
                if (e.target.classList.contains('badge')) {
                    e.dataTransfer.setData('text/plain', e.target.dataset.eventId);
                    e.target.classList.add('dragging');
                }
            });

            calendarDays.addEventListener('dragover', function(e) {
                if (e.target.classList.contains('day-cell') && e.target.dataset.date) {
                    e.preventDefault();
                    e.target.classList.add('drag-over');
                }
            });

            calendarDays.addEventListener('dragleave', function(e) {
                if (e.target.classList.contains('day-cell')) {
                    e.target.classList.remove('drag-over');
                }
            });

            calendarDays.addEventListener('drop', function(e) {
                e.preventDefault();
                if (e.target.classList.contains('day-cell') && e.target.dataset.date) {
                    const eventId = e.dataTransfer.getData('text/plain');
                    const newDate = e.target.dataset.date;

                    updateEventDate(eventId, newDate);
                    e.target.classList.remove('drag-over');
                }
            });

            calendarDays.addEventListener('dragend', function(e) {
                if (e.target.classList.contains('badge')) {
                    e.target.classList.remove('dragging');
                }
            });
        }

        function updateEventDate(eventId, newDate) {
            fetch('/school-calendar/update-event-date', {
                    method: 'POST',
                    body: JSON.stringify({
                        id: eventId,
                        event_date: newDate
                    }),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Event moved successfully');
                        loadCalendar();
                    } else {
                        showToast('error', data.message || 'Failed to move event');
                    }
                });
        }

        // Initial load
        loadCalendar();
    });
</script>

<style>
    /* Form validation styles */
    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .is-invalid ~ .invalid-feedback {
        display: block;
    }
        /* Calendar grid styling */
        .calendar-container {
            min-height: 600px;
        }

        /* Calendar grid layout */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 1px;
        }

        /* Weekday headers */
        .weekday-header {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 1px;
        }

        /* Day cell styling */
        .day-cell {
            min-height: 100px;
            padding: 4px;
            background-color: #fff;
            border-radius: 4px;
            transition: all 0.3s;
            position: relative;
            aspect-ratio: 1;
            height: auto;
            overflow: hidden;
        }

        .day-cell:hover {
            background-color: #f1f5f9;
            cursor: pointer;
        }

        .day-cell.today {
            border: 2px solid #3b82f6;
            background-color: #eff6ff;
        }

        .day-cell.sunday {
            background-color: #fff5f5 !important;
        }

        .day-cell.saturday {
            background-color: #f0f9ff !important;
        }

        .day-cell.holiday {
            background-color: #fff5f5;
        }

        .day-cell.empty-day {
            background-color: #f8fafc;
            color: #ccc;
            cursor: default;
        }

        .day-cell.empty-day .day-number {
            color: #ccc !important;
        }

        .day-number {
            margin-bottom: 4px;
        }

        /* Event badge styling */
        .badge {
            font-size: 0.7rem;
            padding: 3px 6px;
            margin: 1px 0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .badge.holiday {
            background-color: #ef4444 !important;
        }

        .badge::before {
            content: "";
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
            background-color: white;
            opacity: 0.7;
        }

        /* Event indicator for days with many events */
        .event-indicator {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.7rem;
            color: #6b7280;
        }

        .dot {
            height: 6px;
            width: 6px;
            background-color: currentColor;
            border-radius: 50%;
            display: inline-block;
            margin: 0 1px;
        }

        /* Drag and drop styling */
        .drag-over {
            background-color: #e2e8f0 !important;
        }

        .dragging {
            opacity: 0.5;
        }

        /* Modal styles */
        .color-picker option {
            padding: 8px;
        }

        /* Upcoming events styles */
        #upcomingEvents {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .day-cell {
                min-height: 80px;
                font-size: 0.8rem;
            }

            .badge {
                font-size: 0.6rem;
                padding: 2px 4px;
            }

            .month-year-selector {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .day-cell {
                min-height: 60px;
                padding-bottom: 15px;
            }

            .day-number {
                font-size: 0.7rem;
            }

            .badge {
                font-size: 0.5rem;
                padding: 1px 3px;
            }

            .event-indicator {
                font-size: 0.6rem;
            }
        }

        /* Large screen adjustments */
        @media (min-width: 1200px) {
            .calendar-grid {
                gap: 2px;
            }

            .day-cell {
                min-height: 120px;
            }

            .badge {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }

        @media (min-width: 1600px) {
            .day-cell {
                min-height: 140px;
            }
        }
</style>
@endsection
