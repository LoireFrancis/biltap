<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

include "connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <title>Inventory Report</title>
</head>

<style>
    /* Inventory Container and Table */
    .inventory-container {
        max-height: 600px;
        overflow-y: auto;
        border: 1px solid var(--color-light);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--color-light);
    }

    .table th {
        background-color: var(--color-primary);
        color: var(--color-white);
        font-weight: bold;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table tbody tr:nth-child(even) {
        background-color: var(--color-white);
    }

    .table tbody tr:hover {
        background-color: var(--color-light);
    }

    .dropdown-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .dropdown-table th,
    .dropdown-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid var(--color-light);
    }

    .dropdown-table th {
        background-color: var(--color-primary);
        color: var(--color-white);
        font-weight: bold;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    /* Header Container */
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

    /* Back Button */
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
        background-color: #5991d8;
    }

    /* Filter and Print Button Container */
    .filter-print-container {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-bottom: 20px;
    }

    /* Filter Form */
    .filter-form {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .filter-form label {
        margin-right: 0.5rem;
        font-weight: bold;
    }

    .filter-form select {
        padding: 0.5rem;
        border-radius: var(--border-radius-1);
        border: 1px solid var(--color-light);
        font-size: 1rem;
    }

    .filter-form button {
        padding: 0.5rem 1rem;
        background-color: var(--color-primary);
        color: var(--color-white);
        border: none;
        border-radius: var(--border-radius-1);
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .filter-form button:hover {
        background-color: var(--color-dark);
    }

    /* Print Button */
    .print-button {
        background-color: #87CEEB;
        color: var(--color-white);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-1);
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .print-button:hover {
        background-color: #50B8E2;
    }

    /* Media Print */
    @media print {

        .header-container,
        .filter-print-container,
        .print-button {
            display: none;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid black;
        }

        .table th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #e0e0e0;
        }

        @page {
            size: auto;
            margin: 20mm;
        }
    }
</style>

<body>

    <?php include('Temp/aside.php'); ?>

    <!-- Main Content -->
    <main>
        <div class="header-container">
            <h1>Inventory Report</h1>
            <button class="back-button" onclick="location.href='report.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div>

        <!-- Filter and Print Button Container -->
        <div class="filter-print-container">
            <!-- Filter Form -->
            <form class="filter-form" method="GET" action="">
                <label for="year">Year:</label>
                <select id="year" name="year">
                    <option value="">All</option>
                    <?php
                    // Generate year options from both arrival_date and maintenance_from
                    $yearsQuery = "
        SELECT DISTINCT YEAR(arrival_date) as year 
        FROM inventory 
        UNION 
        SELECT DISTINCT YEAR(maintenance_from) as year 
        FROM inventory 
        ORDER BY year DESC
    ";
                    $years = $conn->query($yearsQuery);
                    while ($yearRow = $years->fetch_assoc()) {
                        $year = $yearRow['year'];
                        echo "<option value='$year' " . (isset($_GET['year']) && $_GET['year'] == $year ? 'selected' : '') . ">$year</option>";
                    }
                    ?>
                </select>

                <label for="month">Month:</label>
                <select id="month" name="month">
                    <option value="">All</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>"
                            <?php echo (isset($_GET['month']) && $_GET['month'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''); ?>>
                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <!-- Maintenance Month Filter -->
                <label for="maintenance_month">Maintenance Month:</label>
                <select id="maintenance_month" name="maintenance_month">
                    <option value="">All</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>"
                            <?php echo (isset($_GET['maintenance_month']) && $_GET['maintenance_month'] == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''); ?>>
                            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <!-- Day Filter -->
                <label for="maintenance_day">Day:</label>
                <select id="maintenance_day" name="maintenance_day">
                    <option value="">All</option>
                    <?php for ($day = 1; $day <= 31; $day++): ?>
                        <option value="<?php echo str_pad($day, 2, '0', STR_PAD_LEFT); ?>"
                            <?php echo (isset($_GET['maintenance_day']) && $_GET['maintenance_day'] == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : ''); ?>>
                            <?php echo $day; ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <button type="submit">Filter</button>
            </form>


            <!-- Print Button -->
            <button class="print-button" type="button" onclick="printContent()">Print</button>
        </div>

        <div class="inventory-container">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Image</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Built Number</th>
                        <th>Color</th>
                        <th>Arrival Date</th>
                        <th>Prevent From</th>
                        <th>Prevent To</th>
                        <th>Availability</th>
                        <th>Days Ago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Filter logic
$year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';
$month = isset($_GET['month']) ? $conn->real_escape_string($_GET['month']) : '';
$maintenance_month = isset($_GET['maintenance_month']) ? $conn->real_escape_string($_GET['maintenance_month']) : '';
$maintenance_day = isset($_GET['maintenance_day']) ? $conn->real_escape_string($_GET['maintenance_day']) : '';

$sql = "SELECT * FROM inventory WHERE 1 = 1"; // Base query

// Filter by year and month for arrival_date and maintenance_from
if ($year) {
    $sql .= " AND (YEAR(arrival_date) = '$year' OR YEAR(maintenance_from) = '$year')";
}
if ($month) {
    $sql .= " AND MONTH(arrival_date) = '$month'";
}

// Maintenance filter: Check if the selected month is within the range of maintenance_from and maintenance_to
if ($maintenance_month) {
    $sql .= " AND availability = 0"; // Only show records where availability is 0
    $sql .= " AND (
        (YEAR(maintenance_from) = '$year' AND MONTH(maintenance_from) <= '$maintenance_month')
        OR (YEAR(maintenance_to) = '$year' AND MONTH(maintenance_to) >= '$maintenance_month')
        OR (
            YEAR(maintenance_from) < '$year' AND YEAR(maintenance_to) > '$year'
        )
    )";
}

// Day Filter
if ($maintenance_day) {
    $sql .= " AND DAY(maintenance_from) = '$maintenance_day'";
}
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Calculate days passed since the maintenance_from date
                            $maintenanceFromDate = new DateTime($row['maintenance_from']);
                            $currentDateTime = new DateTime(); // Get the current date and time

                            if ($maintenanceFromDate < $currentDateTime) {
                                $interval = $maintenanceFromDate->diff($currentDateTime);
                                $daysPassed = $interval->days;
                            } else {
                                $daysPassed = 0; // Maintenance date is in the future
                            }

                            // Prepare the days ago text if applicable (availability must be 0 and maintenance_from must be in the past)
                            $daysAgoText = '';
                            if ($row['availability'] == 0 && $daysPassed > 0) {
                                $daysAgoText = "<div style='color: red; margin-bottom: .4rem; font-size: 10px;'>$daysPassed days ago</div>";
                            }

                    ?>
                            <tr data-id="<?php echo $row['id']; ?>">
                                <td>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>"
                                        alt="Item Image" style="width: 50px; height: 50px; border-radius: 50%;">
                                </td>
                                <td><?php echo $row["type"]; ?></td>
                                <td><?php echo $row["name"]; ?></td>
                                <td><?php echo $row["brand"]; ?></td>
                                <td><?php echo $row["built_num"]; ?></td>
                                <td><?php echo $row["color"]; ?></td>
                                <td><?php echo $row["arrival_date"]; ?></td>
                                <td><?php echo $row["maintenance_from"]; ?></td>
                                <td><?php echo $row["maintenance_to"]; ?></td>
                                <td>
                                    <?php
                                    echo $row["availability"] == 0 ? 'Available' : ($row["availability"] == 1 ? 'Borrowed' : 'Maintenance');
                                    ?>
                                </td>
                                <td><?php echo $daysAgoText; ?></td> <!-- New cell for days ago -->
                            </tr>
                            <tr id="maintenance-row-<?php echo $row['id']; ?>" style="display:none;">
                                <td colspan="9">
                                    <div class="maintenance-container" id="maintenance-container-<?php echo $row['id']; ?>">
                                        <!-- Maintenance equipment will be loaded here -->
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='9'>No items found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <!-- End of Main Content -->

    <?php include('Temp/admin_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>


    <script src="JS/global.js"></script>
    <script src="JS/loggedout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        //dropdown
        document.addEventListener('DOMContentLoaded', function() {
            var rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(function(row) {
                row.addEventListener('click', function() {
                    var inventoryId = this.dataset.id;
                    loadMaintenanceEquipment(inventoryId);
                });
            });
        });

        function loadMaintenanceEquipment(inventoryId) {
            var maintenanceRow = document.getElementById("maintenance-row-" + inventoryId);
            var maintenanceContainer = document.getElementById("maintenance-container-" + inventoryId);

            // Toggle visibility of the maintenance row
            if (maintenanceRow.style.display === "none" || maintenanceRow.style.display === "") {
                maintenanceRow.style.display = "table-row";
            } else {
                maintenanceRow.style.display = "none";
                return;
            }

            // Clear existing content
            maintenanceContainer.innerHTML = "Loading...";

            // Make AJAX request to fetch maintenance equipment
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_inventory.php?inventory_id=" + inventoryId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    maintenanceContainer.innerHTML = xhr.responseText;
                } else {
                    maintenanceContainer.innerHTML = "Failed to load maintenance equipment.";
                }
            };
            xhr.send();
        }

        //print
        function printContent() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; }');
            printWindow.document.write('h1 { text-align: center; margin-bottom: 20px; }');
            printWindow.document.write('.table { width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: center; }');
            printWindow.document.write('.table th, .table td { padding: 8px; text-align: center; border: 1px solid black; }');
            printWindow.document.write('.table th { background-color: #333; color: white; font-weight: bold; }');
            printWindow.document.write('.table tbody tr:nth-child(even) { background-color: #f2f2f2; }');
            printWindow.document.write('.table tbody tr:hover { background-color: #e0e0e0; }');
            printWindow.document.write('.logo { text-align: center; margin-bottom: 20px; }');
            printWindow.document.write('.logo img { width: 100px; height: auto; }');
            printWindow.document.write('@page { size: auto; margin: 20mm; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');

            // Include the logo at the top
            printWindow.document.write('<div class="logo">');
            printWindow.document.write('<img src="Picture/logo.png" alt="User Profile Logo">');
            printWindow.document.write('</div>');

            // Clone the main content and remove non-printable elements
            var mainContent = document.querySelector('main').cloneNode(true);
            var printButton = mainContent.querySelector('.print-button');
            var backButton = mainContent.querySelector('.back-button');
            if (printButton) {
                printButton.remove();
            }
            if (backButton) {
                backButton.remove();
            }
            printWindow.document.write(mainContent.innerHTML);

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</body>

</html>