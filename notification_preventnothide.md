<?php
include "connect.php";

$currentDate = date('Y-m-d');

// Updated total items query to include availability check
$totalItemsQuery = "
    SELECT COUNT(*) as total
    FROM inventory
    WHERE maintenance_from <= '$currentDate'
    AND (
        DATE_FORMAT(maintenance_from, '%Y-%m-%d') <= '$currentDate' 
        AND '$currentDate' <= DATE_FORMAT(maintenance_to, '%Y-%m-%d')
    )
    AND availability = 0
";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];

echo $totalItems;
?>
