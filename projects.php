<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
include "connect.php";

$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$totalItemsQuery = "SELECT COUNT(*) as total FROM projects WHERE status IN ('Not started', 'Ongoing')";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$sql = "SELECT * FROM projects WHERE status IN ('Not started', 'Ongoing') LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

if (isset($_GET['message'])) {
    echo "<p class='success-message'>" . htmlspecialchars($_GET['message']) . "</p>";
}
if (isset($_GET['error'])) {
    echo "<p class='error-message'>" . htmlspecialchars($_GET['error']) . "</p>";
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
    <title>Projects</title>
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

    .item .material-icons-sharp {
        width: 100%;
        height: 195px;
        font-size: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-top-left-radius: var(--border-radius-1);
        border-top-right-radius: var(--border-radius-1);
        object-fit: cover;
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
        margin-right: 1rem;
    }

    .label {
        width: 50%;
        font-weight: bold;
        text-align: left;
        padding-right: 1rem;
    }

    .value {
        width: 50%;
        text-align: left;
    }

    .description-textarea {
        width: 100%;
        max-width: 50ch;
        height: auto;
        min-height: 70px;
        resize: none;
        text-align: justify;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: var(--border-radius-1);
        background-color: var(--color-white);
        color: var(--color-text-light);
    }

    .justify {
        text-align: justify;
    }

    /* Action Buttons Container */
    .action-buttons-container {
        display: flex;
        gap: 2px;
    }

    /* Action Buttons */
    .action-button {
        background-color: var(--color-primary);
        color: var(--color-white);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-1);
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
        margin-right: 5px;
    }

    .action-button.update {
        background-color: #72b0f1;
    }

    .action-button.finish {
        background-color: #28a745;
    }

    .action-button:hover {
        opacity: 0.8;
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
        background-color: #66a0e2;
    }

    .search-bar-projects {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #ccc;
        border-radius: var(--border-radius-1);
        background-color: #fff;
        color: #333;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .search-bar-projects::placeholder {
        color: #666;
    }

    .success-message {
        color: green;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .error-message {
        color: red;
        font-weight: bold;
        margin-bottom: 1rem;
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

    /* Show Borrow History Button */
    .show-borrow-history {
        background-color: #2196F3;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .show-borrow-history:hover {
        background-color: #1976D2;
    }

    /* Borrow Chart Container Styles */
    .borrow-chart-container {
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
        max-height: 50vh;
        overflow-y: auto;
    }

    .borrow-content {
        position: relative;
        padding: 0 1rem;
    }

    #borrow-chart {
        width: 100%;
        height: 30vh;
        max-height: 40vh;
    }

    #no-record-message {
        display: none;
        color: #6c757d;
        font-size: 1.2rem;
        text-align: center;
        margin-top: 1rem;
    }

    /* Responsive styles */
    @media screen and (max-width: 1200px) {
        .inventory-container {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Responsive styles for Tablets (iPad) */
    @media screen and (max-width: 1200px) and (min-width: 769px) {
        .inventory-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Responsive styles for Mobile Phones */
    @media screen and (max-width: 768px) {
        .inventory-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<body>

    <?php include('Temp/aside.php'); ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header-container">
            <h1>Projects Management</h1>
            <button class="add-button" onclick="location.href='add_project.php';">New Project</button>
        </div><br>
        <input type="text" class="search-bar-projects" placeholder="Search here...">

        <div class="inventory-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                <span class='material-icons-sharp'>folder</span>
                <div class='item-name'>{$row['project_name']}</div>
                <div class='item-name'>{$row['project_location']}</div>
            </div>";
                }
            } else {
                echo "<p>No items found</p>";
            }
            ?>
        </div>

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

        <!-- Container for the borrow history chart -->
        <div class="borrow-chart-container" id="borrow-chart-container">
            <button onclick="hideBorrowHistory()"
                style="background: #db4f4f; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">X</button>
            <div class="borrow-content" id="borrow-content">
                <canvas id="borrow-chart"></canvas>
                <!-- No borrow record message -->
                <p id="no-record-message" style="display: none; text-align: center;">No borrow record</p>
            </div>
        </div>
    </main>
    <!-- End of Main Content -->

    <?php include('Temp/admin_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>

    <div class="item-details" id="item-details" style="text-align: center;">Details display here...</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/projects.js"></script>
    <script src="JS/loggedout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        //search project
        $(document).ready(function () {
            $('.search-bar-projects').on('keyup', function () {
                var searchTerm = $(this).val().trim();
                if (searchTerm.length > 1) {
                    $.ajax({
                        url: 'search_projects.php',
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
                        url: 'search_projects.php',
                        type: 'GET',
                        success: function (data) {
                            $('.inventory-container').html(data);
                        }
                    });
                }
            });
        });

        //show details
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_project_details.php',
                type: 'GET',
                data: {
                    id: itemId
                },
                success: function (response) {
                    var detailsHtml = `
<div class="details-content">
    ${response}
    <br>
    <div class="details-actions">
        <button class="action-button update" onclick="location.href='update_project.php?id=${itemId}'">Update</button>
        <button class="action-button finish" onclick="finishProject(${itemId})">Finish</button>
        <button class="show-borrow-history" onclick="showBorrowHistory(${itemId})">History</button>
    </div>
</div>`;
                    $('#item-details').html(detailsHtml).addClass('active');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $('#item-details').html('Error loading details.');
                }
            });
        }

        // Show borrowed history
        function showBorrowHistory(itemId) {
            $.ajax({
                url: 'get_borrow_history.php',
                type: 'GET',
                data: { id: itemId },
                success: function (data) {
                    var borrowData = JSON.parse(data);
                    displayBorrowHistory(borrowData);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function displayBorrowHistory(data) {
            var ctx = document.getElementById('borrow-chart').getContext('2d');

            var labels = data.length ? data.map(entry => new Date(entry.borrowed_date).toLocaleDateString()) : [];
            var comments = data.length ? data.map(entry => {
                return `\nProject ID: ${entry.projectid}\nHolder: ${entry.project_holder}\nName: ${entry.project_name}\nBorrowed: ${entry.name}\nBorrowed Date: ${entry.borrowed_date}\nReturned Date: ${entry.return_date}`;
            }) : [];

            var barColors = data.length ? data.map(entry => entry.status === 'Returned' ? 'rgba(128, 128, 128, 0.5)' : 'rgba(54, 162, 235, 0.2)') : [];

            if (data.length === 0) {
                document.getElementById('no-record-message').style.display = 'block';
                document.getElementById('borrow-chart').style.display = 'none';
            } else {
                document.getElementById('no-record-message').style.display = 'none';
                document.getElementById('borrow-chart').style.display = 'block';

                if (window.borrowChart) {
                    window.borrowChart.destroy();
                }

                window.borrowChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Borrow Content',
                            data: Array(labels.length).fill(1),
                            backgroundColor: barColors,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: function (context) {
                                        return 'Status: ' + data[context[0].dataIndex].status;
                                    },
                                    label: function (context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += comments[context.dataIndex];
                                        return label.split('\n');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Borrowed Date'
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

            document.getElementById('borrow-chart-container').style.display = 'block';
        }

        function hideBorrowHistory() {
            document.getElementById('borrow-chart-container').style.display = 'none';
        }

        //finish
        function finishProject(id) {
            if (confirm("Are you sure you want to mark this project as finished?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "finish_project.php?id=" + id, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send();
            }
        }
    </script>
</body>

</html>