<?php
session_start();
include "connect.php";

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    $image_query = "";
    $password_query = "";

    // Check if the image is being updated
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_query = ", image = '$image'";
    }

    // Check if the password is being updated
    if ($password !== null) {
        $password_query = "passw = '$password'"; // No hashing, plain text
    }

    // Prepare the final query (with or without the password update)
    $query = "UPDATE user SET ";
    if ($password_query !== "") {
        $query .= "$password_query";
    }

    if ($image_query !== "") {
        if ($password_query !== "") {
            $query .= "$image_query";
        } else {
            $query .= substr($image_query, 2); // Remove the comma before "image"
        }
    }

    $query .= " WHERE email = '$email'";

    if ($conn->query($query) === TRUE) {
        echo "success";  // Return success to AJAX
    } else {
        echo "Error updating profile: " . $conn->error;
    }
    exit();  // Ensure no further output is sent
}

// Fetch user data
$email = $_SESSION['email'];
$userQuery = $conn->prepare("SELECT * FROM user WHERE email = ?");
$userQuery->bind_param("s", $email);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    $user = null;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/update.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <link rel="stylesheet" href="CSS/notifications.css">
    <title>Profile Management</title>
</head>
<style>
    .inventory-container {
        max-width: 900px;
        margin: 20px auto;
        padding: var(--card-padding);
        background-color: var(--color-white);
        border-radius: var(--card-border-radius);
        box-shadow: var(--box-shadow);
    }

    .profile {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: var(--padding-1);
        border: 2px solid var(--color-primary);
        box-shadow: var(--box-shadow);
    }

    .profile h3 {
        margin: 10px 0;
        color: var(--color-dark);
        text-align: center;
    }

    .profile p {
        margin: 5px 0;
        color: var(--color-dark-variant);
        text-align: center;
    }

    .profile strong {
        color: var(--color-primary);
    }

    .profile p.user-not-found {
        color: var(--color-danger);
        font-weight: 600;
    }

    .password-field {
        position: relative;
        width: 20%;
        margin: 10px 0;
    }

    .password-label {
        text-align: center;
        display: block;
        margin-bottom: 5px;
        color: var(--color-dark);
        font-weight: bold;
    }

    .password-input {
        width: 100%;
        padding: 10px;
        border-radius: var(--border-radius-2);
        border: 1px solid var(--color-dark-variant);
        box-shadow: var(--box-shadow);
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .password-input:focus {
        border-color: var(--color-primary);
        outline: none;
    }

    .toggle-password {
        position: absolute;
        top: 70%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--color-primary);
    }

    .toggle-password:hover {
        color: var(--color-danger);
    }

    .update {
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

    .update:hover {
        background-color: #0056b3;
    }
</style>

<body>
    <?php include('Temp/aside_warehouse.php'); ?>
    <main>
        <div class="header-container">
            <h1>Profile Management</h1>
        </div><br>

        <div class="inventory-container">
            <?php if ($user): ?>
                <form action="warehouse_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="profile">
                        <label for="image" style="cursor: pointer;">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" alt="User Image"
                                class="profile-image">
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" style="display: none;"
                            onchange="previewImage(event)">

                        <h3><?php echo htmlspecialchars($user['fullname']); ?></h3>
                        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <p>Position: <?php echo htmlspecialchars($user['position']); ?></p>

                        <div class="password-field">
                            <label for="password" class="password-label">Change Password:</label>
                            <input type="password" id="password" name="password" class="password-input" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                                <i id="password-icon" class="material-icons-sharp">visibility</i>
                            </span>
                        </div><br>

                        <button type="submit" class="update">Update Account</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="user-not-found">User not found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="update-confirmation-overlay" class="update-confirmation-overlay">
        <div class="update-confirmation-content">
            <h3>Are you sure you want to update your profile?</h3>
            <button class="btn btn-yes" onclick="submitUpdate()">Yes</button>
            <button class="btn btn-no" onclick="cancelUpdate()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="update-success-message" class="update-success-message">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Profile Updated Successfully</h3>
    </div>

    <?php include('Temp/warehouse_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/inventory.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/notifications.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        //image
        function previewImage(event) {
            const image = document.querySelector('.profile-image');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    image.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        //password
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.textContent = "visibility_off";
            } else {
                passwordInput.type = "password";
                passwordIcon.textContent = "visibility";
            }
        }

        // Show confirmation overlay when update button is clicked
        document.querySelector('.update').addEventListener('click', function (e) {
            e.preventDefault(); 
            document.getElementById('update-confirmation-overlay').style.display = 'flex';
        });

        // Function to submit the update form
        function submitUpdate() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';

            var form = document.querySelector('form');
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
                            window.location.href = 'warehouse_profile.php';  
                        }, 2000);
                    } else {
                        console.error('Update failed:', result);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Cancel the update and hide the confirmation overlay
        function cancelUpdate() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';
        }

    </script>

</body>

</html>