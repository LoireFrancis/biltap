<?php
include "connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM projects WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<div class='item-detail'>
                <h2>{$row['project_name']}</h2>
                    <div class='details-row'><div class='label'>Project ID:</div><div class='value'>{$row['projectid']}</div></div>
                    <div class='details-row'><div class='label'>Project Name:</div><div class='value'>{$row['project_name']}</div></div>
                    <div class='details-row'><div class='label'>Location:</div><div class='value'>{$row['project_location']}</div></div>
                    <div class='details-row'>
                        <div class='label'>Description:</div>
                        <textarea class='value description-textarea' readonly>{$row['description']}</textarea>
                    </div>
                    <div class='details-row'><div class='label'>Project Holder:</div><div class='value'>{$row['project_holder']}</div></div>
                    <div class='details-row'><div class='label'>Date Started:</div><div class='value'>{$row['date_started']}</div></div>
                    <div class='details-row'><div class='label'>Date Finish:</div><div class='value'>{$row['date_finish']}</div></div>
                    <div class='details-row'><div class='label'>Status:</div><div class='value'>{$row['status']}</div></div>
              </div>";
    } else {
        echo "No details found";
    }
}
?>
