<!--


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

@php
    // Normalize: if we got a single student, wrap it into a collection
    $list = isset($students) ? $students : collect([$student ?? null]);
@endphp

@foreach ($list as $student)
    @if ($student)
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
    @endif
@endforeach

</body>

</html> -->

<!DOCTYPE html>
<html>
<head>
    <title>Print Fee Balances</title>
    <style>
        @page {
            size: 80mm auto;   /* full 80mm width */
            margin: 0;         /* use maximum width */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 16px;     /* bigger text */
            line-height: 1.6;    /* more spacing */
            margin: 5px;         /* avoid cutting edges */
        }

        .receipt {
            width: 100%;
            padding: 5px 0;
            border-bottom: 1px dashed #000; /* separation between receipts */
            page-break-after: always; /* 👈 forces new page for each student */
        }
        .receipt:last-child {
            page-break-after: auto; /* 👈 last one won’t have an extra blank page */
        }

        h2 {
            margin: 2px 0;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        h3 {
            margin: 4px 0;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        h4 {
            margin: 2px 0 6px 0;
            text-align: center;
            font-size: 25px;
            font-weight: bold;
        }

        h6 {
            margin: 0;
            text-align: center;
            font-size: 12px;
            font-weight: normal;
        }

        p {
            margin: 4px 0;
            font-size: 14px;
        }

        hr {
            margin: 8px 0;
            border: none;
            border-top: 2px solid #000;
        }

        .highlight {
            font-size: 18px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            font-style: italic;
        }
        .powered {
            margin-top: 6px;
            font-size: 11px;
            text-align: center;
            color: #444;
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 800);">

@php
    $list = isset($students) ? $students : collect([$student ?? null]);
@endphp

@foreach ($list as $student)
    @if ($student)
    <div class="receipt">
        <div style="text-align: center; margin-bottom: 5px;">
            <img src="{{ asset('images/school-logo.png') }}" 
                alt="School Logo" 
                style="width:100px; height:auto; display:block; margin:0 auto 5px;">
        </div>
                
        <h4>JEMMAPP</h4>
        <h2> PREPARATORY SCHOOL</h2>
        <h6>P.O BOX 1984-40200, KISII</h6>
        <h6>TEL: 0746881491 / 0726732322</h6>
        <h3>Finance Department</h3>
        <h6><strong>Date:</strong> {{ now()->format('d M Y, h:i A') }}</h6>
        <hr>
        <p><strong>Learner's Name:</strong> {{ $student->full_name }}</p>
        <p><strong>Admission No:</strong> {{ $student->student_reg_number }}</p>
        <p><strong>Grade:</strong>
            {{ $student->schoolClass->level->level_name ?? '' }}
            {{ $student->schoolClass->stream->name ?? '' }}
        </p>
       
    <p><strong>Paid Amount(Latest):</strong> 
    @if($student->recent_payment && $student->recent_payment > 0)
        KSh {{ number_format($student->recent_payment, 2) }}
    @else
        No recent payment
    @endif
</p>


        <p><strong>Fee Balance:</strong> <span class="highlight">KSh {{ number_format($student->current_balance, 2) }}</span></p>
        <p><strong>Printed By:</strong> {{ auth()->user()->name ?? 'System' }}</p>
        <div class="footer">
            Thank you for choosing JEMMAPP as your preferred school.
        </div>
        <div class="powered">
            Software powered by <strong>JavaPA Limited</strong>
        </div>
    </div>
    @endif
@endforeach

</body>
</html>


