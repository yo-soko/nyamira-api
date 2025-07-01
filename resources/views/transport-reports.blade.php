@extends('layout.mainlayout')

@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="row mb-4 p-3 mt-3">
        <div class="col-md-12">
            <h2>Transport Reports</h2> {{-- missing <h2> --}}
        </div>
    </div>

    <!-- Reports Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Generate Transport Reports</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 col-sm-6 mb-2">
                    <select class="form-control" id="reportType">
                        <option value="attendance">Attendance Report</option>
                        <option value="billing">Billing Report</option>
                        <option value="usage">Transport Usage Report</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <select class="form-control" id="reportRoute">
                        <option value="">All Routes</option>
                        @foreach($routes as $route)
                        <option value="{{ $route->id }}">{{ $route->route_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <input type="month" class="form-control" id="reportMonth" value="{{ date('Y-m') }}">
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-primary" id="generateReport">
                            <i class="fas fa-sync-alt mr-1"></i> Generate
                        </button>
                        <button class="btn btn-success" id="exportReport">
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
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
                    <h5>Generating ${reportType.replace(/^\w/, c => c.toUpperCase())} Report...</h5>
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
        'Accept': 'application/json' // ✅ Forces Laravel to return JSON
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

        renderReport(response, reportType, month); // ✅ Should now receive an array
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
                    <h4 class="font-weight-bold text-primary">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Attendance Report - ${monthName}
                    </h4>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">Generated on ${new Date().toLocaleString()}</small>
                        <span class="badge badge-info">${data.length} records</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="attendanceReportTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Route</th>
                                <th>Present Days</th>
                                <th>Absent Days</th>
                                <th>Attendance Rate</th>
                            </tr>
                        </thead>
                        <tbody>`;

            data.forEach(item => {
                const attendanceRate = parseInt(item.attendance_rate) || 0;
                const progressColor = attendanceRate >= 90 ? 'success' :
                    attendanceRate >= 75 ? 'primary' :
                    attendanceRate >= 50 ? 'warning' : 'danger';

                html += `
                    <tr>
                        <td>${item.student || 'N/A'}</td>
                        <td>${item.class || 'N/A'}</td>
                        <td>${item.route || 'N/A'}</td>
                        <td>${item.present_days || 0}</td>
                        <td>${item.absent_days || 0}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1" style="height: 20px;">
                                    <div class="progress-bar bg-${progressColor}"
                                         role="progressbar"
                                         style="width: ${attendanceRate}%"
                                         aria-valuenow="${attendanceRate}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        ${attendanceRate}%
                                    </div>
                                </div>
                            </div>
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
                    <h4 class="font-weight-bold text-primary">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Billing Report - ${monthName}
                    </h4>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">Generated on ${new Date().toLocaleString()}</small>
                        <span class="badge badge-info">${data.length} records</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="billingReportTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Route</th>
                                <th>Fee (KSh)</th>
                                <th>Paid (KSh)</th>
                                <th>Balance (KSh)</th>
                                <th>Status</th>
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

                const statusClass = balance <= 0 ? 'badge-success' : 'badge-danger';
                const statusText = balance <= 0 ? 'Paid' : 'Pending';

                html += `
                    <tr>
                        <td>${item.student || 'N/A'}</td>
                        <td>${item.class || 'N/A'}</td>
                        <td>${item.route || 'N/A'}</td>
                        <td class="text-right">${fee.toFixed(2)}</td>
                        <td class="text-right">${paid.toFixed(2)}</td>
                        <td class="text-right ${balance > 0 ? 'text-danger font-weight-bold' : 'text-success'}">
                            ${balance.toFixed(2)}
                        </td>
                        <td>
                            <span class="badge ${statusClass}">${statusText}</span>
                        </td>
                    </tr>`;
            });

            html += `
                </tbody>
                <tfoot class="font-weight-bold">
                    <tr>
                        <td colspan="3" class="text-right">Totals:</td>
                        <td class="text-right">${totalFee.toFixed(2)}</td>
                        <td class="text-right">${totalPaid.toFixed(2)}</td>
                        <td class="text-right ${totalBalance > 0 ? 'text-danger' : 'text-success'}">
                            ${totalBalance.toFixed(2)}
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
            $('table[id$="ReportTable"]').DataTable({
                dom: '<"top"Bf>rt<"bottom"lip><"clear">',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                responsive: true,
                pageLength: 25,
                order: [
                    [0, 'asc']
                ]
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
    /* Responsive Report Styles */
    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 1rem;
        }

        .report-header h4 {
            font-size: 1.25rem;
        }

        #reportType,
        #reportRoute,
        #reportMonth {
            margin-bottom: 10px;
        }

        .btn-group {
            flex-wrap: wrap;
        }

        .btn-group .btn {
            flex: 1 0 45%;
            margin: 2px;
        }

        table {
            font-size: 0.85rem;
        }

        .progress {
            height: 15px;
        }
    }

    /* Enhanced Report Table Styles */
    #reportResults table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }

    #reportResults table th {
        background-color: #f8f9fc;
        font-weight: 600;
        border-bottom: 2px solid #e3e6f0;
    }

    #reportResults table td,
    #reportResults table th {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #e3e6f0;
    }

    #reportResults table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .progress {
        background-color: #eaecf4;
        border-radius: 0.35rem;
    }

    .progress-bar {
        border-radius: 0.35rem;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .alert {
        border-left: 4px solid;
    }

    .alert-info {
        border-left-color: #36b9cc;
    }

    .alert-danger {
        border-left-color: #e74a3b;
    }

    .alert-warning {
        border-left-color: #f6c23e;
    }

    .text-success {
        color: #1cc88a !important;
    }

    .text-danger {
        color: #e74a3b !important;
    }

    .text-primary {
        color: #4e73df !important;
    }

    .text-muted {
        color: #858796 !important;
    }
</style>
@endsection
