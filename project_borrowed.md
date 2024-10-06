<?php
include "connect.php";

$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Get the projectid from the URL
$projectId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Adjust total items query to filter by projectid
$totalItemsQuery = "SELECT COUNT(*) as total FROM borrowed WHERE projectid = $itemId";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Adjust main query to filter by projectid
$sql = "SELECT * FROM borrowed WHERE projectid = $itemId AND status = 0 LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);
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
    <title>Project Borrowed</title>
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
        padding: 1rem;
        margin-top: 1rem;
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

    .delete-icon {
        color: red;
    }

    .custom-blue {
        color: #4d9aeb;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .custom-red {
        color: #db4f4f;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
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
</style>

<body>
    <?php include('Temp/aside.php'); ?>
    <main>
        <div class="header-container">
            <h1>Project Borrowed</h1>
            <button class="back-button" onclick="location.href='projects.php';">
                <span class="material-icons-sharp">arrow_back</span>
            </button>
        </div><br>
        <input type="text" class="search-bar" placeholder="Search here...">

        <div class="inventory-container">
            <?php
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['name']}'>
                <div class='item-name'>{$row['name']}</div>
                <div class='item-name'>{$row['project_name']}</div>
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
    <script src="JS/inventory.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/notifications.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        //get details
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_borrowed_details.php',
                type: 'GET',
                data: {
                    id: itemId
                },
                success: function (response) {
                    $('#item-details').html(response).addClass('active');
                }
            });
        }
    </script>

</body>

</html>