<?php
include "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='item-detail'>
                <h2>{$row['fullname']}</h2>
                    <div class='details-row'><div class='label'>Position:</div><div class='value'>{$row['position']}</div></div>
                    <div class='details-row'><div class='label'>Email:</div><div class='value'>{$row['email']}</div></div>
                    <div class='details-row'><div class='label'>User Type:</div><div class='value'>{$row['users']}</div></div>";
    } else {
        echo "No details found";
    }
}
?>