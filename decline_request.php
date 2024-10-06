<?php
include 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['ids']) && is_array($data['ids'])) {
    $ids = $data['ids'];

    $conn->begin_transaction();

    try {
        $sqlUpdate = "UPDATE request SET status = '1' WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);

        $sqlInsert = "INSERT INTO notifications (project_holder, project_id, name, message, status) VALUES (?, ?, ?, ?, 1)";
        $stmtInsert = $conn->prepare($sqlInsert);

        foreach ($ids as $id) {
            $sqlFetch = "SELECT project_holder, projectid, name, project_name FROM request WHERE id = ?";
            $stmtFetch = $conn->prepare($sqlFetch);
            $stmtFetch->bind_param('i', $id);
            $stmtFetch->execute();
            $result = $stmtFetch->get_result();
            $row = $result->fetch_assoc();

            if (!$row) {
                throw new Exception("Request ID $id not found.");
            }

            $stmtUpdate->bind_param('i', $id);
            $stmtUpdate->execute();

            $message = "The request {$row['name']} for project {$row['project_name']} has been rejected.";

            $stmtInsert->bind_param('ssss', $row['project_holder'], $row['projectid'], $row['name'], $message);
            $stmtInsert->execute();
        }

        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollback();
        echo 'error: ' . $e->getMessage();
    }

    $stmtUpdate->close();
    $stmtInsert->close();
    $conn->close();
} else {
    echo 'error: No IDs received';
}
?>
