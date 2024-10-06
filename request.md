<?php
include "connect.php";

$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$totalItemsQuery = "SELECT COUNT(*) as total FROM request";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$sql = "SELECT * FROM request WHERE status = 0 LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/approve.css">
    <link rel="stylesheet" href="CSS/reject.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <title>Borrow</title>
</head>
<style>
    .inventory-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
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

    .item {
        position: relative;
        background-color: var(--color-white);
        border-radius: var(--border-radius-1);
        box-shadow: var(--box-shadow);
        cursor: pointer;
        transition: transform 0.3s;
    }

    .item:hover {
        transform: scale(1.05);
    }

    .item img {
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

    .item-details .approve-btn {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .item-details .approve-btn:hover {
        background-color: #388E3C;
    }

    .item-details .reject-btn {
        background-color: #db4f4f;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .item-details .reject-btn:hover {
        background-color: #b73b3b;
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
    <?php include('Temp/aside_warehouse.php'); ?>
    <main>
        <div class="header-container">
            <h1>Borrow Request</h1>
        </div><br>
        <input type="text" class="search-bar" placeholder="Search here...">

        <div class="inventory-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
        <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['project_holder']}'>
        <div class='item-name'>{$row['projectid']}</div>
        <div class='item-name'>{$row['project_holder']}</div>
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

    <!-- Approve Confirmation Overlay -->
    <div id="approve-confirmation-overlay" class="approve-confirmation-overlay">
        <div class="approve-confirmation-content">
            <h3>Are you sure to approve this request?</h3>
            <button class="btn btn-yes" onclick="submitApproveItem()">Yes</button>
            <button class="btn btn-no" onclick="cancelApproveItem()">No</button>
        </div>
    </div>

    <!-- Approve Success Message -->
    <div id="approve-success-message" class="approve-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Request Approved!</h3>
    </div>

    <!-- Reject Confirmation Overlay -->
    <div id="reject-confirmation-overlay" class="reject-confirmation-overlay">
        <div class="reject-confirmation-content">
            <h3>Are you sure you want to reject this request?</h3>
            <button class="btn btn-yes" onclick="submitRejectItem()">Yes</button>
            <button class="btn btn-no" onclick="cancelRejectItem()">No</button>
        </div>
    </div>

    <!-- Reject Success Message -->
    <div id="reject-success-message" class="reject-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Request Rejected.</h3>
    </div>

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
    <script>
        let previousRequestCount = 0;

        function fetchNewRequests() {
            $.ajax({
                url: 'check_new_requests.php',
                type: 'GET',
                success: function (response) {
                    const currentRequestCount = parseInt(response);

                    if (currentRequestCount > previousRequestCount) {
                        previousRequestCount = currentRequestCount;
                        loadRequests();
                    }
                }
            });
        }
        function loadRequests() {
            $.ajax({
                url: 'fetch_requests.php',
                type: 'GET',
                success: function (data) {
                    $('.inventory-container').html(data);
                }
            });
        }
        setInterval(fetchNewRequests, 1000);

        //item display
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_request_details.php',
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
                        <button class="approve-btn" onclick="confirmApproveRequest(${itemId})">Approve</button>
                        <button class="reject-btn" onclick="confirmRejectRequest(${itemId})">Decline</button>
                    </div>
                </div>
            `;
                    $('#item-details').html(detailsHtml).addClass('active');
                }
            });
        }

        //approve confirmation overlay
        function confirmApproveRequest(itemId) {
            document.getElementById('approve-confirmation-overlay').style.display = 'flex';
            document.getElementById('approve-confirmation-overlay').setAttribute('data-item-id', itemId);
        }

        function cancelApproveItem() {
            document.getElementById('approve-confirmation-overlay').style.display = 'none';
        }

        function submitApproveItem() {
            var itemId = document.getElementById('approve-confirmation-overlay').getAttribute('data-item-id');

            document.getElementById('approve-confirmation-overlay').style.display = 'none';

            $.ajax({
                url: 'approve_request.php',
                type: 'POST',
                data: { id: itemId },
                success: function (response) {
                    if (response === 'success') {
                        document.getElementById('approve-success-message').style.display = 'block';
                        setTimeout(function () {
                            document.getElementById('approve-success-message').style.display = 'none';
                            location.reload();
                        }, 1000);
                    } else {
                        alert('Error approving request.');
                    }
                }
            });
        }
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                document.getElementById('approve-success-message').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('approve-success-message').style.display = 'none';
                }, 1000);
            }
        }

        //reject confirmation overlay
        function confirmRejectRequest(itemId) {
            document.getElementById('reject-confirmation-overlay').style.display = 'flex';
            document.getElementById('reject-confirmation-overlay').setAttribute('data-item-id', itemId);
        }

        function cancelRejectItem() {
            document.getElementById('reject-confirmation-overlay').style.display = 'none';
        }

        function submitRejectItem() {
            var itemId = document.getElementById('reject-confirmation-overlay').getAttribute('data-item-id');

            document.getElementById('reject-confirmation-overlay').style.display = 'none';

            $.ajax({
                url: 'decline_request.php',
                type: 'POST',
                data: { id: itemId },
                success: function (response) {
                    if (response === 'success') {
                        document.getElementById('reject-success-message').style.display = 'block';
                        setTimeout(function () {
                            document.getElementById('reject-success-message').style.display = 'none';
                            location.reload();
                        }, 1000);
                    } else {
                        alert('Error declining request.');
                    }
                }
            });
        }
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                document.getElementById('reject-success-message').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('reject-success-message').style.display = 'none';
                }, 1000);
            }
        }

        //logout
        function confirmLogout() {
            document.getElementById('confirmation-overlay').style.display = 'flex';
        }

        function cancelLogout() {
            document.getElementById('confirmation-overlay').style.display = 'none';
        }

        function logout() {
            document.getElementById('confirmation-overlay').style.display = 'none';
            setTimeout(function () {
                document.getElementById('success-message').style.display = 'block';
                setTimeout(function () {
                    document.getElementById('success-message').style.display = 'none';
                    window.location.href = 'logout.php';
                }, 1000);
            }, 100);
        }

        //notification count
        function fetchNotificationCount() {
            fetch('notification_count.php')
                .then(response => response.text())
                .then(count => {
                    const badge = document.getElementById('notification-badge');
                    if (parseInt(count) > 0) {
                        badge.textContent = count;
                        badge.style.display = 'inline';

                        const length = count.length;
                        if (length === 1) {
                            badge.style.fontSize = '1rem'; 
                            badge.style.width = '15px'; 
                            badge.style.height = '15px';
                        } else if (length === 2) {
                            badge.style.fontSize = '0.8rem'; 
                            badge.style.width = '25px'; 
                            badge.style.height = '25px';
                        } else {
                            badge.style.fontSize = '0.6rem'; 
                            badge.style.width = '30px'; 
                            badge.style.height = '30px'; 
                        }
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }

        document.addEventListener('DOMContentLoaded', fetchNotificationCount);
        setInterval(fetchNotificationCount, 1000);
    </script>

</body>

</html>