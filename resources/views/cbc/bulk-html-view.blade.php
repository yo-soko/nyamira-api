<!DOCTYPE html>
<html>
<head>
<title>Bulk CBC Reports</title>
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 5mm;
        }
   .print-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 12pt;
        padding: 5px 0;
        background: white;
        border-top: 1px solid #000;
    }
        body {
            margin: 0 !important;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 15pt;
            background: white;
            color: black;
        }

        body * {
            visibility: hidden;
        }

        .container-report, .container-report * {
            visibility: visible;
        }

        .container-report {
            margin-top: 20mm; /* add this */
            position: relative;
            width: 100%;
            min-height: 100%;
            background: white;
            box-sizing: border-box;
        }

        .page-header,
        .footer,
        .d-print-none {
            display: none !important;
        }

        h4, h5, h6 {
            font-size: 16pt;
            margin: 4px 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse !important;
        }

        th, td {
            padding: 6px;
            border: 1px solid #000 !important;
            text-align: left;
            vertical-align: middle;
            font-size: 15pt;
        }

        .table th {
            background: #f0f0f0;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .col-6, .col-md-6 {
            width: 50%;
        }

        canvas {
            display: block;
            margin: 0 auto;
            page-break-inside: avoid;
            image-rendering: crisp-edges;
            image-rendering: pixelated;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        ul {
            padding-left: 1rem;
        }

        ul li {
            margin-bottom: 2px;
        }

        .d-flex {
            display: flex !important;
            flex-wrap: nowrap !important;
        }

        .logo-img {
            height: 200px !important;
            width: auto !important;
        }

        .page-break {
            page-break-after: always;
        }
    }
</style>

</head>
<body >

    @foreach ($reports as $report)
        @php
            $student = $report['student'] ?? null;
            $marks = $report['marks'] ?? collect();
            $summary = $report['summary'] ?? [];
            $facilitatorName = $report['facilitatorName'] ?? '';
            $rubricCode = $report['rubricCode'] ?? fn($x) => '-';
            $chartData = $report['chartData'] ?? collect();
            $classAverage = $report['classAverage'] ?? 0;
            $levelAverage = $report['levelAverage'] ?? 0;
        @endphp

        <div class="container-report bg-white p-4">
            @include('cbc.single-report-partial', get_defined_vars())
        </div>

        <div class="page-break"></div>
    @endforeach


</body>
</html>
<script src="{{ asset('build/plugins/chartjs/chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($reports as $report)
        (function() {
            const canvasId = 'performanceChart_{{ $report['student']->id }}';
            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) return;

            const chartData = @json($report['chartData']);
            const gradeToPosition = score => {
                if (score >= 80) return 4;
                if (score >= 60) return 3;
                if (score >= 40) return 2;
                if (score > 0) return 1;
                return 0;
            };
            const positionToGrade = ['-', 'B.E', 'A.E', 'M.E', 'E.E'];
            const assessments = ['assessment_1', 'assessment_2', 'assessment_3'];
            const colors = [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(75, 192, 192, 0.5)'
            ];
            const borderColors = colors.map(c => c.replace('0.5','1'));

            const datasets = assessments.map((key, idx) => ({
                label: 'Assessment ' + (idx + 1),
                data: chartData.map(item => gradeToPosition(item[key])),
                backgroundColor: colors[idx % colors.length],
                borderColor: borderColors[idx % borderColors.length],
                borderWidth: 1
            }));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.map(item => item.subject),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 4,
                            ticks: {
                                stepSize: 1,
                                callback: value => positionToGrade[value]
                            },
                            title: { display: true, text: 'Grade' }
                        },
                        x: {
                            title: { display: true, text: 'Subjects' }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const datasetLabel = context.dataset.label;
                                    const rawScore = chartData[context.dataIndex][assessments[context.datasetIndex]];
                                    let grade = '-';
                                    if (rawScore >= 80) grade = 'E.E';
                                    else if (rawScore >= 60) grade = 'M.E';
                                    else if (rawScore >= 40) grade = 'A.E';
                                    else if (rawScore > 0) grade = 'B.E';
                                    return `${datasetLabel}: (${grade})`;
                                }
                            }
                        }
                    }
                }
            });
        })();
    @endforeach
});
</script>
<script>
window.addEventListener('load', function() {
    setTimeout(() => { window.print(); }, 500);
});
</script>
