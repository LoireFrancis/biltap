<?php
include 'connect.php';

$itemId = $_GET['id'];

// Modify the query to select images based on project_id, built_num, report_date
$query = "SELECT ei.images, ei.built_num, re.project_id, re.report_date
          FROM emergency_image ei
          JOIN return_emergency re 
          ON re.project_id = ei.project_id 
          AND re.built_num = ei.built_num 
          AND re.report_date = ei.report_date 
          WHERE re.id = ? 
          AND re.status = 0";  // Ensure only requests with status = 0 are fetched
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $itemId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results and encode them as JSON
$imageData = [];
while ($row = $result->fetch_assoc()) {
    $imageData[] = [
        'images' => base64_encode($row['images']),
        'project_id' => $row['project_id'],
        'built_num' => $row['built_num'],
        'report_date' => $row['report_date']
    ];
}

echo json_encode($imageData);
?>
