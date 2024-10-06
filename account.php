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

$totalItemsQuery = "SELECT COUNT(*) as total FROM user";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$sql = "SELECT * FROM user LIMIT $offset, $itemsPerPage";
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
    <link rel="stylesheet" href="CSS/delete.css">
    <title>User Accounts</title>
    <style>
        .inventory-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .item-details .edit-btn {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .item-details .edit-btn:hover {
            background-color: #388E3C;
        }

        .item-details .delete-btn {
            background-color: #db4f4f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .item-details .delete-btn:hover {
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

        .edit-btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 4px 8px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 10px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }

        .delete-btn {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 4px 8px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 10px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-btn:hover {
            background-color: #da190b;
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
</head>

<body>
    <?php include('Temp/aside.php'); ?>
    <main>
        <div class="header-container">
            <h1>Account Management</h1>
            <button class="add-button" onclick="location.href='add_account.php';">New Account</button>
        </div><br>
        <input type="text" class="search-bar" placeholder="Search here...">

        <div class="inventory-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                        <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['fullname']}'>
                        <div class='item-name'>{$row['fullname']}</div>
                        <div class='item-name'>{$row['position']}</div>
                    </div>";
                }
            } else {
                echo "<p>No accounts found</p>";
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

    <!-- Confirmation Overlay -->
    <div id="delete-confirmation-overlay" class="delete-confirmation-overlay">
        <div class="delete-confirmation-content">
            <h3>Are you sure to delete this account?</h3>
            <button class="btn btn-yes" onclick="submitDeleteAccount()">Yes</button>
            <button class="btn btn-no" onclick="cancelDeleteAccount()">No</button>
        </div>
    </div>

    <!-- Success Message -->
    <div id="delete-success-message" class="delete-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Account Deleted Successfully</h3>
    </div>

    <?php include('Temp/admin_profile.php'); ?>

    <br><br><br>
    <?php include('Temp/sidelogo.php'); ?>
    <br>

    <div class="item-details" id="item-details" style="text-align: center;">Details display here...</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="JS/global.js"></script>
    <script src="JS/loggedout.js"></script>
    <script>
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_client_inv_details.php',
                type: 'GET',
                data: { id: itemId },
                success: function (response) {
                    var detailsHtml = `
            <div class="details-content">
                ${response}
                <div class="details-actions">
                    <button class="edit-btn" onclick="editItem(${itemId})">Update</button>
                    <button class="delete-btn" onclick="deleteItem(${itemId})">Delete</button>
                </div>
            </div>
            `;
                    $('#item-details').html(detailsHtml).addClass('active');
                }
            });
        }

        function editItem(id) {
            window.location.href = `update_account.php?id=${id}`;
        }


        function deleteItem(id) {
            document.getElementById('delete-confirmation-overlay').style.display = 'flex';

            // Ensure the correct ID is passed when the 'Yes' button is clicked
            document.querySelector('.btn-yes').onclick = function () {
                submitDeleteAccount(id);
            };
        }

        //delete account
        let deleteId; 

        function deleteItem(id) {
            deleteId = id; 
            document.getElementById('delete-confirmation-overlay').style.display = 'flex';

            document.querySelector('.btn-yes').onclick = function () {
                submitDeleteAccount();
            };
        }

        function submitDeleteAccount() {
            document.getElementById('delete-confirmation-overlay').style.display = 'none';

            $.ajax({
                url: 'delete_account.php',
                type: 'GET',
                data: { id: deleteId }, 
                success: function (response) {
                    if (response === 'success') {
                        document.getElementById('delete-success-message').style.display = 'block';
                        setTimeout(function () {
                            document.getElementById('delete-success-message').style.display = 'none';
                            location.reload();
                        }, 1000);
                    } else {
                        alert('Error: ' + response);
                    }
                },
                error: function (xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        }


        function cancelDeleteAccount() {
            document.getElementById('delete-confirmation-overlay').style.display = 'none';
        }

        //search
        $(document).ready(function () {
            $('.search-bar').on('keyup', function () {
                var searchTerm = $(this).val().trim();
                if (searchTerm.length > 1) {
                    $.ajax({
                        url: 'search_client.php',
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
                        url: 'search_client.php',
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