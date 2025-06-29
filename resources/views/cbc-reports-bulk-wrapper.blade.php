
<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 20mm;
    }

    body {
        margin: 0 !important;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 13pt;
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
        position: absolute;
        left: 0;
        top: 0;
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
        font-size: 14pt;
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
        font-size: 13pt;
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
        page-break-inside: avoid;
        display: block;
        margin: 0 auto;
    }

    ul {
        padding-left: 1rem;
    }

    ul li {
        margin-bottom: 2px;
    }
     .logo-img {
        height: 30mm;
        max-height: 100px;
    }
}
</style>
@foreach($students as $report)
    <div class="report-page">
        @include('cbc.single-report-partial', $report)
    </div>
@endforeach


