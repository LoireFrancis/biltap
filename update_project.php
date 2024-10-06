<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
include "connect.php";

if (isset($_GET['id'])) {
    $projectId = intval($_GET['id']);

    $query = "SELECT * FROM projects WHERE id = $projectId";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    } else {
        echo "Project not found!";
        exit;
    }
} else {
    echo "No project ID provided!";
    exit;
}

$incharge_query = "SELECT fullname, position FROM user WHERE users = 'client'";
$incharge_result = mysqli_query($conn, $incharge_query);

$incharge_options = "";
if (mysqli_num_rows($incharge_result) > 0) {
    while ($row = mysqli_fetch_assoc($incharge_result)) {
        $fullname = htmlspecialchars($row['fullname']);
        $position = htmlspecialchars($row['position']);
        $selected = ($fullname === $item['project_holder']) ? 'selected' : '';
        $incharge_options .= "<option value='" . $fullname . "' data-position='" . $position . "' $selected>" . $fullname . "</option>";
    }
} else {
    $incharge_options = "<option value=''>No clients found</option>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $project_location = mysqli_real_escape_string($conn, $_POST['project_location']);
    $project_holder = mysqli_real_escape_string($conn, $_POST['project_holder']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $date_started = mysqli_real_escape_string($conn, $_POST['date_started']);
    $date_finish = mysqli_real_escape_string($conn, $_POST['date_finish']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update_query = "
        UPDATE projects SET 
        project_name = '$project_name',
        project_location = '$project_location',
        project_holder = '$project_holder', 
        position = '$position', 
        date_started = '$date_started',
        date_finish = '$date_finish',
        status = '$status'
        WHERE id = $projectId
    ";

    if (mysqli_query($conn, $update_query)) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <link rel="stylesheet" href="CSS/update.css">
    <title>Update Project Details</title>
    <style>
        .form-container {
            max-width: 900px;
            max-height: 650px;
            margin: 0 auto;
            padding: 2rem;
            background-color: var(--color-white);
            border-radius: var(--border-radius-2);
            box-shadow: var(--box-shadow);
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
        }

        .form-column {
            flex: 1;
        }

        .form-column label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-column input,
        .form-column select {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: var(--border-radius-1);
            border: 1px solid var(--color-dark-variant);
        }

        .form-group button {
            background-color: var(--color-primary);
            color: var(--color-white);
            border: none;
            padding: 1rem;
            cursor: pointer;
            font-size: 1rem;
            border-radius: var(--border-radius-1);
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .form-group button:hover {
            background-color: #357dbf;
        }

        .back-button {
            background-color: #72b0f1;
            color: var(--text-color);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-1);
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #5a9bcf;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .header-container h1 {
            margin: 0;
        }

        .header-container .back-button {
            display: flex;
            align-items: center;
        }

        input[type="text"],
        input[type="date"],
        input[type="textarea"],
        select {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid var(--color-dark-variant);
            border-radius: var(--border-radius-1);
            box-sizing: border-box;
            margin-bottom: 1rem;
            transition: border-color 0.3s ease;
            background-color: var(--color-white);
            color: var(--color-dark);
        }

        textarea {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid var(--color-dark-variant);
            border-radius: var(--border-radius-1);
            box-sizing: border-box;
            margin-bottom: 1rem;
            background-color: var(--color-white);
            color: var(--color-dark);
            resize: vertical;
        }
    </style>
</head>

<body>
    <?php include('Temp/aside.php'); ?>
    <main>
        <div class="header-container">
            <h1>Update Project Details</h1>
            <button class="back-button" onclick="location.href='projects.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div>
        <div class="form-container">
            <form id="update-form"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($projectId); ?>"
                method="POST">
                <div class="form-group">
                    <!-- Left Column: Project details -->
                    <div class="form-column">
                        <label for="projectid">Project ID:</label>
                        <input type="text" name="projectid" id="projectid"
                            value="<?php echo htmlspecialchars($item['projectid']); ?>" readonly>

                        <label for="project_name">Project Name:</label>
                        <input type="text" name="project_name" id="project_name"
                            value="<?php echo htmlspecialchars($item['project_name']); ?>" required>

                        <label for="project_location">Location:</label>
                        <input type="text" name="project_location" id="project_location"
                            value="<?php echo htmlspecialchars($item['project_location']); ?>" required>

                        <label for="description">Description:</label>
                        <textarea name="description" id="description" rows="4"
                            required><?php echo htmlspecialchars($item['description']); ?></textarea>
                    </div>

                    <!-- Right Column: Other details -->
                    <div class="form-column">
                        <label for="project_holder">Incharge:</label>
                        <select id="project_holder" name="project_holder" required onchange="updatePosition()">
                            <option value="" disabled>Select a person</option>
                            <?php echo $incharge_options; ?>
                        </select>

                        <label for="position">Position:</label>
                        <input type="text" name="position" id="position"
                            value="<?php echo htmlspecialchars($item['position']); ?>" required>

                        <label for="date_started">Date Started:</label>
                        <input type="date" name="date_started" id="date_started"
                            value="<?php echo htmlspecialchars($item['date_started']); ?>" required>

                        <label for="date_finish">Date Finish:</label>
                        <input type="date" name="date_finish" id="date_finish"
                            value="<?php echo htmlspecialchars($item['date_finish']); ?>" required>

                        <label for="status">Status:</label>
                        <select name="status" id="status" required>
                            <option value="Not Started" <?php echo ($item['status'] == 'Not Started') ? 'selected' : ''; ?>>Not Started</option>
                            <option value="Ongoing" <?php echo ($item['status'] == 'Ongoing') ? 'selected' : ''; ?>>
                                Ongoing</option>
                            <option value="Finished" <?php echo ($item['status'] == 'Finished') ? 'selected' : ''; ?>>
                                Finished</option></select>
                        </select>
                    </div>
                </div><br>

                <div class="form-group">
                    <button type="button" id="update-button">Update Project</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="update-confirmation-overlay" class="update-confirmation-overlay">
        <div class="update-confirmation-content">
            <h3>Are you sure you want to update this project?</h3>
            <button class="btn btn-yes" onclick="submitUpdateForm()">Yes</button>
            <button class="btn btn-no" onclick="cancelUpdate()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="update-success-message" class="update-success-message">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Project Updated Successfully</h3>
    </div>

    <?php include('Temp/admin_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/inventory.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        //update
        document.getElementById('update-button').addEventListener('click', function () {
            document.getElementById('update-confirmation-overlay').style.display = 'flex';
        });

        function submitUpdateForm() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';
            document.getElementById('update-form').submit();
        }

        function cancelUpdate() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';
        }

        function submitUpdateForm() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';

            var form = document.getElementById('update-form');
            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    if (result.trim() === 'success') {
                        document.getElementById('update-success-message').style.display = 'block';
                        setTimeout(function () {
                            document.getElementById('update-success-message').style.display = 'none';
                            window.location.href = 'projects.php';
                        }, 3000);
                    } else {
                        console.error('Update failed:', result);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>