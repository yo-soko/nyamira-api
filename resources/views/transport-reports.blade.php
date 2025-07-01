@extends('layout.mainlayout')

@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Transport Reports</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Reports Card -->
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0"><i class="fas fa-file-alt mr-2"></i>Generate Transport Reports</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4 g-3">
                    <div class="col-lg-3 col-md-6">
                        <label for="reportType" class="form-label">Report Type</label>
                        <select class="form-select" id="reportType">
                            <option value="attendance">Attendance Report</option>
                            <option value="billing">Billing Report</option>
                            <option value="usage">Transport Usage Report</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="reportRoute" class="form-label">Route</label>
                        <select class="form-select" id="reportRoute">
                            <option value="">All Routes</option>
                            @foreach($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="reportMonth" class="form-label">Month</label>
                        <input type="month" class="form-control" id="reportMonth" value="{{ date('Y-m') }}">
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex align-items-end">
                        <div class="d-flex w-100 gap-2">
                            <button class="btn btn-primary flex-grow-1" id="generateReport">
                                <i class="fas fa-sync-alt mr-1"></i> Generate
                            </button>
                            <button class="btn btn-outline-secondary flex-grow-1" id="exportReport">
                                <i class="fas fa-download mr-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                <div id="reportResults">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i> Select report type and parameters to generate a report
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        // Report Generation
        $('#generateReport').click(function() {
            const reportType = $('#reportType').val();
            const routeId = $('#reportRoute').val();
            const month = $('#reportMonth').val();

            if (!month) {
                toastr.error('Please select a month for the report');
                return;
            }

            $('#reportResults').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h5>Generating Report...</h5>
                    <p class="text-muted">This may take a few moments</p>
                </div>
            `);

            $.ajax({
                url: '{{ route('transport.reports.search') }}',
                type: 'GET',
                data: {
                    type: reportType,
                    route_id: routeId,
                    month: month,
                },
                headers: {
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.error) {
                        $('#reportResults').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                ${response.message || 'Error generating report'}
                            </div>
                        `);
                        return;
                    }
                    renderReport(response, reportType, month);
                },
                error: function(xhr) {
                    let errorMsg = 'Failed to generate report. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#reportResults').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle mr-2"></i> ${errorMsg}
                        </div>
                    `);
                }
            });
        });

        // Function to render the report based on type
        function renderReport(data, reportType, month) {
            let html = '';
            const monthName = new Date(month + '-01').toLocaleString('default', {
                month: 'long',
                year: 'numeric'
            });

            switch (reportType) {
                case 'attendance':
                    html = renderAttendanceReport(data, monthName);
                    break;
                case 'billing':
                    html = renderBillingReport(data, monthName);
                    break;
                case 'usage':
                    html = renderUsageReport(data, monthName);
                    break;
                default:
                    if (!Array.isArray(data)) {
                        $('#reportResults').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Report data is malformed or not an array.
                            </div>
                        `);
                        return;
                    }
            }

            $('#reportResults').html(html);
            initializeReportDataTable();
        }

        // Render Attendance Report
        function renderAttendanceReport(data, monthName) {
            if (!data || data.length === 0) {
                return `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        No attendance data found for ${monthName}
                    </div>`;
            }

            let html = `
                <div class="report-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Attendance Report - ${monthName}
                        </h5>
                        <div class="text-muted small">Generated on ${new Date().toLocaleString()}</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover w-100" id="attendanceReportTable">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Route</th>
                                <th class="text-end">Present</th>
                                <th class="text-end">Absent</th>
                                <th class="text-center">Rate</th>
                            </tr>
                        </thead>
                        <tbody>`;

            data.forEach(item => {
                const attendanceRate = parseInt(item.attendance_rate) || 0;
                const progressColor = attendanceRate >= 90 ? 'bg-success' :
                    attendanceRate >= 75 ? 'bg-primary' :
                    attendanceRate >= 50 ? 'bg-warning' : 'bg-danger';

                html += `
                    <tr>
                        <td>${item.student || 'N/A'}</td>
                        <td>${item.class || 'N/A'}</td>
                        <td>${item.route || 'N/A'}</td>
                        <td class="text-end">${item.present_days || 0}</td>
                        <td class="text-end">${item.absent_days || 0}</td>
                        <td class="text-center">
                            <span class="badge ${progressColor}">${attendanceRate}%</span>
                        </td>
                    </tr>`;
            });

            html += `</tbody></table></div>`;
            return html;
        }

        // Render Billing Report
        function renderBillingReport(data, monthName) {
            if (!data || data.length === 0) {
                return `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        No billing data found for ${monthName}
                    </div>`;
            }

            let totalFee = 0;
            let totalPaid = 0;
            let totalBalance = 0;

            let html = `
                <div class="report-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice-dollar mr-2"></i>
                            Billing Report - ${monthName}
                        </h5>
                        <div class="text-muted small">Generated on ${new Date().toLocaleString()}</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover w-100" id="billingReportTable">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Route</th>
                                <th class="text-end">Fee (KSh)</th>
                                <th class="text-end">Paid (KSh)</th>
                                <th class="text-end">Balance (KSh)</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>`;

            data.forEach(item => {
                const fee = parseFloat(item.fee) || 0;
                const paid = parseFloat(item.paid) || 0;
                const balance = fee - paid;

                totalFee += fee;
                totalPaid += paid;
                totalBalance += balance;

                const statusClass = balance <= 0 ? 'bg-success' : 'bg-danger';
                const statusText = balance <= 0 ? 'Paid' : 'Pending';

                html += `
                    <tr>
                        <td>${item.student || 'N/A'}</td>
                        <td>${item.class || 'N/A'}</td>
                        <td>${item.route || 'N/A'}</td>
                        <td class="text-end">${fee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end">${paid.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end ${balance > 0 ? 'text-danger fw-semibold' : 'text-success'}">
                            ${balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                        </td>
                        <td class="text-center">
                            <span class="badge ${statusClass}">${statusText}</span>
                        </td>
                    </tr>`;
            });

            html += `
                </tbody>
                <tfoot class="fw-semibold">
                    <tr>
                        <td colspan="3" class="text-end">Totals:</td>
                        <td class="text-end">${totalFee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end">${totalPaid.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-end ${totalBalance > 0 ? 'text-danger' : 'text-success'}">
                            ${totalBalance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                </table>
            </div>`;

            return html;
        }

        // Initialize DataTables for reports
        function initializeReportDataTable() {
            $('table[id$="ReportTable"]').each(function() {
                $(this).DataTable({
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                         "<'row'<'col-sm-12'B>>",
                    buttons: [
                        {
                            extend: 'copy',
                            className: 'btn btn-sm btn-outline-secondary',
                            text: '<i class="fas fa-copy mr-1"></i> Copy'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-sm btn-outline-secondary',
                            text: '<i class="fas fa-file-excel mr-1"></i> Excel'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-outline-secondary',
                            text: '<i class="fas fa-file-pdf mr-1"></i> PDF'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-outline-secondary',
                            text: '<i class="fas fa-print mr-1"></i> Print'
                        }
                    ],
                    responsive: true,
                    pageLength: 25,
                    lengthMenu: [10, 25, 50, 100],
                    order: [[0, 'asc']],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        zeroRecords: "No matching records found"
                    },
                    initComplete: function() {
                        $('.dataTables_filter input').addClass('form-control form-control-sm');
                        $('.dataTables_length select').addClass('form-select form-select-sm');
                    }
                });
            });
        }

        // Export Report
        $('#exportReport').click(function() {
            const reportType = $('#reportType').val();
            const routeId = $('#reportRoute').val();
            const month = $('#reportMonth').val();

            if (!month) {
                toastr.error('Please select a month for the report');
                return;
            }

            const exportBtn = $(this);
            const originalHtml = exportBtn.html();
            exportBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');
            exportBtn.prop('disabled', true);

            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '/transport/reports/export';
            form.target = '_blank';

            const typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'type';
            typeInput.value = reportType;
            form.appendChild(typeInput);

            const routeInput = document.createElement('input');
            routeInput.type = 'hidden';
            routeInput.name = 'route_id';
            routeInput.value = routeId;
            form.appendChild(routeInput);

            const monthInput = document.createElement('input');
            monthInput.type = 'hidden';
            monthInput.name = 'month';
            monthInput.value = month;
            form.appendChild(monthInput);

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            form.appendChild(tokenInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            setTimeout(() => {
                exportBtn.html(originalHtml);
                exportBtn.prop('disabled', false);
            }, 2000);
        });
    });
</script>

<style>
    /* Clean, Professional Styles */
    .card {
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border-radius: 0.375rem;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 1rem 1.25rem;
    }

    .table {
        font-size: 0.875rem;
        border-color: #e0e0e0;
    }

    .table th {
        font-weight: 600;
        text-transform: none;
        background-color: #f8f9fa;
        border-bottom-width: 1px;
        padding: 0.75rem 1rem;
    }

    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-top: 1px solid #e0e0e0;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        border-radius: 0.25rem;
    }

    .btn {
        font-weight: 500;
    }

    .form-select, .form-control {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
    }

    .alert {
        border-radius: 0.375rem;
        border-left: none;
    }

    /* DataTables Customization */
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        padding: 0.25rem 0.5rem;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        padding: 0.25rem 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        border: 1px solid #dee2e6;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd;
        color: white !important;
        border-color: #0d6efd;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            border: none;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: left;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection
