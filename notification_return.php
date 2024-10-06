<?php
header('Content-Type: text/plain');
include 'connect.php';

$sql = "SELECT COUNT(*) AS count FROM return_request WHERE status = '0'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo 'Error: ' . $conn->error;
}

$conn->close();
?>
