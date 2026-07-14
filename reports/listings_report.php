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
            l.type as listing_type,
            COUNT(l.id) as total,
            SUM(CASE WHEN l.status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN l.status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN l.status = 'rejected' THEN 1 ELSE 0 END) as rejected,
            ROUND(AVG(r.rating), 1) as avg_rating,
            COUNT(DISTINCT r.id) as total_reviews
         FROM listings l
         LEFT JOIN reviews r ON l.id = r.listing_id
         WHERE l.is_deleted = 0
         GROUP BY l.type
         ORDER BY l.type";
$stmt = $conn->prepare($sql);
$stmt->execute();
$listings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$totalListings = array_sum(array_column($listings, 'total'));

$mpdf = createPDF('Listings Report');
$html = getReportStyles();
$html .= getReportHeader('Listings Report', 'All Listings Separated by Type');

$html .= '<table>
    <thead>
        <tr>
            <th>Listing Type</th>
            <th class="text-center">Total</th>
            <th class="text-center">Approved</th>
            <th class="text-center">Pending</th>
            <th class="text-center">Rejected</th>
            <th class="text-center">Avg Rating</th>
            <th class="text-center">Total Reviews</th>
        </tr>
    </thead>
    <tbody>';

if (empty($listings)) {
    $html .= '<tr><td colspan="7" class="text-center">No listings found</td></tr>';
} else {
    foreach ($listings as $row) {
        $html .= '<tr>
            <td>' . ucfirst(str_replace('_', ' ', $row['listing_type'])) . '</td>
            <td class="text-center">' . $row['total'] . '</td>
            <td class="text-center badge-approved">' . $row['approved'] . '</td>
            <td class="text-center badge-pending">' . $row['pending'] . '</td>
            <td class="text-center badge-rejected">' . $row['rejected'] . '</td>
            <td class="text-center">' . ($row['avg_rating'] ?? 'N/A') . '</td>
            <td class="text-center">' . $row['total_reviews'] . '</td>
        </tr>';
    }
}

$html .= '</tbody></table>';

$html .= '
<div class="summary-box">
    <div class="summary-title">Listings Summary</div>
    <table style="margin-top:0;">
        <tr>
            <td>Total Listings on Platform</td>
            <td class="text-right"><strong>' . $totalListings . '</strong></td>
        </tr>
    </table>
</div>';

$html .= getReportFooter();

$mpdf->WriteHTML($html);
$mpdf->Output('CeylonTourMate_Listings_Report_' . date('Ymd') . '.pdf', 'I');
exit();
?>