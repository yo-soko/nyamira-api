<!DOCTYPE html>
<html>

<head>
    <title>Transport Report - {{ ucfirst($type) }} - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }
    </style>
</head>

<body>
    <h1>Transport Report - {{ ucfirst($type) }}</h1>
    <h3>Date: {{ $date }}</h3>

    @if($type === 'pickup' || $type === 'dropoff')
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Route</th>
                <th>Status</th>
                <th>Time</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                <td>{{ $item->route->route_name ?? 'N/A' }}</td>
                <td>{{ $type === 'pickup' ? $item->pickup_status : $item->dropoff_status }}</td>
                <td>{{ $type === 'pickup' ? $item->pickup_time : $item->dropoff_time }}</td>
                <td>{{ $type === 'pickup' ? $item->pickup_location : $item->dropoff_location }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'attendance')
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Route</th>
                <th>Pickup Status</th>
                <th>Pickup Time</th>
                <th>Pickup Location</th>
                <th>Dropoff Status</th>
                <th>Dropoff Time</th>
                <th>Dropoff Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                <td>{{ $item->route->route_name ?? 'N/A' }}</td>
                <td>{{ $item->pickup_status ?? '-' }}</td>
                <td>{{ $item->pickup_time ?? '-' }}</td>
                <td>{{ $item->pickup_location ?? '-' }}</td>
                <td>{{ $item->dropoff_status ?? '-' }}</td>
                <td>{{ $item->dropoff_time ?? '-' }}</td>
                <td>{{ $item->dropoff_location ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'daily')
    <!-- Daily Summary View -->
    <table>
        <thead>
            <tr>
                <th>Route</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Attendance Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{ $item->route_name }}</td>
                <td>{{ $item->present }}</td>
                <td>{{ $item->absent }}</td>
                <td>{{ $item->present + $item->absent > 0 ? round(($item->present / ($item->present + $item->absent)) * 100) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'monthly')
    <table>
        <thead>
            <tr>
                <th>Route</th>
                <th>Total Students</th>
                <th>Avg Attendance</th>
                <th>Best Day</th>
                <th>Worst Day</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{ $item['route_name'] }}</td>
                <td>{{ $item['total_students'] }}</td>
                <td>{{ $item['avg_attendance'] }}%</td>
                <td>{{ $item['best_day']['date'] }} ({{ $item['best_day']['rate'] }}%)</td>
                <td>{{ $item['worst_day']['date'] }} ({{ $item['worst_day']['rate'] }}%)</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'payments')
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Route</th>
                <th>Total Fee</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                <td>{{ $item->class->name ?? 'N/A' }}</td>
                <td>{{ $item->route->route_name ?? 'N/A' }}</td>
                <td class="text-right">KSh {{ number_format($item->transport_fee, 2) }}</td>
                <td class="text-right {{ $item->balance > 0 ? 'text-danger' : 'text-success' }}">
                    KSh {{ number_format($item->balance, 2) }}
                </td>
                <td class="text-center">
                    @if($item->balance > 0)
                    <span class="text-danger">Pending</span>
                    @else
                    <span class="text-success">Paid</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($type === 'vehicle')
    <table>
        <thead>
            <tr>
                <th>Vehicle</th>
                <th>Route</th>
                <th>Capacity</th>
                <th>Students</th>
                <th>Utilization</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $item)
            <tr>
                <td>{{ $item['registration_number'] }} ({{ $item['model'] }})</td>
                <td>{{ $item['route_name'] }}</td>
                <td>{{ $item['capacity'] }}</td>
                <td>{{ $item['students'] }}</td>
                <td>{{ $item['capacity'] > 0 ? round(($item['students'] / $item['capacity']) * 100) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <p><strong>No valid report type selected.</strong></p>
    @endif

    <div style="margin-top: 20px; font-size: 0.8em; text-align: right;">
        Generated on {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>

</html>
