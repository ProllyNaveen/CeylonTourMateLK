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
            YEAR(b.created_at) as year,
            l.type as listing_type,
            COUNT(b.id) as total_bookings,
            SUM(CASE WHEN b.status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN b.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN b.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
            COALESCE(SUM(b.total_price), 0) as total_revenue
         FROM bookings b
         JOIN listings l ON b.listing_id = l.id
         GROUP BY YEAR(b.created_at), MONTH(b.created_at), l.type
         ORDER BY YEAR(b.created_at) DESC, MONTH(b.created_at) DESC, l.type";
$stmt = $conn->prepare($sql);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$totalBookings = array_sum(array_column($bookings, 'total_bookings'));
$totalRevenue  = array_sum(array_column($bookings, 'total_revenue'));

$mpdf = createPDF('Bookings Report');
$html = getReportStyles();
$html .= getReportHeader('Bookings Report', 'Monthly Bookings by Listing Type');

$html .= '<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Year</th>
            <th>Type</th>
            <th class="text-center">Total</th>
            <th class="text-center">Completed</th>
            <th class="text-center">Confirmed</th>
            <th class="text-center">Pending</th>
            <th class="text-center">Cancelled</th>
            <th class="text-right">Revenue (Rs.)</th>
        </tr>
    </thead>
    <tbody>';

if (empty($bookings)) {
    $html .= '<tr><td colspan="9" class="text-center">No bookings found</td></tr>';
} else {
    foreach ($bookings as $row) {
        $html .= '<tr>
            <td>' . $row['month_name'] . '</td>
            <td>' . $row['year'] . '</td>
            <td>' . ucfirst(str_replace('_', ' ', $row['listing_type'])) . '</td>
            <td class="text-center">' . $row['total_bookings'] . '</td>
            <td class="text-center">' . $row['completed'] . '</td>
            <td class="text-center">' . $row['confirmed'] . '</td>
            <td class="text-center">' . $row['pending'] . '</td>
            <td class="text-center">' . $row['cancelled'] . '</td>
            <td class="text-right">Rs. ' . number_format($row['total_revenue'], 2) . '</td>
        </tr>';
    }
}

$html .= '</tbody></table>';

$html .= '
<div class="summary-box">
    <div class="summary-title">Bookings Summary</div>
    <table style="margin-top:0;">
        <tr>
            <td>Total Bookings</td>
            <td class="text-right"><strong>' . $totalBookings . '</strong></td>
        </tr>
        <tr>
            <td>Total Revenue Generated</td>
            <td class="text-right"><strong>Rs. ' . number_format($totalRevenue, 2) . '</strong></td>
        </tr>
    </table>
</div>';

$html .= getReportFooter();

$mpdf->WriteHTML($html);
$mpdf->Output('CeylonTourMate_Bookings_Report_' . date('Ymd') . '.pdf', 'I');
exit();
?>