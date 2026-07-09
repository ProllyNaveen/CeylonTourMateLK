<?php
require_once '../vendor/autoload.php';

function createPDF($title) {
    $mpdf = new \Mpdf\Mpdf([
        'margin_top'    => 20,
        'margin_bottom' => 20,
        'margin_left'   => 15,
        'margin_right'  => 15,
        'format'        => 'A4'
    ]);

    $mpdf->SetTitle($title);
    $mpdf->SetAuthor('CeylonTourMate.lk');

    return $mpdf;
}

function getReportStyles() {
    return '
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #2b2b2b;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0f2a43;
        }
        .report-title {
            font-size: 20px;
            font-weight: bold;
            color: #0f2a43;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 12px;
            color: #6b6b6b;
        }
        .report-date {
            font-size: 11px;
            color: #6b6b6b;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #0f2a43;
            color: #ffffff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 7px 8px;
            border-bottom: 1px solid #eeeeee;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-box {
            background-color: #f0f7fb;
            border: 1px solid #1c7293;
            border-radius: 5px;
            padding: 10px 15px;
            margin-top: 20px;
        }
        .summary-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f2a43;
            margin-bottom: 8px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-approved { color: #3b6d11; font-weight: bold; }
        .badge-pending { color: #7a5200; font-weight: bold; }
        .badge-rejected { color: #993c1d; font-weight: bold; }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eeeeee;
            font-size: 10px;
            color: #6b6b6b;
            text-align: center;
        }
    </style>
    ';
}

function getReportHeader($title, $subtitle) {
    $date = date('d M Y h:i A');
    return '
    <div class="report-header">
        <div class="report-title">CeylonTourMate.lk</div>
        <div class="report-subtitle">' . $subtitle . '</div>
        <div class="report-date">Generated on: ' . $date . '</div>
    </div>
    ';
}

function getReportFooter() {
    return '
    <div class="footer">
        This report was generated automatically by CeylonTourMate.lk &copy; ' . date('Y') . '
    </div>
    ';
}
?>