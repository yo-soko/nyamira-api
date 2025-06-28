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
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
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

    @if($type === 'daily')
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
                <td>{{ $item->student->full_name }}</td>
                <td>{{ $item->class->name }}</td>
                <td>{{ $item->route->route_name }}</td>
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
    @endif

    <div style="margin-top: 20px; font-size: 0.8em; text-align: right;">
        Generated on {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>

</html>
