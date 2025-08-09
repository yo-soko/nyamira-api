<!-- <!DOCTYPE html>
<html>
<head>
    <title>Fee Balances</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 80mm;
            padding: 0 5mm; /* space left and right */
            text-align: center;
            border-bottom: 1px dashed #000;
            page-break-after: always; /* force each student to print on a new page */
        }

        .receipt h4 {
            margin: 0;
            font-size: 14px;
        }

        .receipt h3 {
            margin: 2px 0;
            font-size: 13px;
        }

        .receipt p {
            margin: 2px 0;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        @media print {
            @page {
                size: 80mm auto; /* width fixed to 80mm, height auto so it cuts after text */
                margin: 0; /* remove printer margins */
            }

            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin:10px;">
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
</html> -->


<!DOCTYPE html>
<html>
<head>
    <title>Print Fee Balances</title>
    <style>
        @page {
            size: 80mm auto;
            margin-left: 5mm;
            margin-right: 5mm;
            margin-top: 0;
            margin-bottom: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .receipt {
            page-break-after: always;
        }

        .receipt:last-child {
            page-break-after: auto;
        }

        h4, h3 {
            margin: 0;
            text-align: center;
        }

        p {
            margin: 3px 0;
        }

        hr {
            margin: 5px 0;
            border: none;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 500);">

@foreach ($students as $student)
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
@endforeach

</body>
</html>
