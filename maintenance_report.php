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
        color: var(--color-white);
        border-radius: var(--border-radius-1);
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
        border: 1px solid #87CEEB;
    }

    .filter-form button:hover {
        background-color: var(--color-dark-variant);
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
            <h1>Maintenance Report</h1>
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
                    // Generate year options
                    $years = $conn->query("SELECT DISTINCT YEAR(maintenance_date) as year FROM maintenance ORDER BY year DESC");
                    while ($yearRow = $years->fetch_assoc()) {
                        $year = $yearRow['year'];
                        echo "<option value='$year' " . (isset($_GET['year']) && $_GET['year'] == $year ? 'selected' : '') . ">$year</option>";
                    }
                    ?>
                </select>

                <label for="month">Month:</label>
                <select id="month" name="month">
                    <option value="">All</option>
                    <option value="01" <?php echo (isset($_GET['month']) && $_GET['month'] == '01' ? 'selected' : ''); ?>>
                        January</option>
                    <option value="02" <?php echo (isset($_GET['month']) && $_GET['month'] == '02' ? 'selected' : ''); ?>>
                        February</option>
                    <option value="03" <?php echo (isset($_GET['month']) && $_GET['month'] == '03' ? 'selected' : ''); ?>>
                        March</option>
                    <option value="04" <?php echo (isset($_GET['month']) && $_GET['month'] == '04' ? 'selected' : ''); ?>>
                        April</option>
                    <option value="05" <?php echo (isset($_GET['month']) && $_GET['month'] == '05' ? 'selected' : ''); ?>>
                        May</option>
                    <option value="06" <?php echo (isset($_GET['month']) && $_GET['month'] == '06' ? 'selected' : ''); ?>>
                        June</option>
                    <option value="07" <?php echo (isset($_GET['month']) && $_GET['month'] == '07' ? 'selected' : ''); ?>>
                        July</option>
                    <option value="08" <?php echo (isset($_GET['month']) && $_GET['month'] == '08' ? 'selected' : ''); ?>>
                        August</option>
                    <option value="09" <?php echo (isset($_GET['month']) && $_GET['month'] == '09' ? 'selected' : ''); ?>>
                        September</option>
                    <option value="10" <?php echo (isset($_GET['month']) && $_GET['month'] == '10' ? 'selected' : ''); ?>>
                        October</option>
                    <option value="11" <?php echo (isset($_GET['month']) && $_GET['month'] == '11' ? 'selected' : ''); ?>>
                        November</option>
                    <option value="12" <?php echo (isset($_GET['month']) && $_GET['month'] == '12' ? 'selected' : ''); ?>>
                        December</option>
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
                        <th>Note</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Filter logic
                    $year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';
                    $month = isset($_GET['month']) ? $conn->real_escape_string($_GET['month']) : '';

                    $sql = "SELECT * FROM maintenance";
                    if ($year) {
                        $sql .= " WHERE YEAR(maintenance_date) = '$year'";
                        if ($month) {
                            $sql .= " AND MONTH(maintenance_date) = '$month'";
                        }
                    } elseif ($month) {
                        $sql .= " WHERE MONTH(maintenance_date) = '$month'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
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
                                <td><?php echo $row["comment"]; ?></td>
                                <td><?php echo $row["availability"] == 0 ? 'Available' : ($row["availability"] == 1 ? 'Borrowed' : 'Maintenance'); ?>
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