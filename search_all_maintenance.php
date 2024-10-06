<?php
include 'connect.php'; 

$search = $_GET['search'] ?? '';


$stmt = $conn->prepare("SELECT * FROM maintenance WHERE 
                        name LIKE CONCAT('%', ?, '%') OR 
                        type LIKE CONCAT('%', ?, '%') OR 
                        brand LIKE CONCAT('%', ?, '%') OR 
                        built_num LIKE CONCAT('%', ?, '%') OR 
                        color LIKE CONCAT('%', ?, '%') OR 
                        arrival_date LIKE CONCAT('%', ?, '%') OR
                        maintenance_date LIKE CONCAT('%', ?, '%') OR 
                        availability = (CASE 
                                           WHEN ? = 'available' THEN 0 
                                           WHEN ? = 'borrowed' THEN 1 
                                           WHEN ? = 'maintenance' THEN 2 
                                      END)");


$stmt->bind_param("ssssssssss", $search, $search, $search, $search, $search, $search, $search, $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
            <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['name']}'>
            <div class='item-name'>{$row['name']}</div>
        </div>";
    }
} else {
    echo "<p>No items found.</p>";
}

$stmt->close();
$conn->close();
