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

$currentDate = date('Y-m-d');
$currentDay = date('d');

// Updated total items query to include availability check
$totalItemsQuery = "
    SELECT COUNT(*) as total
    FROM inventory
    WHERE maintenance_from <= '$currentDate'
    AND (
        DATE_FORMAT(maintenance_from, '%Y-%m-%d') <= '$currentDate' 
        AND '$currentDate' <= DATE_FORMAT(maintenance_to, '%Y-%m-%d')
    )
    AND availability = 0
";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Updated item selection query to include availability check
$sql = "
    SELECT * 
    FROM inventory 
    WHERE maintenance_from <= '$currentDate'
    AND (
        DATE_FORMAT(maintenance_from, '%Y-%m-%d') <= '$currentDate' 
        AND '$currentDate' <= DATE_FORMAT(maintenance_to, '%Y-%m-%d')
    )
    AND availability = 0
    LIMIT $offset, $itemsPerPage
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="CSS/globals.css">
    <link rel="stylesheet" href="CSS/maintenance_approve.css">
    <link rel="stylesheet" href="CSS/pospone.css">
    <link rel="stylesheet" href="CSS/loggedout.css">
    <link rel="stylesheet" href="CSS/notifications.css">
    <title>Preventive Maintenance</title>
</head>
<style>
    .chk {
        border: none;
        transition: background-color 0.3s ease;
        margin-left: 35rem;
        margin-right: .1rem;
    }

    .select-all {
        color: skyblue;
        border: none;
        transition: background-color 0.3s ease;
        font-size: small;
        margin-right: .1rem;
    }

    .action {
        text-align: right;
    }

    .maintenance-btn {
        background-color: #4CAF50;
        color: #fff;
        border: none;
        padding: 2px 4px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-left: 1rem;
        margin-right: .1rem;
    }

    .maintenance-btn:hover {
        background-color: #388E3C;
    }

    .pospone_maintenance-btn {
        background-color: #db4f4f;
        color: #fff;
        border: none;
        padding: 2px 4px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .pospone_maintenance:hover {
        background-color: #b73b3b;
    }

    .inventory-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .item {
        flex: 0 0 230px;
        background-color: var(--color-white);
        border-radius: var(--border-radius-1);
        box-shadow: var(--box-shadow);
        text-align: center;
        padding: 0.3rem;
        height: 100%;
        box-sizing: border-box;
    }

    .item:hover {
        transform: scale(1.05);
    }

    .item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-top-left-radius: var(--border-radius-1);
        border-top-right-radius: var(--border-radius-1);
    }

    .item-name {
        padding: 0.5rem;
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

    /* Responsive styles */
    @media screen and (max-width: 1200px) {
        .chk {
            margin-left: 10rem;
            margin-right: .1rem;
        }
    }

    /* Responsive styles for Tablets (iPad) */
    @media screen and (max-width: 1200px) and (min-width: 769px) {
        .chk {
            margin-left: 5rem;
            margin-right: 0.1rem;
        }

        .inventory-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Responsive styles for Mobile Phones */
    @media screen and (max-width: 768px) {
        .chk {
            margin-left: 5rem;
            margin-right: 0;
        }

        .inventory-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div>
    <?php include('Temp/aside_warehouse.php'); ?>
    <main>
        <div class="header-container">
            <h1>Preventive Maintenance</h1>
        </div><br>

        <div class="action">
            <input type='checkbox' class='chk'>
            <label class='select-all'>Select All</label>
            <button class='maintenance-btn' onclick='confirmApproveMaintenance()'>Maintenance</button>
            <!--    <button class='pospone_maintenance-btn' onclick='confirmPosponeMaintenace()'>Pospone</button> -->
        </div><br>
        <div class="inventory-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $availability_class = '';
                    if ($row['availability'] == 2) {
                        $availability_class = 'in-maintenance';
                    }
                
                    // Calculate days passed since the maintenance_from date
                    $maintenanceFromDate = new DateTime($row['maintenance_from']);
                    $currentDateTime = new DateTime(); // Get the current date and time
                
                    // Initialize daysPassed
                    $daysPassed = 0;
                
                    // Check if the current date is greater than the maintenance_from date
                    if ($currentDateTime > $maintenanceFromDate) {
                        $interval = $maintenanceFromDate->diff($currentDateTime);
                        $daysPassed = $interval->days; // Get the difference in days
                    }
                
                    // Prepare the days ago text if applicable
                    $daysAgoText = '';
                    if ($daysPassed > 0) {
                        $daysAgoText = "<div style='color: red; margin-bottom: .4rem; font-size: 10px;'>$daysPassed days ago</div>";
                    }
                
                    echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                        <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['name']}'>
                        <div class='availability-circle {$availability_class}'></div>
                        <div class='item-name'>{$row['name']}</div>
                        <div class='item-name'>{$row['built_num']}</div>
                        $daysAgoText
                        <input type='checkbox'>
                    </div>";
                }
            } else {
                echo "<p>No items found for maintenance</p>";
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

    <!-- Maintenance Confirmation Overlay -->
    <div id="maintenance-confirmation-overlay" class="maintenance-confirmation-overlay" style="display: none;">
        <div class="maintenance-confirmation-content">
            <h3>Are you sure to maintenance this item?</h3>
            <button class="btn btn-yes">Yes</button>
            <button class="btn btn-no">No</button>
        </div>
    </div>

    <!-- Maintenance Success Message -->
    <div id="maintenance-success-message" class="maintenance-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Maintenance Approved!</h3>
    </div>

    <!-- Pospone Confirmation Overlay 
    <div id="pospone-confirmation-overlay" class="pospone-confirmation-overlay">
        <div class="pospone-confirmation-content">
            <h3>Are you sure to pospone the maintenance?</h3>
            <button class="btn btn-yes" onclick="submitRejectItem()">Yes</button>
            <button class="btn btn-no" onclick="cancelRejectItem()">No</button>
        </div>
    </div> -->

    <!-- Pospone Success Message 
    <div id="pospone-success-message" class="pospone-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Maintenance Pospone.</h3>
    </div>  -->

    <?php include('Temp/warehouse_profile.php'); ?>
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
        //item prevent
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_inventory_details.php',
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
                }
            });
        }

        //approve request
        function confirmApproveMaintenance() {
            const selectedItems = document.querySelectorAll('.item input[type="checkbox"]:checked');
            if (selectedItems.length === 0) {
                alert('No items selected.');
                return;
            }

            const itemIds = Array.from(selectedItems).map(checkbox => checkbox.closest('.item').getAttribute('data-id'));

            document.getElementById('maintenance-confirmation-overlay').style.display = 'flex';

            document.querySelector('#maintenance-confirmation-overlay .btn-yes').onclick = () => submitApproveItems(itemIds);
            document.querySelector('#maintenance-confirmation-overlay .btn-no').onclick = cancelApproveItem;
        }

        function submitApproveItems(itemIds) {
            fetch('approve_maintenance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: itemIds })
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        document.getElementById('maintenance-confirmation-overlay').style.display = 'none';

                        document.getElementById('maintenance-success-message').style.display = 'block';

                        setTimeout(() => {
                            document.getElementById('maintenance-success-message').style.display = 'none';
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Error: ' + result);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function cancelApproveItem() {
            document.getElementById('maintenance-confirmation-overlay').style.display = 'none';
        }

        //decline request
        //function confirmPosponeMaintenace() {
        //    const selectedItems = document.querySelectorAll('.item input[type="checkbox"]:checked');
        //    if (selectedItems.length === 0) {
        //       alert('No items selected.');
        //        return;
        //    }

        //    const itemIds = Array.from(selectedItems).map(checkbox => checkbox.closest('.item').getAttribute('data-id'));

        //    document.getElementById('pospone-confirmation-overlay').style.display = 'flex';

        //    document.querySelector('#pospone-confirmation-overlay .btn-yes').onclick = () => submitRejectItems(itemIds);
        //    document.querySelector('#pospone-confirmation-overlay .btn-no').onclick = cancelRejectItem;
        //}

        //function submitRejectItems(itemIds) {
        //    fetch('decline_pospone.php', {
        //        method: 'POST',
        //        headers: { 'Content-Type': 'application/json' },
        //        body: JSON.stringify({ ids: itemIds })
        //    })
        //        .then(response => response.text())
        //        .then(result => {
        //            if (result === 'success') {
        //                document.getElementById('pospone-confirmation-overlay').style.display = 'none'; 
        //                document.getElementById('pospone-success-message').style.display = 'block';
        //                setTimeout(() => {
        //                    document.getElementById('pospone-success-message').style.display = 'none';
        //                    window.location.reload();
        //                }, 1000);
        //            } else {
        //                alert('Error: ' + result);
        //            }
        //        })
        //        .catch(error => console.error('Error:', error));
        //}

        //function cancelRejectItem() {
        //    document.getElementById('pospone-confirmation-overlay').style.display = 'none';
        //}

        // Select all
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.querySelector('.action .chk');

            selectAllCheckbox.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.inventory-container .item input[type="checkbox"]');

                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });
    </script>

    </body>

</html>