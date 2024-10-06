<?php
include "connect.php";

$currentDate = date('Y-m-d');
$currentDay = date('d');

$totalItemsQuery = "
    SELECT COUNT(*) as total
    FROM inventory
    WHERE DAY(maintenance_from) = '$currentDay'
    AND maintenance_from <= '$currentDate'
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
