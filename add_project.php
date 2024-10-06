<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
include "connect.php";

// Function to generate a unique project ID
function generateProjectId($conn)
{
    $prefix = 'BILTAP-';
    $length = 5;
    $unique = false;

    while (!$unique) {
        $random_number = str_pad(rand(1, 99999), $length, '0', STR_PAD_LEFT);
        $projectid = $prefix . $random_number;

        $query = "SELECT COUNT(*) AS count FROM projects WHERE projectid = '$projectid'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row['count'] == 0) {
            $unique = true;
        }
    }

    return $projectid;
}

$projectid = generateProjectId($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectid = $_POST['projectid'] ?? $projectid;
    $project_name = $_POST['project_name'] ?? '';
    $project_location = $_POST['project_location'] ?? '';
    $description = $_POST['description'] ?? '';
    $project_holder = $_POST['project_holder'] ?? '';
    $position = $_POST['position'] ?? '';
    $date_started = $_POST['date_started'] ?? '';
    $date_finish = $_POST['date_finish'] ?? '';
    $status = $_POST['status'] ?? '0';

    $query = "INSERT INTO projects (projectid, project_name, project_location, description, project_holder, position, date_started, date_finish, status) 
              VALUES ('$projectid', '$project_name', '$project_location', '$description', '$project_holder', '$position', '$date_started', '$date_finish', '$status')";

    if (mysqli_query($conn, $query)) {
        if (isset($_POST['equipment_names']) && isset($_POST['built_nums'])) {
            $names = $_POST['equipment_names'];
            $built_nums = $_POST['built_nums'];

            for ($i = 0; $i < count($names); $i++) {
                $name = mysqli_real_escape_string($conn, $names[$i]);
                $built_num = mysqli_real_escape_string($conn, $built_nums[$i]);

                $query_borrowed = "INSERT INTO borrowed (projectid, project_holder, position, name, built_num, status) 
                               VALUES ('$projectid', '$project_holder', '$position', '$name', '$built_num', 0)";
                mysqli_query($conn, $query_borrowed);

                // Update availability in inventory table
                $query_update_availability = "UPDATE inventory SET availability = 1 WHERE name = '$name' AND built_num = '$built_num'";
                mysqli_query($conn, $query_update_availability);
            }
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}

$incharge_query = "SELECT fullname, position FROM user WHERE users = 'client'";
$incharge_result = mysqli_query($conn, $incharge_query);

$incharge_options = "";
if (mysqli_num_rows($incharge_result) > 0) {
    while ($row = mysqli_fetch_assoc($incharge_result)) {
        $fullname = htmlspecialchars($row['fullname']);
        $position = htmlspecialchars($row['position']);
        $incharge_options .= "<option value='" . $fullname . "' data-position='" . $position . "'>" . $fullname . "</option>";
    }
} else {
    $incharge_options = "<option value=''>No clients found</option>";
}

$equip_query = "SELECT id, name, built_num FROM inventory WHERE availability = 0";
$equip_result = mysqli_query($conn, $equip_query);

$equip_options = "";
if (mysqli_num_rows($equip_result) > 0) {
    while ($row = mysqli_fetch_assoc($equip_result)) {
        $equip_options .= "<option value='" . $row['id'] . "' data-built-num='" . $row['built_num'] . "'>" . htmlspecialchars($row['name']) . "</option>";
    }
} else {
    $equip_options = "<option value=''>No equipment available</option>";
}

mysqli_close($conn);
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
    <title>Add Project</title>
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

        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .form-left,
        .form-right {
            flex: 1;
            min-width: 300px;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--color-dark);
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

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="textarea"]:focus,
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
        body.dark-mode input[type="date"],
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

        /* added E/M and built num */
        #equipment_inputs_container {
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
        }

        .equipment-input-group {
            display: flex;
            margin-bottom: 10px;
        }

        .equipment-input-group input {
            margin-right: 10px;
        }

        .remove-button {
            background: none;
            border: none;
            cursor: pointer;
            color: red;
        }

        .add {
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

        .add:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include('Temp/aside.php'); ?>

    <main>
        <div class="header-container">
            <h1>Add New Project</h1>
            <button class="back-button" onclick="location.href='projects.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div>
        <div class="inventory-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                enctype="multipart/form-data" onsubmit="return confirmAddProject();">
                <div class="form-container">
                    <div class="form-left">
                        <label for="projectid">Project ID:</label>
                        <input type="text" id="projectid" name="projectid"
                            value="<?php echo htmlspecialchars($projectid); ?>" readonly /><br>

                        <label for="project_name">Project:</label>
                        <input type="text" id="project_name" name="project_name">

                        <label for="project_location">Location:</label>
                        <input type="text" id="project_location" name="project_location" required>

                        <label for="project_holder">Incharge:</label>
                        <select id="project_holder" name="project_holder" required onchange="updatePosition()">
                            <option value="" disabled selected>Select a person</option>
                            <?php echo $incharge_options; ?>
                        </select>

                        <label for="position">Position:</label>
                        <input type="text" id="position" name="position" readonly>

                        <label>Equip/Machine:</label>
                        <select id="equip_machine" name="equip_machine" onchange="updateBuiltNum()">
                            <option value="" disabled selected>Select Equipment</option>
                            <?php echo $equip_options; ?>
                        </select>

                        <button type="button" class="add" onclick="addEquipment()">Add to Project</button>

                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="Not Started">Not Started</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Finish">Finish</option>
                        </select>
                    </div>
                    <div class="form-right">
                        <input type="hidden" id="built_num" name="built_num" readonly>

                        <label for="description">Description:</label>
                        <input type="textarea" id="description" name="description">

                        <label for="date_started">Date started:</label>
                        <input type="date" id="date_started" name="date_started" required>

                        <label for="date_finish">Date finish:</label>
                        <input type="date" id="date_finish" name="date_finish" required>

                        <!--display added equipment -->
                        <label>Selected Equip/Machine:</label>
                        <div id="equipment_inputs_container" style="max-height: 300px; overflow-y: auto;">
                            <div id="equipment_inputs"></div>
                        </div>
                    </div>
                    <button type="submit">Add Project</button>
            </form>
        </div>
    </main>

    <!-- Confirmation Overlay -->
    <div id="add-confirmation-overlay" class="add-confirmation-overlay">
        <div class="add-confirmation-content">
            <h3>Are you sure to add this project?</h3>
            <button class="btn btn-yes" onclick="submitAddProject()">Yes</button>
            <button class="btn btn-no" onclick="cancelAddProject()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="add-success-message" class="add-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Project Added Successfully</h3>
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
        //post position
        function updatePosition() {
            var select = document.getElementById('project_holder');
            var positionInput = document.getElementById('position');
            var selectedOption = select.options[select.selectedIndex];
            positionInput.value = selectedOption.getAttribute('data-position') || '';
        }

        //post equip.machine and built num
        let selectedEquipments = [];

        function updateBuiltNum() {
            var select = document.getElementById('equip_machine');
            var builtNumInput = document.getElementById('built_num');
            var selectedOption = select.options[select.selectedIndex];
            builtNumInput.value = selectedOption.getAttribute('data-built-num') || '';
        }

        function addEquipment() {
            var select = document.getElementById('equip_machine');
            var builtNum = document.getElementById('built_num').value;

            if (select.value === "" || builtNum === "") {
                alert("Please select equipment and ensure built number is filled.");
                return;
            }

            var selectedOption = select.options[select.selectedIndex];
            var equipmentName = selectedOption.text;
            var equipmentId = select.value;

            if (selectedEquipments.some(e => e.id === equipmentId)) {
                alert("Already added.");
                return;
            }

            selectedEquipments.push({ id: equipmentId, name: equipmentName, built_num: builtNum });

            updateEquipmentList();

            select.value = "";
            document.getElementById('built_num').value = "";
        }

        function updateEquipmentList() {
            var equipmentInputs = document.getElementById('equipment_inputs');
            equipmentInputs.innerHTML = "";

            selectedEquipments.forEach(function (equipment, index) {
                var inputGroup = document.createElement('div');
                inputGroup.className = "equipment-input-group";

                var equipmentInput = document.createElement('input');
                equipmentInput.type = "text";
                equipmentInput.name = "equipment_names[]";
                equipmentInput.value = equipment.name;
                equipmentInput.placeholder = "Equip/Machine";
                equipmentInput.required = true;

                var builtNumInput = document.createElement('input');
                builtNumInput.type = "text";
                builtNumInput.name = "built_nums[]";
                builtNumInput.value = equipment.built_num;
                builtNumInput.placeholder = "Built Number";
                builtNumInput.required = true;

                var removeButton = document.createElement('button');
                removeButton.type = "button";
                removeButton.className = "remove-button";
                removeButton.onclick = function () {
                    removeEquipment(index);
                };

                var icon = document.createElement('span');
                icon.className = "material-icons";
                icon.textContent = "delete";

                function removeEquipment(index) {
                    selectedEquipments.splice(index, 1);
                    updateEquipmentList();
                }

                removeButton.appendChild(icon);
                inputGroup.appendChild(equipmentInput);
                inputGroup.appendChild(builtNumInput);
                inputGroup.appendChild(removeButton);
                equipmentInputs.appendChild(inputGroup);
            });

            updateHiddenInputs();
        }

        function updateHiddenInputs() {
        }

        //add account
        function confirmAddProject() {
            document.getElementById('add-confirmation-overlay').style.display = 'flex';
            return false;
        }

        function cancelAddProject() {
            document.getElementById('add-confirmation-overlay').style.display = 'none';
        }

        function submitAddProject() {
            document.getElementById('add-confirmation-overlay').style.display = 'none';
            document.querySelector('form').submit();
        }

        // Check for success parameter in the URL
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                document.getElementById('add-success-message').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('add-success-message').style.display = 'none';
                }, 1000);
            }
        }
    </script>

</body>

</html>