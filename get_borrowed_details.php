<?php
include "connect.php";

// Ensure the 'id' is provided and is an integer to prevent SQL injection
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to integer for security
    $sql = "SELECT * FROM borrowed WHERE id = ?";
    
    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the 'id' parameter to the statement
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any record is found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='item-detail'>
                    <h2>{$row['name']}</h2>
                    <div class='details-row'><div class='label'>ProjectID:</div><div class='value'>{$row['projectid']}</div></div>
                    <div class='details-row'><div class='label'>Project Holder:</div><div class='value'>{$row['project_holder']}</div></div>
                    <div class='details-row'><div class='label'>Position:</div><div class='value'>{$row['position']}</div></div>
                    <div class='details-row'><div class='label'>Project Name:</div><div class='value'>{$row['project_name']}</div></div>
                    <div class='details-row'><div class='label'>Project Location:</div><div class='value'>{$row['project_location']}</div></div>
                    <div class='details-row'><div class='label'>Equip/Machine:</div><div class='value'>{$row['name']}</div></div>
                    <div class='details-row'><div class='label'>Built Number:</div><div class='value'>{$row['built_num']}</div></div>
                    <div class='details-row'><div class='label'>Borrowed Date:</div><div class='value'>{$row['borrowed_date']}</div></div>
                    <div class='details-row'><div class='label'>Return Date:</div><div class='value'>{$row['return_date']}</div></div>
                  </div>";
        } else {
            echo "<p>No details found for the requested item.</p>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // In case of a query preparation error
        echo "<p>Error preparing the statement.</p>";
    }
} else {
    echo "<p>Invalid request. No valid ID provided.</p>";
}
?>
