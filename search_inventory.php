<?php
include 'connect.php'; 

$search = $_GET['search'] ?? '';

// Pagination logic
$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Check if the search is related to availability
$availabilityMap = [
    'available' => 0,
    'borrowed' => 1,
    'maintenance' => 2
];
$searchAvailability = array_key_exists(strtolower($search), $availabilityMap) ? $availabilityMap[strtolower($search)] : null;

// Modify query to include pagination
$stmt = $conn->prepare(
    "SELECT * FROM inventory 
     WHERE name LIKE CONCAT('%', ?, '%') 
     OR type LIKE CONCAT('%', ?, '%') 
     OR brand LIKE CONCAT('%', ?, '%') 
     OR built_num LIKE CONCAT('%', ?, '%') 
     OR color LIKE CONCAT('%', ?, '%') 
     OR arrival_date LIKE CONCAT('%', ?, '%') 
     OR (availability = ? AND ? IS NOT NULL)
     LIMIT ?, ?"
);

$stmt->bind_param("ssssssiiii", $search, $search, $search, $search, $search, $search, $searchAvailability, $searchAvailability, $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        switch ($row['availability']) {
            case 0:
                $availability_text = 'Available';
                $availability_class = 'available';
                break;
            case 1:
                $availability_text = 'Borrowed';
                $availability_class = 'borrowed';
                break;
            case 2:
                $availability_text = 'Maintenance';
                $availability_class = 'maintenance';
                break;
            default:
                $availability_text = 'Unknown';
                $availability_class = '';
                break;
        }
        echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                    <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['name']}'>
                    <div class='item-name'>{$row['name']}</div>
                    <div class='item-info'>
                    <div class='item-built-num'>{$row['built_num']}</div>
                    <div class='availability-circle {$availability_class}'></div>
                    </div>
        </div>";
    }
} else {
    echo "<p>No items found.</p>";
}

$stmt->close();
$conn->close();
?>
