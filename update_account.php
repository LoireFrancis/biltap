<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
include "connect.php";

// Check if user ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Prepare and execute query to fetch user details
    $query = "SELECT * FROM user WHERE id = $userId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    } else {
        echo "User not found!";
        exit;
    }
} else {
    echo "No user ID provided!";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $passw = mysqli_real_escape_string($conn, $_POST['passw']);
    $users = mysqli_real_escape_string($conn, $_POST['users']);

    $image_query = "";
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_query = ", image = '$image'";
    }

    // Only include password in the update query if user is an admin
    $passw_query = "";
    if ($users === 'admin') {
        $passw_query = ", passw = '$passw'";
    }

    // Prepare and execute update query
    $update_query = "
        UPDATE user SET 
        fullname = '$fullname', 
        position = '$position', 
        email = '$email'
        $passw_query
        $image_query,
        users = '$users'
        WHERE id = $userId
    ";

    if (mysqli_query($conn, $update_query)) {
        header("Location: account.php?update=success");
        exit;
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
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
    <title>Update Account Details</title>
    <style>
        .form-container {
            max-width: 900px;
            max-height: 650px;
            margin: 0 auto;
            padding: 2rem;
            background-color: var(--color-white);
            border-radius: var(--border-radius-2);
            box-shadow: var(--box-shadow);
        }

        .form-container:hover {
            box-shadow: none;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--color-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border-radius: var(--border-radius-1);
        }

        .form-group img {
            width: 10%;
            height: auto;
            margin: 0 auto 1rem;
            display: block;
            border-radius: var(--border-radius-1);
            border: 1px solid var(--color-dark-variant);
            box-sizing: border-box;
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
            box-sizing: border-box;
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
            background-color: #72b0f1;
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

        input[type="file"] {
            width: 100%;
            padding: 0.65rem;
            font-size: 1rem;
            border: 1px solid var(--color-dark-variant);
            border-radius: var(--border-radius-1);
            box-sizing: border-box;
            background-color: var(--color-white);
            color: var(--color-dark);
            transition: border-color 0.3s ease;
            margin-bottom: 1rem;
        }

        input[type="file"]:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
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
    </style>
</head>

<body>
    <?php include('Temp/aside.php'); ?>
    <main>
        <div class="header-container">
            <h1>Update Account Details</h1>
            <button class="back-button" onclick="location.href='account.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div>
        <div class="form-container">
            <form id="update-form"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($userId); ?>"
                method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <?php if (!empty($item['image'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>"
                            alt="<?php echo htmlspecialchars($item['fullname']); ?>">

                        <label for="image">Image:</label>
                        <input type="file" name="image" id="image">
                    <?php endif; ?>

                    <label for="fullname">Fullname:</label>
                    <input type="text" name="fullname" id="fullname"
                        value="<?php echo htmlspecialchars($item['fullname']); ?>" required>

                    <label for="position">Position:</label>
                    <input type="text" name="position" id="position"
                        value="<?php echo htmlspecialchars($item['position']); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($item['email']); ?>"
                        required>

                    <?php if ($item['users'] === 'admin'): ?>
                        <label for="passw">Password:</label>
                        <input type="password" name="passw" id="passw"
                            value="<?php echo htmlspecialchars($item['passw']); ?>" required>
                    <?php endif; ?>

                    <label for="users">User:</label>
                    <select name="users" id="users" required>
                        <option value="admin" <?php echo ($item['users'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="warehouse" <?php echo ($item['users'] == 'warehouse') ? 'selected' : ''; ?>>Warehouse</option>
                        <option value="client" <?php echo ($item['users'] == 'client') ? 'selected' : ''; ?>>Client
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" id="update-button">Update Account</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="update-confirmation-overlay" class="update-confirmation-overlay">
        <div class="update-confirmation-content">
            <h3>Are you sure you want to update this account?</h3>
            <button class="btn btn-yes" onclick="submitUpdateForm()">Yes</button>
            <button class="btn btn-no" onclick="cancelUpdate()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="update-success-message" class="update-success-message">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Account Updated Successfully</h3>
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

            document.getElementById('update-success-message').style.display = 'block';

            setTimeout(function () {
                document.getElementById('update-form').submit();
            }, 1000);
        }

        function cancelUpdate() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';
        }

        function showSuccessMessage() {
            document.getElementById('update-success-message').style.display = 'block';
            setTimeout(function () {
                document.getElementById('update-success-message').style.display = 'none';
                window.location.href = 'account.php';
            }, 3000);
        }

        window.addEventListener('load', function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('update') && urlParams.get('update') === 'success') {
                showSuccessMessage();
            }
        });
    </script>
</body>

</html>