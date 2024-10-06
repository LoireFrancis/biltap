<?php
include 'connect.php';

$ids = json_decode(file_get_contents('php://input'), true)['ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    echo 'Invalid input';
    exit;
}

$conn->begin_transaction();

foreach ($ids as $id) {
    // 1. Update the request status
    $sql = "UPDATE request SET status = '2' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if (!$stmt->execute()) {
        $conn->rollback();
        echo 'error updating request';
        exit;
    }

    $query = "SELECT * FROM request WHERE id = ?";
    $stmt2 = $conn->prepare($query);
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row = $result->fetch_assoc();
    $built_num = $row['built_num'];
    $project_holder = $row['project_holder'];
    $project_id = $row['projectid'];
    $message = "Request {$built_num} for project {$row['project_name']} has been approved.";

    // 2. Update the inventory table to set availability
    $update_inventory = "UPDATE inventory SET availability = '1' WHERE built_num = ?";
    $stmt3 = $conn->prepare($update_inventory);
    $stmt3->bind_param('s', $built_num);

    if (!$stmt3->execute()) {
        $conn->rollback();
        echo 'error updating inventory';
        exit;
    }

    // 3. Insert into the borrowed table
    $insert_borrowed = "INSERT INTO borrowed (image, projectid, project_holder, position, project_name, project_location, name, built_num, borrowed_date, return_date, status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt4 = $conn->prepare($insert_borrowed);
    $stmt4->bind_param(
        'ssssssssss',
        $row['image'],
        $row['projectid'],
        $row['project_holder'],
        $row['position'],
        $row['project_name'],
        $row['project_location'],
        $row['name'],
        $row['built_num'],
        $row['borrowed_date'],
        $row['return_date']
    );

    if (!$stmt4->execute()) {
        $conn->rollback();
        echo 'error inserting into borrowed table';
        exit;
    }

    // 4. Insert into the notifications table
    $insert_notification = "INSERT INTO notifications (project_holder, project_id, name, message, status)
            VALUES (?, ?, ?, ?, 2)";
    $stmt5 = $conn->prepare($insert_notification);
    $stmt5->bind_param('ssss', $project_holder, $project_id, $row['name'], $message);

    if (!$stmt5->execute()) {
        $conn->rollback();
        echo 'error inserting into notifications table';
        exit;
    }
}

$conn->commit();

$stmt->close();
$stmt2->close();
$stmt3->close();
$stmt4->close();
$stmt5->close();

echo 'success';
?>