<?php
include "connect.php";

$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Adjusted query to count maintenance items
$totalItemsQuery = "SELECT COUNT(*) as total FROM maintenance";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Adjusted query to select maintenance items with pagination
$sql = "SELECT * FROM maintenance LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/notifications.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <title>Maintenance</title>
</head>
<style>
    .inventory-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .item {
        background-color: var(--color-white);
        border-radius: var(--border-radius-1);
        box-shadow: var(--box-shadow);
        cursor: pointer;
        transition: transform 0.3s;
    }

    .item:hover {
        transform: scale(1.05);
    }

    .item img,
    .item-details img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-top-left-radius: var(--border-radius-1);
        border-top-right-radius: var(--border-radius-1);
    }

    .item-name {
        padding: 0.5rem;
        text-align: center;
        font-weight: bold;
    }

    .item-details {
        background-color: var(--color-white);
        border-radius: var(--border-radius-1);
        box-shadow: var(--box-shadow);
        padding: 1rem;
        margin-top: 1rem;
    }

    .item-details:hover {
        box-shadow: none;
    }

    .item-details h2 {
        text-align: center;
        font-weight: bold;
        color: var(--primary-color);
    }

    .details-row {
        display: flex;
        padding: 0.5rem 0;
    }

    .label {
        width: 50%;
        font-weight: bold;
        text-align: left;
        padding-right: 1rem;
    }

    .value {
        width: 65%;
        text-align: left;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .add-button {
        background-color: #72b0f1;
        color: var(--text-color);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-1);
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .add-button:hover {
        background-color: #72b0f1;
    }

    .search-bar {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #ccc;
        border-radius: var(--border-radius-1);
        background-color: #fff;
        color: #333;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .search-bar::placeholder {
        color: #666;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }

    .pagination a {
        text-decoration: none;
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        border: 1px solid #ddd;
        border-radius: var(--border-radius-1);
        transition: background-color 0.3s;
    }

    .pagination a:hover {
        background-color: #f0f0f0;
    }

    .pagination .active {
        background-color: var(--primary-color);
        color: #fff;
        border: 1px solid var(--primary-color);
    }

    .item-details .done-btn {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .item-details .done-btn:hover {
        background-color: #388E3C;
    }

    .show-maintenance-history {
        background-color: #2196F3;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .show-maintenance-history:hover {
        background-color: #1976D2;
    }

    /* Styles for the maintenance chart container */
    #maintenance-chart-container {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--color-background);
        padding: 1rem;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        border-top: 1px solid var(--color-dark-variant);
        max-height: 60vh;
        overflow-y: auto;
    }

    /* Styles for the chart itself */
    #maintenance-chart {
        width: 100%;
        height: 300px;
        max-height: 300px;
    }

    /* Styles for the 'No maintenance record' message */
    #no-record-message {
        display: none;
        color: var(--color-danger);
        font-size: 1.2rem;
        text-align: center;
        margin-top: 1rem;
    }

    /* Optional: Style for maintenance content area */
    #maintenance-content {
        position: relative;
        padding: 0 1rem;
    }
</style>

<body>
    <?php include('Temp/aside_warehouse.php'); ?>
    <main>
        <div class="header-container">
            <h1>Maintenance History Management</h1>
        </div><br>
        <input type="text" class="search-bar" placeholder="Search here...">

        <div class="inventory-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['name']}'>
                <div class='item-name'>{$row['name']}</div>
            </div>";
                }
            } else {
                echo "<p>No items found</p>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>">Next</a>
            <?php endif; ?>
        </div>

        <div class="graph-container">
            <canvas id="maintenanceGraph"></canvas>
        </div>

        <!-- Container for the maintenance history chart -->
        <div class="maintenance-chart-container" id="maintenance-chart-container">
            <button onclick="hideMaintenanceHistory()"
                style="background: #db4f4f; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">X</button>
            <div class="maintenance-content" id="maintenance-content">
                <canvas id="maintenance-chart"></canvas>
                <!-- No maintenance record message -->
                <p id="no-record-message" style="display: none; text-align: center;">No maintenance record</p>
            </div>
        </div>
    </main>

    <?php include('Temp/header.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>
    <br>

    <div class="item-details" id="item-details" style="text-align: center;">Details display here...</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/maintenance.js"></script>
    <script src="JS/notifications.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_maintenance_details.php',
                type: 'GET',
                data: {
                    id: itemId
                },
                success: function (response) {
                    var detailsHtml = `
            <div class="details-content">
                ${response}
                <br>
            </div>
        `;
                    $('#item-details').html(detailsHtml).addClass('active');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $('#item-details').html('Error loading details.');
                }
            });
        }

        function showMaintenanceHistory(itemId) {
            $.ajax({
                url: 'get_maintenance_history.php',
                type: 'GET',
                data: { id: itemId },
                success: function (data) {
                    var maintenanceData = JSON.parse(data);
                    displayMaintenanceHistory(maintenanceData);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function displayMaintenanceHistory(data) {
            var ctx = document.getElementById('maintenance-chart').getContext('2d');
            var labels = data.length ? data.map(entry => new Date(entry.maintenance_date).toLocaleDateString()) : [];
            var comments = data.length ? data.map(entry => entry.comment) : [];

            if (data.length === 0) {
                document.getElementById('no-record-message').style.display = 'block';
                document.getElementById('maintenance-chart').style.display = 'none';
            } else {
                document.getElementById('no-record-message').style.display = 'none';
                document.getElementById('maintenance-chart').style.display = 'block';

                if (window.maintenanceChart) {
                    window.maintenanceChart.destroy();
                }

                window.maintenanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Maintenance Records',
                            data: Array(labels.length).fill(1),
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function (context) {
                                        return 'Maintenance Comment';
                                    },
                                    label: function (context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += comments[context.dataIndex];
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                display: false,
                            }
                        }
                    }
                });
            }

            document.getElementById('maintenance-chart-container').style.display = 'block';
        }

        function hideMaintenanceHistory() {
            document.getElementById('maintenance-chart-container').style.display = 'none';
        }

        function DoneItem(itemId) {
            window.location.href = 'update_maintenance.php?id=' + itemId;
        }

        $(document).ready(function () {
            $('.search-bar').on('keyup', function () {
                var searchTerm = $(this).val().trim();
                if (searchTerm.length > 1) {
                    $.ajax({
                        url: 'search_maintenance.php',
                        type: 'GET',
                        data: {
                            search: searchTerm
                        },
                        success: function (data) {
                            $('.inventory-container').html(data);
                        }
                    });
                } else {

                    $.ajax({
                        url: 'search_maintenance.php',
                        type: 'GET',
                        success: function (data) {
                            $('.inventory-container').html(data);
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>