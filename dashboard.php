<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

include "connect.php";

$sqlTotalRows = "SELECT COUNT(*) as total FROM inventory";
$sqlVisits = "SELECT COUNT(*) as total FROM inventory WHERE availability = 2";
$sqlSearches = "SELECT COUNT(*) as total FROM inventory WHERE availability = 1";

$resultTotalRows = $conn->query($sqlTotalRows);
$resultVisits = $conn->query($sqlVisits);
$resultSearches = $conn->query($sqlSearches);

$totalRows = $resultTotalRows->fetch_assoc()['total'];
$totalVisits = $resultVisits->fetch_assoc()['total'];
$totalSearches = $resultSearches->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <title>Dashboard</title>
</head>
<style>
    .reminder-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.reminder-text {
    width: 100%;
    margin-bottom: 0.5rem;
}

.reminder-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.reminder-datetime {
    width: 70%; 
}

.delete-reminder {
    cursor: pointer;
    color: lightcoral; 
    font-size: 1.5rem;
    margin-left: 0.5rem; 
}

</style>
<body>
    <?php include('Temp/aside.php'); ?>

    <!-- Main Content -->
    <main>
        <h1>Analytics</h1>
        <!-- Analyses -->
        <div class="analyse">
            <div class="inventory">
                <div class="status">
                    <div class="info">
                        <h3>Equipment<br>Machinery</h3>
                    </div>
                    <div class="progresss">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="percentage">
                            <h1><?php echo $totalRows; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="maintenance">
                <div class="status">
                    <div class="info">
                        <h3>Under<br>Maintenance</h3>
                    </div>
                    <div class="progresss">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="percentage">
                            <h1><?php echo $totalVisits; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="borrowed">
                <div class="status">
                    <div class="info">
                        <h3>Borrowed</h3>
                    </div>
                    <div class="progresss">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="percentage">
                            <h1><?php echo $totalSearches; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>
        <!-- End of Analyses -->

        <!-- New Equipment/Machinery Section -->
        <div class="new-item">
            <h2>New Equipment/Machinery</h2>
            <div class="item-list">
                <?php
                include "connect.php";

                $sql = "SELECT *, TIMESTAMPDIFF(HOUR, arrival_date, NOW()) as hours_elapsed FROM inventory ORDER BY arrival_date DESC LIMIT 4";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $timeSinceArrival = $row['hours_elapsed'] > 24 ? floor($row['hours_elapsed'] / 24) . ' days ago' : $row['hours_elapsed'] . ' hours ago';
                        echo '<div class="item">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '">';
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<p>Added ' . $timeSinceArrival . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="user"><p>No new equipment/machinery found.</p></div>';
                }

                $conn->close();
                ?>
            </div>
        </div><br>
        <!-- End of New Equipment/Machinery Section -->

        <!-- Recent Borrowed Table -->
        <div class="recent-borrowed">
            <h2>Recent Borrowed</h2>
            <div class="item-list">
                <?php
                include "connect.php";

                // Fetch borrowed items from the inventory
                $sql = "SELECT * FROM inventory WHERE availability = 1 ORDER BY arrival_date DESC LIMIT 4";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="item">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '">';
                        echo '<div class="info">';
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="user"><p>No recent borrowed items found.</p></div>';
                }

                $conn->close();
                ?>
            </div>
        </div>

        <!-- End of Recent Orders -->
    </main>
    <!-- End of Main Content -->

    <!-- Confirmation Overlay -->
    <div id="confirmation-overlay" class="confirmation-overlay">
        <div class="confirmation-content">
            <h3>Are you sure to logout?</h3>
            <button class="btn btn-yes" onclick="logout()">Yes</button>
            <button class="btn btn-no" onclick="cancelLogout()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="success-message">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Logout Successful</h3>
    </div>

    <?php include('Temp/admin_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>

    <div class="reminders">
        <div class="header">
            <h2>Reminders</h2>
            <span class="material-icons-sharp">alarm</span>
        </div>

        <div class="reminder-list" id="reminderList">
            <!-- Reminder items will be dynamically added here -->
        </div>

        <div class="notification add-reminder" id="addReminder">
            <div>
                <span class="material-icons-sharp">add</span>
                <h3>Add Reminder</h3>
            </div>
        </div>
    </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
    </script>
</body>

</html>