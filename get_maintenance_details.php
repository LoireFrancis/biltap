<?php
include "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM maintenance WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='item-detail'>
                <h2>{$row['name']}</h2>
                    <div class='details-row'><div class='label'>Type:</div><div class='value'>{$row['type']}</div></div>
                    <div class='details-row'><div class='label'>Name:</div><div class='value'>{$row['name']}</div></div>
                    <div class='details-row'><div class='label'>Brand:</div><div class='value'>{$row['brand']}</div></div>
                    <div class='details-row'><div class='label'>Built Number:</div><div class='value'>{$row['built_num']}</div></div>
                    <div class='details-row'><div class='label'>Color:</div><div class='value'>{$row['color']}</div></div>
                    <div class='details-row'><div class='label'>Arrival Date:</div><div class='value'>{$row['arrival_date']}</div></div>
                    <div class='details-row'><div class='label'>Maintenance Date:</div><div class='value'>{$row['maintenance_date']}</div></div>
                    <div class='details-row'><div class='label'>Note:</div><div class='value'>{$row['comment']}</div></div>
                    <div class='details-row'><div class='label'>Availability:</div><div class='value'>{$row['availability']}</div></div>
              </div>";
    } else {
        echo "No details found";
    }
}
?>