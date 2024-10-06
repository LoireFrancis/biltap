<?php
include "connect.php";

$error_message = "";

// Handle form submission to add new item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $built_num = $_POST['built_num'];
    $color = $_POST['color'];
    $comment = $_POST['comment'];
    $maintenance_from = $_POST['maintenance_from'];
    $maintenance_to = $_POST['maintenance_to'];
    $qrcode = $_POST['qrcode'];

    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    $qrcode = addslashes(base64_decode($qrcode));

    // Check if built_num already exists
    $checkSql = "SELECT * FROM inventory WHERE built_num = '$built_num'";
    $result = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($result) > 0) {
        // Built number already exists
        $error_message = "*The built number already exists.";
    } else {
        // Insert new item
        $sql = "INSERT INTO inventory (image, type, name, brand, built_num, color, qrcode, comment, maintenance_from, maintenance_to, availability)
                VALUES ('$image', '$type', '$name', '$brand', '$built_num', '$color', '$qrcode', '$comment', '$maintenance_from', '$maintenance_to', 0)";

        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
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
    <link rel="stylesheet" href="CSS/add.css">
    <link rel="stylesheet" href="CSS/notifications.css">
    <title>Add Item</title>
    <style>
        /* Form styling */
        form {
            max-width: 900px;
            margin: 0 auto;
            padding: 1rem;
            background-color: var(--color-white);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
        }

        label {
            font-weight: bold;
            margin-bottom: 1rem;
            color: var(--color-dark);
        }

        input[type="text"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 0.71rem;
            font-size: 1rem;
            border: 1px solid var(--color-dark-variant);
            border-radius: var(--border-radius-1);
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            background-color: var(--color-white);
            color: var(--color-dark);
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        input[type="date"] {
            width: 30%;
            padding: 0.71rem;
            margin-right: 2rem;
            font-size: 1rem;
            border: 1px solid var(--color-dark-variant);
            border-radius: var(--border-radius-1);
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            background-color: var(--color-white);
            color: var(--color-dark);
        }

        input[type="date"]:focus {
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
        }

        input[type="file"]:focus {
            outline: none;
            border-color: var(--color-primary);
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
        body.dark-mode input[type="date"],
        body.dark-mode select,
        body.dark-mode textarea,
        body.dark-mode input[type="file"] {
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

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: var(--color-white);
            border-radius: var(--border-radius-2);
            box-shadow: var(--box-shadow);
        }

        .form-container:hover {
            box-shadow: none;
        }

        .generate-qr-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 1rem;
            cursor: pointer;
            font-size: 1rem;
            border-radius: var(--border-radius-1);
            transition: background-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 1rem;
        }

        .generate-qr-button:hover {
            background-color: #45a049;
        }

        .qr-container {
            text-align: center;
            margin-top: 1rem;
        }

        .qr-code {
            display: inline-block;
            margin-top: 1rem;
        }

        .qr-img {
            width: 130px;
            height: 130px;
        }

        /* Form container for two-column layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-row label {
            grid-column: span 1;
        }

        .form-row input,
        .form-row select,
        .form-row textarea {
            grid-column: span 1;
        }

        .form-row input[type="file"] {
            grid-column: span 2;
        }

        .form-row.full-width {
            grid-column: span 2;
        }
    </style>
</head>

<body>
    <?php include('Temp/aside_warehouse.php'); ?>

    <main>

        <div class="header-container">
            <h1>Add New Inventory Item</h1><br>
            <button class="back-button" onclick="location.href='inventory.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div><br>
        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data" onsubmit="return confirmAddItem();">
                <div class="form-row">
                    <div>
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image">
                    </div>
                    <div>
                        <label for="type">Type:</label>
                        <select id="type" name="type" required>
                            <option value="Equipment">Equipment</option>
                            <option value="Machinery">Machinery</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div>
                        <label for="brand">Brand:</label>
                        <input type="text" id="brand" name="brand" required>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="built_num">Built Number:</label>
                        <input type="text" id="built_num" name="built_num" required>
                        <div class="error-message" style="color: yellow; " id="error-message">
                            <?php if (!empty($error_message))
                                echo $error_message; ?>
                        </div>
                    </div>
                    <div>
                        <label for="color">Color:</label>
                        <input type="text" id="color" name="color" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="full-width">
                        <label for="comment">Comment:</label>
                        <textarea id="comment" name="comment"></textarea>
                    </div>
                    <div>
                        <label for="set">Maintenance:</label><br>
                        <label for="maintenance_from">From:</label>
                        <input type="date" id="maintenance_from" name="maintenance_from"></input>
                        <label for="maintenance_to">To:</label>
                        <input type="date" id="maintenance_to" name="maintenance_to"></input>
                    </div>
                </div>

                <div id="qr-container" class="qr-container" style="display: none;">
                    <div id="qr-code" class="qr-code"></div><br>
                    <a id="download-link" href="#" download="qrcode.png" style="display: none; color:red;">Download QR
                        Code</a>
                    <input type="hidden" id="qrcode" name="qrcode">
                </div><br>

                <button type="button" class="generate-qr-button" onclick="generateQR()">Generate QR</button><br>
                <button type="submit">Add Item</button>
            </form>
        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="add-confirmation-overlay" class="add-confirmation-overlay">
        <div class="add-confirmation-content">
            <h3>Are you sure to add this item?</h3>
            <button class="btn btn-yes" onclick="submitAddItem()">Yes</button>
            <button class="btn btn-no" onclick="cancelAddItem()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="add-success-message" class="add-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Item Added Successfully</h3>
    </div>

    <?php include('Temp/warehouse_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/inventory.js"></script>
    <script src="JS/notifications.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        function confirmAddItem() {
            document.getElementById('add-confirmation-overlay').style.display = 'flex';
            return false;
        }

        function cancelAddItem() {
            document.getElementById('add-confirmation-overlay').style.display = 'none';
        }

        function submitAddItem() {
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

            const errorMessage = document.getElementById('error-message');
            if (errorMessage.textContent.trim() !== '') {
                setTimeout(function () {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        }

        function generateQR() {
            // Get form values
            var type = document.getElementById('type').value;
            var name = document.getElementById('name').value;
            var brand = document.getElementById('brand').value;
            var built_num = document.getElementById('built_num').value;
            var color = document.getElementById('color').value;

            // Check if all required fields are filled
            if (type && name && brand && built_num && color) {

                var qrContent = `Type: ${type}\nName: ${name}\nBrand: ${brand}\nBuilt Number: ${built_num}\nColor: ${color}`;

                // Generate QR code
                QRCode.toDataURL(qrContent, { width: 130, height: 130 }, function (err, url) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    document.getElementById('qr-code').innerHTML = `<img src="${url}" alt="QR Code" class="qr-img">`;
                    document.getElementById('qr-container').style.display = 'block';

                    // Set up download link
                    var downloadLink = document.getElementById('download-link');
                    downloadLink.href = url;
                    downloadLink.download = `QR_Code_${built_num}.png`;
                    downloadLink.style.display = 'inline';


                    var qrcodeInput = document.getElementById('qrcode');
                    qrcodeInput.value = url.split(',')[1];
                });
            } else {
                alert("Please fill in all required fields to generate the QR code.");
            }
        }

        setTimeout(function () {
            var message = document.getElementById('success-message');
            if (message) {
                message.style.display = 'none';
            }
        }, 1000);
    </script>
</body>

</html>