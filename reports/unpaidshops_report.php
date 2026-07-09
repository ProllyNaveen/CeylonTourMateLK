<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Classes/session.php';
checkRole(['admin']);

require_once '../Classes/DBconnection.php';
require_once 'pdfhelper.php';

$conn = DBconnection::getInstance()->getConnection();

$currentMonth = date('n');
$currentYear  = date('Y');
$monthName    = date('F');

$sql  = "SELECT
            l.title as shop_name,
            l.city,
            sl.shop_type,
            u.firstname,
            u.lastname,
            u.email,
            u.phone
         FROM shop_listings sl
         JOIN listings l ON sl.listing_id = l.id
         JOIN users u ON l.user_id = u.id
         LEFT JOIN payments p ON p.shop_listing_id = sl.id
             AND p.type = 'listing_fee'
             AND p.payment_status = 'completed'
             AND p.fee_month = ?
             AND p.fee_year = ?
         WHERE p.id IS NULL
         AND l.status = 'approved'
         ORDER BY l.city, l.title";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $currentMonth, $currentYear);
$stmt->execute();
$unpaidShops = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$mpdf = createPDF('Unpaid Shop Fees Report');
$html = getReportStyles();
$html .= getReportHeader('Unpaid Shop Fees Report', 'Shop Listings That Have Not Paid Their Fee for ' . $monthName . ' ' . $currentYear);

$html .= '<table>
    <thead>
        <tr>
            <th>Shop Name</th>
            <th>Shop Type</th>
            <th>City</th>
            <th>Owner Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>';

if (empty($unpaidShops)) {
    $html .= '<tr><td colspan="6" class="text-center">All shops have paid their fees for this month!</td></tr>';
} else {
    foreach ($unpaidShops as $row) {
        $html .= '<tr>
            <td>' . $row['shop_name'] . '</td>
            <td>' . $row['shop_type'] . '</td>
            <td>' . $row['city'] . '</td>
            <td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>
            <td>' . $row['email'] . '</td>
            <td>' . $row['phone'] . '</td>
        </tr>';
    }
}

$html .= '</tbody></table>';

$html .= '
<div class="summary-box">
    <div class="summary-title">Summary</div>
    <table style="margin-top:0;">
        <tr>
            <td>Total Shops with Unpaid Fees for ' . $monthName . ' ' . $currentYear . '</td>
            <td class="text-right"><strong>' . count($unpaidShops) . '</strong></td>
        </tr>
    </table>
</div>';

$html .= getReportFooter();

$mpdf->WriteHTML($html);
$mpdf->Output('CeylonTourMate_Unpaid_Fees_Report_' . date('Ymd') . '.pdf', 'I');
exit();
?>