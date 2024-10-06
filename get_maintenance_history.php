<?php
include 'connect.php';

$itemId = $_GET['id'];

// Prepare and execute the query to fetch maintenance history for the given item ID
$query = "SELECT maintenance_date, comment FROM maintenance WHERE name = (SELECT name FROM inventory WHERE id = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $itemId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results and encode them as JSON
$maintenanceData = [];
while ($row = $result->fetch_assoc()) {
    $maintenanceData[] = [
        'maintenance_date' => $row['maintenance_date'],
        'comment' => $row['comment']
    ];
}

echo json_encode($maintenanceData);
?>
