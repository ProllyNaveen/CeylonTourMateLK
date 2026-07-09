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
            role,
            COUNT(*) as total,
            SUM(CASE WHEN is_approved = 1 AND is_suspended = 0 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN is_suspended = 1 THEN 1 ELSE 0 END) as suspended
         FROM users
         WHERE role != 'admin'
         GROUP BY role
         ORDER BY role";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$totalUsers = array_sum(array_column($users, 'total'));

$mpdf = createPDF('Users Report');
$html = getReportStyles();
$html .= getReportHeader('Users Report', 'All Time User Statistics by Role');

$html .= '<table>
    <thead>
        <tr>
            <th>Role</th>
            <th class="text-center">Total</th>
            <th class="text-center">Active</th>
            <th class="text-center">Pending Approval</th>
            <th class="text-center">Suspended</th>
        </tr>
    </thead>
    <tbody>';

if (empty($users)) {
    $html .= '<tr><td colspan="5" class="text-center">No users found</td></tr>';
} else {
    foreach ($users as $row) {
        $html .= '<tr>
            <td>' . ucfirst(str_replace('_', ' ', $row['role'])) . '</td>
            <td class="text-center">' . $row['total'] . '</td>
            <td class="text-center badge-approved">' . $row['active'] . '</td>
            <td class="text-center badge-pending">' . $row['pending'] . '</td>
            <td class="text-center badge-rejected">' . $row['suspended'] . '</td>
        </tr>';
    }
}

$html .= '</tbody></table>';

$html .= '
<div class="summary-box">
    <div class="summary-title">Users Summary</div>
    <table style="margin-top:0;">
        <tr>
            <td>Total Registered Users</td>
            <td class="text-right"><strong>' . $totalUsers . '</strong></td>
        </tr>
    </table>
</div>';

$html .= getReportFooter();

$mpdf->WriteHTML($html);
$mpdf->Output('CeylonTourMate_Users_Report_' . date('Ymd') . '.pdf', 'I');
exit();
?>