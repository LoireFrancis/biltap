<?php
include 'connect.php';

$ids = json_decode(file_get_contents('php://input'), true)['ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    echo 'Invalid input';
    exit;
}

$conn->begin_transaction();

foreach ($ids as $id) {
    // Prepare the SQL statements once outside the loop
    $update_request_sql = "UPDATE return_request SET status = '2' WHERE id = ?";
    $stmt_request = $conn->prepare($update_request_sql);

    // Prepare statement for inventory update
    $update_inventory_sql = "UPDATE inventory SET availability = '2' WHERE built_num = ?";
    $stmt_inventory = $conn->prepare($update_inventory_sql);

    // Prepare statement for borrowed table update
    $update_borrowed_sql = "UPDATE borrowed SET status = '1' WHERE id = ?";
    $stmt_borrowed = $conn->prepare($update_borrowed_sql);

    // Prepare statement for inserting notifications
    $insert_notification_sql = "INSERT INTO notifications (project_holder, project_id, name, message, status)
                                VALUES (?, ?, ?, ?, 2)";
    $stmt_notification = $conn->prepare($insert_notification_sql);

    // 1. Update the request status
    $stmt_request->bind_param('i', $id);
    if (!$stmt_request->execute()) {
        $conn->rollback();
        echo 'Error updating request status';
        exit;
    }

    // Fetch details for notifications and inventory update
    $query = "SELECT * FROM return_request WHERE id = ?";
    $stmt_fetch = $conn->prepare($query);
    $stmt_fetch->bind_param('i', $id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();

    if ($row = $result->fetch_assoc()) {
        $built_num = $row['built_num'];
        $project_holder = $row['project_holder'];
        $project_id = $row['project_id'];
        $message = "Return request for project {$row['project_name']} has been maintenance.";

        // 2. Update the inventory table to set availability
        $stmt_inventory->bind_param('s', $built_num);
        if (!$stmt_inventory->execute()) {
            $conn->rollback();
            echo 'Error updating inventory';
            exit;
        }

        // 3. Update into the borrowed table
        $stmt_borrowed->bind_param('i', $id);
        if (!$stmt_borrowed->execute()) {
            $conn->rollback();
            echo 'Error updating borrowed status';
            exit;
        }

        // 4. Insert into the notifications table
        $stmt_notification->bind_param('ssss', $project_holder, $project_id, $row['name'], $message);
        if (!$stmt_notification->execute()) {
            $conn->rollback();
            echo 'Error inserting into notifications table';
            exit;
        }
    } else {
        $conn->rollback();
        echo 'Error fetching return request details';
        exit;
    }

    $stmt_fetch->close();
}

$conn->commit();

$stmt_request->close();
$stmt_inventory->close();
$stmt_borrowed->close();
$stmt_notification->close();

echo 'success';
?>
