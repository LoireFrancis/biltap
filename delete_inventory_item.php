<?php
include "connect.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM inventory WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Item deleted successfully";
    } else {
        echo "Error deleting item: " . $conn->error;
    }
}

$conn->close();
?>
