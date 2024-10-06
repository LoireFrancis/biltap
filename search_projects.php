<?php
include 'connect.php'; 

$search = $_GET['search'] ?? '';

$stmt = $conn->prepare("SELECT * FROM projects WHERE 
                        (projectid LIKE CONCAT('%', ?, '%') OR 
                        project_name LIKE CONCAT('%', ?, '%') OR 
                        project_location LIKE CONCAT('%', ?, '%') OR 
                        description LIKE CONCAT('%', ?, '%') OR 
                        project_holder LIKE CONCAT('%', ?, '%') OR 
                        position LIKE CONCAT('%', ?, '%') OR 
                        date_started LIKE CONCAT('%', ?, '%') OR 
                        date_finish LIKE CONCAT('%', ?, '%'))
                        AND status = 0");

$stmt->bind_param("ssssssss", $search, $search, $search, $search, $search, $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
        <span class='material-icons-sharp'>folder</span>
        <div class='item-name'>{$row['project_name']}</div>
        <div class='item-name'>{$row['project_location']}</div>
        </div>";
    }
} else {
    echo "<p>No items found.</p>";
}

$stmt->close();
$conn->close();
?>
