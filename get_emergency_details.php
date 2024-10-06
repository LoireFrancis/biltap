<?php
include "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM return_emergency WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='item-detail'>
                <h2>{$row['name']}</h2>
                    <div class='details-row'><div class='label'>ProjectID:</div><div class='value'>{$row['project_id']}</div></div>
                    <div class='details-row'><div class='label'>Project Holder:</div><div class='value'>{$row['project_holder']}</div></div>
                    <div class='details-row'><div class='label'>Position:</div><div class='value'>{$row['position']}</div></div>
                    <div class='details-row'><div class='label'>Project name:</div><div class='value'>{$row['project_name']}</div></div>
                    <div class='details-row'><div class='label'>Project Location:</div><div class='value'>{$row['project_location']}</div></div>      
                    <div class='details-row'><div class='label'>Equip/Machine:</div><div class='value'>{$row['name']}</div></div>
                    <div class='details-row'><div class='label'>Built Number:</div><div class='value'>{$row['built_num']}</div></div>
                    <div class='details-row'><div class='label'>Borrowed Date:</div><div class='value'>{$row['borrowed_date']}</div></div>
                    <div class='details-row'><div class='label'>Return Date:</div><div class='value'>{$row['return_date']}</div></div>
              </div>";
    } else {
        echo "No details found";
    }
}
