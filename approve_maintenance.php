<?php
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ids']) && is_array($data['ids'])) {
    $ids = implode(',', array_map('intval', $data['ids']));

    $itemsQuery = "SELECT * FROM inventory WHERE id IN ($ids)";
    $itemsResult = $conn->query($itemsQuery);

    if ($itemsResult->num_rows > 0) {
        $maintenanceInsertQuery = "INSERT INTO maintenance (image, type, name, brand, built_num, color, arrival_date, comment, availability) VALUES ";

        $values = [];

        while ($item = $itemsResult->fetch_assoc()) {
            $values[] = "(" .
                "'" . $conn->real_escape_string($item['image']) . "', " .
                "'" . $conn->real_escape_string($item['type']) . "', " .
                "'" . $conn->real_escape_string($item['name']) . "', " .
                "'" . $conn->real_escape_string($item['brand']) . "', " .
                "'" . $conn->real_escape_string($item['built_num']) . "', " .
                "'" . $conn->real_escape_string($item['color']) . "', " .
                "'" . $conn->real_escape_string($item['arrival_date']) . "', " .
                "'Major Maintenance', " .
                "2" .
                ")";
        }


        $maintenanceInsertQuery .= implode(', ', $values);


        // Update only the availability and comment
        $updateQuery = "UPDATE inventory SET 
        availability = 2, 
        comment = CONCAT(comment, ' Major Maintenance'), 
        maintenance_from = CASE 
            WHEN DATEDIFF(NOW(), maintenance_from) >= 1 THEN DATE_ADD(NOW(), INTERVAL 1 MONTH) 
            ELSE maintenance_from 
        END 
    WHERE id IN ($ids)";

        $conn->begin_transaction();

        try {
            if ($conn->query($updateQuery) === TRUE && $conn->query($maintenanceInsertQuery) === TRUE) {
                $conn->commit();
                echo "success";
            } else {
                throw new Exception("Error: " . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
    } else {
        echo "No items found for the given IDs.";
    }
} else {
    echo "Invalid request data.";
}

$conn->close();
?>