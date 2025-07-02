<!DOCTYPE html>
<html>
<head>
    <title>Fee Dashboard Report</title>
    <style>
        body { font-family: sans-serif; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>JEMMAPP Fee Report</h2>

    <p><strong>Term:</strong> {{ $currentTerm->term_name ?? 'N/A' }} - {{ $currentTerm->year ?? '' }}</p>
    <p><strong>Total Students:</strong> {{ $totalStudents }}</p>
    <p><strong>Total Collected:</strong> Ksh {{ number_format($totalCollected, 2) }}</p>
    <p><strong>Total Outstanding:</strong> Ksh {{ number_format($outstandingBalance, 2) }}</p>

    <table>
        <thead>
            <tr>
                <th>Level</th>
                <th>Expected</th>
                <th>Paid</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($levelData as $data)
                <tr>
                    <td>{{ $data['name'] }}</td>
                    <td>Ksh {{ number_format($data['expected'], 2) }}</td>
                    <td>Ksh {{ number_format($data['paid'], 2) }}</td>
                    <td>Ksh {{ number_format($data['balance'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
