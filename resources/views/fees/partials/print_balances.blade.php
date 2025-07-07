<!DOCTYPE html>
<html>
<head>
    <title>Fee Balances</title>
    <style>
        body { font-family: monospace; font-size: 12px; }
        .receipt { border-bottom: 1px dashed #000; margin-bottom: 20px; padding-bottom: 10px; text-align: center; page-break-after: always; }
        .receipt h4 { margin: 0; font-size: 14px; }
        .receipt p { margin: 2px 0; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">🖨️ Print Now</button>
</div>

@forelse($students as $student)
    <div class="receipt">
        <h4>JEMMAPP Preparatory School</h4>
        <h3>Finance Department</h3>
        <p>Date: {{ now()->format('d M Y, h:i A') }}</p>
        <hr>
        <p><strong>Student Name:</strong> {{ $student->full_name }}</p>
        <p><strong>Adm No:</strong> {{ $student->student_reg_number }}</p>
        <p><strong>Class:</strong>
            {{ $student->schoolClass->level->level_name ?? '' }}
            {{ $student->schoolClass->stream->name ?? '' }}
        </p>
        <p><strong>Fee Balance:</strong> KSh {{ number_format($student->current_balance, 2) }}</p>
        <p><strong>Printed By:</strong> {{ auth()->user()->name ?? 'System' }}</p>
    </div>
@empty
    <p>No students with pending balances.</p>
@endforelse

</body>
</html>
