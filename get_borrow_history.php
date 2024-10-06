<?php
include 'connect.php';

$itemId = $_GET['id'];

$statusMapping = [
    0 => 'Borrowed',
    1 => 'Returned'
];

$query = "SELECT b.projectid, b.project_holder, b.project_name, b.name, b.built_num, b.borrowed_date, b.return_date, b.status 
          FROM borrowed b 
          JOIN projects p ON b.projectid = p.projectid 
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $itemId);
$stmt->execute();
$result = $stmt->get_result();

$borrowData = [];
while ($row = $result->fetch_assoc()) {
    $borrowData[] = [
        'projectid' => $row['projectid'],
        'project_holder' => $row['project_holder'],
        'project_name' => $row['project_name'],
        'name' => $row['name'],
        'built_num' => $row['built_num'],
        'borrowed_date' => $row['borrowed_date'],
        'return_date' => $row['return_date'],
        'status' => $statusMapping[$row['status']] ?? 'Unknown'
    ];
}

echo json_encode($borrowData);
?>
