<?php
include 'connect.php'; 

$search = $_GET['search'] ?? '';

// Prepare the query to search the user table
$stmt = $conn->prepare("SELECT * FROM user WHERE 
                        fullname LIKE CONCAT('%', ?, '%') OR 
                        position LIKE CONCAT('%', ?, '%') OR 
                        email LIKE CONCAT('%', ?, '%') OR 
                        users LIKE CONCAT('%', ?, '%')");

// Bind parameters (4 strings)
$stmt->bind_param("ssss", $search, $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
            <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['fullname']}'>
            <div class='item-name'>{$row['fullname']}</div>
            <div class='item-name'>{$row['position']}</div>
        </div>";
    }
} else {
    echo "<p>No users found.</p>";
}

$stmt->close();
$conn->close();
?>
