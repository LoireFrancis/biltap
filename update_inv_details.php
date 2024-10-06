<?php
include "connect.php";

if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    $query = "SELECT * FROM inventory WHERE id = $itemId";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    } else {
        echo "Item not found!";
        exit;
    }
} else {
    echo "No item ID provided!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $built_num = $_POST['built_num'];
    $color = $_POST['color'];
    $arrival_date = $_POST['arrival_date'];
    $comment = $_POST['comment'];

    $current_availability = $item['availability'];

    if ($current_availability == 0) {
        $availability = $_POST['availability'];
    } else {
        $availability = $current_availability;
    }

    $image_query = "";
    if (!empty($_FILES['image']['tmp_name'])) {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $image_query = ", image = '$image'";
    } else {
        $image = addslashes($item['image']);
    }

    $update_query = "
        UPDATE inventory SET 
        type = '$type', 
        name = '$name', 
        brand = '$brand', 
        built_num = '$built_num', 
        color = '$color', 
        comment = '$comment',
        arrival_date = '$arrival_date',
        availability = '$availability'
        $image_query
        WHERE id = $itemId
    ";

    if (mysqli_query($conn, $update_query)) {
        if ($availability == 2) {
            $maintenance_date = date('Y-m-d H:i:s');
            $insert_maintenance_query = "
                INSERT INTO maintenance (image, type, name, brand, built_num, color, comment, arrival_date, maintenance_date, availability) 
                VALUES ('$image', '$type', '$name', '$brand', '$built_num', '$color', '$comment', '$arrival_date', '$maintenance_date', 'Maintenance')
            ";

            mysqli_query($conn, $insert_maintenance_query);
        }

        // Redirect with success parameter
        header("Location: update_inv_details.php?id=$itemId&update=success");
        exit;
    } else {
        echo "Error updating item: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="CSS/notifications.css">
    <link rel="stylesheet" href="CSS/update.css">
    <title>Update Inventory Details</title>
</head>
<style>
    form {
        max-width: 900px;
        max-height: 800px;
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
        margin-bottom: 1rem;
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

    .form-group img {
        width: 10%;
        height: auto;
        margin: 0 auto 1rem;
        display: block;
        border-radius: var(--border-radius-1);
        border: 1px solid var(--color-dark-variant);
        box-sizing: border-box;
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

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .form-row .form-group {
        flex: 1 1 calc(50% - 1rem);
        box-sizing: border-box;
    }

    .form-row .form-group.col {
        margin-bottom: 1rem;
    }
</style>

<body>
    <?php include('Temp/aside_warehouse.php'); ?>
    <main>
        <div class="header-container">
            <h1>Update Inventory Item</h1><br>
            <button class="back-button" onclick="location.href='inventory.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div><br>
        <div class="form-container">
            <form method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdateItem();">
                <div class="form-group">
                    <?php if (!empty($item['image'])): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>"
                            alt="<?php echo $item['name']; ?>">
                    <?php endif; ?>
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                </div>

                <!-- Two-column layout start -->
                <div class="form-row">
                    <div class="form-group col">
                        <label for="type">Type:</label>
                        <select name="type" id="type" required>
                            <option value="Equipment" <?php echo ($item['type'] == 'Equipment') ? 'selected' : ''; ?>>
                                Equipment</option>
                            <option value="Machinery" <?php echo ($item['type'] == 'Machinery') ? 'selected' : ''; ?>>
                                Machinery</option>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($item['name']); ?>"
                            required>
                    </div>
                    <div class="form-group col">
                        <label for="brand">Brand:</label>
                        <input type="text" name="brand" id="brand"
                            value="<?php echo htmlspecialchars($item['brand']); ?>" required>
                    </div>
                    <div class="form-group col">
                        <label for="built_num">Built Number:</label>
                        <input type="text" name="built_num" id="built_num"
                            value="<?php echo htmlspecialchars($item['built_num']); ?>" required>
                    </div>
                    <div class="form-group col">
                        <label for="color">Color:</label>
                        <input type="text" name="color" id="color"
                            value="<?php echo htmlspecialchars($item['color']); ?>" required>
                    </div>
                    <div class="form-group col">
                        <label for="arrival_date">Arrival Date:</label>
                        <input type="text" name="arrival_date" id="arrival_date"
                            value="<?php echo htmlspecialchars($item['arrival_date']); ?>" readonly>
                    </div>
                    <div class="form-group col">
                        <label for="comment">Note:</label>
                        <textarea name="comment" id="comment"
                            rows="4"><?php echo htmlspecialchars($item['comment']); ?></textarea>
                    </div>
                    <div class="form-group col">
                        <label for="availability">Availability:</label>
                        <select name="availability" id="availability" required <?php echo ($item['availability'] != 0) ? 'disabled' : ''; ?>>
                            <option value="0" <?php echo ($item['availability'] == 0) ? 'selected' : ''; ?>>Available
                            </option>
                            <option value="1" <?php echo ($item['availability'] == 1) ? 'selected' : ''; ?>>Borrowed
                            </option>
                            <option value="2" <?php echo ($item['availability'] == 2) ? 'selected' : ''; ?>>Maintenance
                            </option>
                        </select>
                    </div>
                </div>
                <!-- Two-column layout end -->

                <div class="form-group">
                    <button type="submit">Update Item</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="update-confirmation-overlay" class="update-confirmation-overlay">
        <div class="update-confirmation-content">
            <h3>Are you sure to update this item?</h3>
            <button class="btn btn-yes" onclick="submitUpdateItem()">Yes</button>
            <button class="btn btn-no" onclick="cancelUpdateItem()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="update-success-message" class="update-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Item Updated Successfully</h3>
    </div>


    <?php include('Temp/warehouse_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/inventory.js"></script>
    <script src="JS/notifications.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        // Update
        function confirmUpdateItem() {
            document.getElementById('update-confirmation-overlay').style.display = 'flex';
            return false;
        }

        function cancelUpdateItem() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';
        }

        function submitUpdateItem() {
            document.getElementById('update-confirmation-overlay').style.display = 'none';
            document.querySelector('form').submit();
        }

        function showSuccessMessage() {
            document.getElementById('update-success-message').style.display = 'block';
            setTimeout(function () {
                document.getElementById('update-success-message').style.display = 'none';
                redirectToInventory();
            }, 1000); 
        }

        function redirectToInventory() {
            window.location.href = 'inventory.php';
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