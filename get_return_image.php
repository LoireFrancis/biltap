<?php
include 'connect.php';

$itemId = $_GET['id'];

// Modify the query to select images based on project_id, built_num, request_date
$query = "SELECT ri.images, ri.built_num, rr.project_id, rr.request_date
          FROM return_image ri
          JOIN return_request rr 
          ON rr.project_id = ri.project_id 
          AND rr.built_num = ri.built_num 
          AND rr.request_date = ri.request_date 
          WHERE rr.id = ? 
          AND rr.status = 0";  // Ensure only requests with status = 0 are fetched
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
        'request_date' => $row['request_date']
    ];
}

echo json_encode($imageData);
?>
