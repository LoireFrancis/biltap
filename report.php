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
    <title>ConstructQR-Dashboard</title>
</head>
<style>
    .inventory-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .inventory-container :hover {
        transform: scale(1.05);
    }

    .reports {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }

    .report-item {
        background-color: #383838;
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        font-size: 18px;
    }

    .report-cards {
        background-color: var(--color-white);
        padding: var(--card-padding);
        border-radius: var(--card-border-radius);
        margin-top: 1rem;
        box-shadow: var(--box-shadow);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 15vh;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.5rem;
        text-align: center;
    }

    .report-cards span {
        font-size: 3rem;
        display: block;
    }

    /* Responsive styles for Tablets (iPad) */
    @media screen and (max-width: 1200px) and (min-width: 769px) {
        .inventory-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Responsive styles for Mobile Phones */
    @media screen and (max-width: 768px) {
        .inventory-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<body>

    <?php include('Temp/aside.php'); ?>

    <!-- Main Content -->
    <main>
        <div class="header-container">
            <h1>Print Reports</h1>

            <div class="inventory-container">
                <a href="project_report.php" class="report-cards">
                    <span class="material-icons-sharp">import_contacts</span>
                    <p>Projects</p>
                </a>
                <a href="inventory_report.php" class="report-cards">
                    <span class="material-icons-sharp">inventory</span>
                    <p>Equipment/Machine</p>
                </a>
                <a href="maintenance_report.php" class="report-cards">
                    <span class="material-icons-sharp">build</span>
                    <p>Maintenance</p>
                </a> <a href="graph_report.php" class="report-cards">
                    <span class="material-icons-sharp">insights</span>
                    <p>Maintenance Graph</p>
                </a>
                <a href="borrowed_report.php" class="report-cards">
                    <span class="material-icons-sharp">import_contacts</span>
                    <p>Borrowed E/M</p>
                </a>
                <a href="return_emergency_report.php" class="report-cards">
                    <span class="material-icons-sharp">warning</span>
                    <p>Borrowed Maintenace</p>
                </a>
            </div>
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
    </script>

</body>

</html>