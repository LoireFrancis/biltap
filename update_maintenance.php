<?php
include 'connect.php';

// Get the list of IDs from the request payload
$ids = json_decode(file_get_contents('php://input'), true)['ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    echo 'Invalid input';
    exit;
}

$conn->begin_transaction();

foreach ($ids as $id) {
    // Prepare SQL for the different updates and insertions
    $update_inventory_sql = "UPDATE inventory SET availability = '0' WHERE built_num = ?";
    $update_borrowed_sql = "UPDATE borrowed SET status = '0' WHERE built_num = ?";
    $update_return_emergency_sql = "UPDATE return_emergency SET status = '2' WHERE id = ?";
    $update_maintenance_sql = "UPDATE maintenance SET emergency_report = '0' WHERE built_num = ?";
    $insert_notification_sql = "INSERT INTO notifications (project_holder, project_id, name, message, status)
                                VALUES (?, ?, ?, ?, 2)";

    // Prepare statements
    $stmt_inventory = $conn->prepare($update_inventory_sql);
    $stmt_borrowed = $conn->prepare($update_borrowed_sql);
    $stmt_return_emergency = $conn->prepare($update_return_emergency_sql);
    $stmt_maintenance = $conn->prepare($update_maintenance_sql);
    $stmt_notification = $conn->prepare($insert_notification_sql);

    // Fetch details from return_emergency table for notification and updates
    $query = "SELECT * FROM return_emergency WHERE id = ?";
    $stmt_fetch = $conn->prepare($query);
    $stmt_fetch->bind_param('i', $id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();

    if ($row = $result->fetch_assoc()) {
        $built_num = $row['built_num'];
        $project_holder = $row['project_holder'];
        $project_id = $row['project_id'];
        $name = $row['name'];
        $message = "Request {$row['name']} for {$row['project_id']} maintenance is finished.";

        // 1. Update inventory availability
        $stmt_inventory->bind_param('s', $built_num);
        if (!$stmt_inventory->execute()) {
            $conn->rollback();
            echo 'Error updating inventory availability';
            exit;
        }

        // 2. Update borrowed status
        $stmt_borrowed->bind_param('s', $built_num);
        if (!$stmt_borrowed->execute()) {
            $conn->rollback();
            echo 'Error updating borrowed status';
            exit;
        }

        // 3. Update return_emergency status
        $stmt_return_emergency->bind_param('i', $id);
        if (!$stmt_return_emergency->execute()) {
            $conn->rollback();
            echo 'Error updating return_emergency status';
            exit;
        }

        // 4. Update maintenance emergency_report
        $stmt_maintenance->bind_param('s', $built_num);
        if (!$stmt_maintenance->execute()) {
            $conn->rollback();
            echo 'Error updating maintenance emergency_report';
            exit;
        }

        // 5. Insert into notifications
        $stmt_notification->bind_param('ssss', $project_holder, $project_id, $name, $message);
        if (!$stmt_notification->execute()) {
            $conn->rollback();
            echo 'Error inserting into notifications';
            exit;
        }
    } else {
        $conn->rollback();
        echo 'Error fetching return_emergency details';
        exit;
    }

    $stmt_fetch->close();
}

$conn->commit();

// Close all statements
$stmt_inventory->close();
$stmt_borrowed->close();
$stmt_return_emergency->close();
$stmt_maintenance->close();
$stmt_notification->close();

echo 'success';
?>
