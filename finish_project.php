<?php
include "connect.php";

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

if (isset($_GET['id'])) {
    $projectId = intval($_GET['id']);

    $sql = "UPDATE projects SET status = 'Finished' WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $projectId);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Project status updated to Finish';
        } else {
            $response['message'] = 'Failed to update project status';
        }

        $stmt->close();
    } else {
        $response['message'] = 'Failed to prepare SQL statement';
    }
} else {
    $response['message'] = 'No project ID provided';
}

$conn->close();

echo json_encode($response);
?>
