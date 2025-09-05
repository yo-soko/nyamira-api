<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; visibility:hidden;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
         #printableArea, #printableArea * {
        visibility: visible; /* only show the printable area */
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <h3>Fingerprint Logs Report</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Arrival Time</th>
                <th>Leaving Time</th>
                <th>Log Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->user_id }}</td>
                    <td>{{ $log->user->name ?? 'Unknown' }}</td>
                    <td>{{ $log->pickup_time }}</td>
                    <td>{{ $log->dropoff_time }}</td>
                    <td>{{ $log->log_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
