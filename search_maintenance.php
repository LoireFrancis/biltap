<?php
include 'connect.php'; 

$search = $_GET['search'] ?? '';

// Pagination logic
$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Modify query to include pagination and availability check
$stmt = $conn->prepare(
    "SELECT * FROM inventory 
     WHERE (name LIKE CONCAT('%', ?, '%') 
     OR type LIKE CONCAT('%', ?, '%') 
     OR brand LIKE CONCAT('%', ?, '%') 
     OR built_num LIKE CONCAT('%', ?, '%') 
     OR color LIKE CONCAT('%', ?, '%') 
     OR arrival_date LIKE CONCAT('%', ?, '%')) 
     AND availability = 2
     LIMIT ?, ?");

$stmt->bind_param("ssssssii", $search, $search, $search, $search, $search, $search, $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                    <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['name']}'>
                    <div class='item-name'>{$row['name']}</div>
                    <div class='item-built-num'>{$row['built_num']}</div>
              </div>";
    }
} else {
    echo "<p>No items found.</p>";
}

$stmt->close();
$conn->close();
?>
