<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
include "connect.php";

$fullname_error = "";
$email_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $passw = $_POST['password'];
    $users = $_POST['role'];

    $checkFullnameQuery = "SELECT * FROM user WHERE fullname = '$fullname'";
    $checkFullnameResult = mysqli_query($conn, $checkFullnameQuery);

    $checkEmailQuery = "SELECT * FROM user WHERE email = '$email'";
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($checkFullnameResult) > 0) {
        $fullname_error = "*Fullname already exists.";
    }

    if (mysqli_num_rows($checkEmailResult) > 0) {
        $email_error = "*Email already exists.";
    }

    if (empty($fullname_error) && empty($email_error)) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

        $query = "INSERT INTO user (fullname, position, email, passw, users, image) 
                  VALUES ('$fullname', '$position', '$email', '$passw', '$users', '$image')";

        if (mysqli_query($conn, $query)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        } else {
            $fullname_error = "Error: " . mysqli_error($conn);
            $email_error = "Error: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
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
    <link rel="stylesheet" href="CSS/add.css">
    <title>Inventory</title>
</head>
<style>
    /* Form */
    form {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
        background-color: var(--color-white);
        border-radius: var(--card-border-radius);
        box-shadow: var(--box-shadow);
    }

    label {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: var(--color-dark);
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

    input[type="text"]:focus,
    input[type="password"]:focus,
    select:focus {
        outline: none;
        border-color: var(--color-primary);
    }

    select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'%3E%3C/path%3E%3C/svg%3E");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 3rem;
        cursor: pointer;
    }

    button[type="submit"] {
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

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    body.dark-mode {
        color: var(--color-white);
        background-color: var(--color-background);
    }

    body.dark-mode input[type="text"],
    body.dark-mode input[type="password"],
    body.dark-mode select {
        color: var(--color-input-text);
        background-color: var(--color-dark);
        border-color: var(--color-dark-variant);
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

    .success-message {
        color: green;
        font-weight: bold;
        margin: 1rem 0;
    }

    .inventory-container {
        max-width: 900px;
        margin: 0 auto;
        background-color: var(--color-white);
        border-radius: var(--border-radius-2);
        box-shadow: var(--box-shadow);
    }

    .inventory-container:hover {
        box-shadow: none;
    }
</style>

<body>
    <?php include('Temp/aside.php'); ?>

    <main>
        <div class="header-container">
            <h1>Add New Account</h1>
            <button class="back-button" onclick="location.href='account.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div><br>
        <div class="inventory-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data" onsubmit="return confirmAddAccount();">
                <!-- Image Field -->
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required><br><br>

                <!-- Full Name Field -->
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>
                <div id="fullname-error-message"
                    style="color: yellow; display: <?php echo !empty($fullname_error) ? 'block' : 'none'; ?>;">
                    <?php echo $fullname_error; ?>
                </div><br>

                <!-- Position Field -->
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" required><br><br>

                <!-- Email Field -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <div id="email-error-message"
                    style="color: yellow; display: <?php echo !empty($email_error) ? 'block' : 'none'; ?>;">
                    <?php echo $email_error; ?>
                </div><br>

                <!-- Password Field -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <!-- User Role Field -->
                <label for="role">Type of user:</label>
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="warehouse">Warehouse</option>
                    <option value="borrower">Borrower</option>
                </select><br><br>

                <!-- Submit Button -->
                <button type="submit">Add Account</button>
            </form>

        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="add-confirmation-overlay" class="add-confirmation-overlay">
        <div class="add-confirmation-content">
            <h3>Are you sure to add this account?</h3>
            <button class="btn btn-yes" onclick="submitAddAccount()">Yes</button>
            <button class="btn btn-no" onclick="cancelAddAccount()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="add-success-message" class="add-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Account Added Successfully</h3>
    </div>


    <?php include('Temp/admin_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/inventory.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>

        //add account
        function confirmAddAccount() {
            document.getElementById('add-confirmation-overlay').style.display = 'flex';
            return false;
        }

        function cancelAddAccount() {
            document.getElementById('add-confirmation-overlay').style.display = 'none';
        }

        function submitAddAccount() {
            document.getElementById('add-confirmation-overlay').style.display = 'none';

            document.querySelector('form').submit();
        }

        // Check for success 
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                document.getElementById('add-success-message').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('add-success-message').style.display = 'none';
                }, 1000);
            }

            const fullnameErrorMessage = document.getElementById('fullname-error-message');

            if (fullnameErrorMessage.textContent.trim() !== '') {
                setTimeout(function () {
                    fullnameErrorMessage.style.display = 'none';
                }, 3000);
            }

            const emailErrorMessage = document.getElementById('email-error-message');

            if (emailErrorMessage.textContent.trim() !== '') {
                setTimeout(function () {
                    emailErrorMessage.style.display = 'none';
                }, 3000);
            }
        }
    </script>

</body>

</html>