<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Classes/session.php';
checkRole(['admin']);

require_once '../Classes/DBconnection.php';
require_once 'pdfhelper.php';

$conn = DBconnection::getInstance()->getConnection();


$sql  = "SELECT 
            MONTHNAME(b.created_at) as month_name,
            MONTH(b.created_at) as month_num,
            YEAR(b.created_at) as year,
            COUNT(b.id) as total_bookings,
            COALESCE(SUM(b.commission_amount), 0) as total_commission
         FROM bookings b
         WHERE b.status = 'completed'
         GROUP BY YEAR(b.created_at), MONTH(b.created_at)
         ORDER BY YEAR(b.created_at) DESC, MONTH(b.created_at) DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$bookingRevenue = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$sql2  = "SELECT
            MONTHNAME(payment_date) as month_name,
            MONTH(payment_date) as month_num,
            YEAR(payment_date) as year,
            COUNT(id) as total_payments,
            COALESCE(SUM(amount), 0) as total_fees
          FROM payments
          WHERE type = 'listing_fee' AND payment_status = 'completed'
          GROUP BY YEAR(payment_date), MONTH(payment_date)
          ORDER BY YEAR(payment_date) DESC, MONTH(payment_date) DESC";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$feeRevenue = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);


$totalCommission = array_sum(array_column($bookingRevenue, 'total_commission'));
$totalFees       = array_sum(array_column($feeRevenue, 'total_fees'));
$grandTotal      = $totalCommission + $totalFees;


$mpdf = createPDF('Revenue Report');

$html = getReportStyles();
$html .= getReportHeader('Revenue Report', 'Monthly Revenue — Commission & Listing Fees');


$html .= '<h3 style="color:#0f2a43; margin-top:20px;">Booking Commission Revenue</h3>';
$html .= '<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Year</th>
            <th>Completed Bookings</th>
            <th class="text-right">Commission Earned (Rs.)</th>
        </tr>
    </thead>
    <tbody>';

if (empty($bookingRevenue)) {
    $html .= '<tr><td colspan="4" class="text-center">No data found</td></tr>';
} else {
    foreach ($bookingRevenue as $row) {
        $html .= '<tr>
            <td>' . $row['month_name'] . '</td>
            <td>' . $row['year'] . '</td>
            <td class="text-center">' . $row['total_bookings'] . '</td>
            <td class="text-right">Rs. ' . number_format($row['total_commission'], 2) . '</td>
        </tr>';
    }
}
$html .= '</tbody></table>';


$html .= '<h3 style="color:#0f2a43; margin-top:25px;">Listing Fee Revenue</h3>';
$html .= '<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Year</th>
            <th>Payments Received</th>
            <th class="text-right">Fees Collected (Rs.)</th>
        </tr>
    </thead>
    <tbody>';

if (empty($feeRevenue)) {
    $html .= '<tr><td colspan="4" class="text-center">No data found</td></tr>';
} else {
    foreach ($feeRevenue as $row) {
        $html .= '<tr>
            <td>' . $row['month_name'] . '</td>
            <td>' . $row['year'] . '</td>
            <td class="text-center">' . $row['total_payments'] . '</td>
            <td class="text-right">Rs. ' . number_format($row['total_fees'], 2) . '</td>
        </tr>';
    }
}
$html .= '</tbody></table>';


$html .= '
<div class="summary-box">
    <div class="summary-title">Revenue Summary</div>
    <table style="margin-top:0;">
        <tr>
            <td>Total Commission from Bookings</td>
            <td class="text-right"><strong>Rs. ' . number_format($totalCommission, 2) . '</strong></td>
        </tr>
        <tr>
            <td>Total Listing Fees Collected</td>
            <td class="text-right"><strong>Rs. ' . number_format($totalFees, 2) . '</strong></td>
        </tr>
        <tr style="background-color:#0f2a43; color:#ffffff;">
            <td><strong>Grand Total Revenue</strong></td>
            <td class="text-right"><strong>Rs. ' . number_format($grandTotal, 2) . '</strong></td>
        </tr>
    </table>
</div>';

$html .= getReportFooter();

$mpdf->WriteHTML($html);
$mpdf->Output('CeylonTourMate_Revenue_Report_' . date('Ymd') . '.pdf', 'I');
exit();
?>