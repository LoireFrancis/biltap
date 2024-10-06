<?php
include "connect.php";

// Check if the 'id' parameter is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    // If 'id' parameter is missing, return an error message
    echo "ID parameter is missing.";
}

$conn->close();
?>
