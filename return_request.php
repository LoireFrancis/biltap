<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
include "connect.php";

$itemsPerPage = 2;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$totalItemsQuery = "SELECT COUNT(*) as total FROM return_request";
$totalItemsResult = $conn->query($totalItemsQuery);
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$projects = $conn->query("SELECT DISTINCT project_name FROM return_request WHERE status = 0 LIMIT $offset, $itemsPerPage");
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
    <link rel="stylesheet" href="CSS/notifications.css">
    <title>Return Borrow</title>
</head>
<style>
    .header-container {
        padding-bottom: 5px;
    }

    .search-bar {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #ccc;
        border-radius: var(--border-radius-1);
        background-color: #fff;
        color: #333;
        font-size: 1rem;
    }

    .search-bar::placeholder {
        color: #666;
    }

    .project-container {
        margin-bottom: 1rem;
    }

    .project-title {
        font-size: .9rem;
        margin-bottom: .8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        white-space: nowrap;
    }

    .project-title-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .inventory-scrollable {
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        gap: 1rem;
        height: 220px;
        white-space: nowrap;
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
        padding-bottom: 1rem;
        text-align: right;
        margin-top: 1rem;
    }

    .approve-btn {
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

    .approve-btn:hover {
        background-color: #388E3C;
    }

    .reject-btn {
        background-color: #db4f4f;
        color: #fff;
        border: none;
        padding: 2px 4px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .reject-btn:hover {
        background-color: #b73b3b;
    }

    .pagination {
        display: flex;
        justify-content: center;
    }

    .pagination a {
        text-decoration: none;
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        margin: 0 0.1rem;
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

    .show-images {
        background-color: #2196F3;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .show-images:hover {
        background-color: #1976D2;
    }

    #images-container {
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

    #images-content {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 0 1rem;
    }

    #images-content img {
        width: 300px;
        height: 200px;
        object-fit: fill;
        border-radius: 5px;
        margin: 0.5rem;
    }

    /* Enlarged Image Display */
    #enlarged-image-container {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1001;
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        background-color: rgba(255, 255, 255, 0.95);
        padding: 10px;
        overflow: hidden;
    }

    /* The enlarged image itself */
    #enlarged-image-container img {
        max-width: 100%;
        max-height: 100%;
        height: auto;
        object-fit: contain;
        border-radius: 5px;
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

<body>
    <?php include('Temp/aside_warehouse.php'); ?>
    <main>
        <div class="header-container">
            <h1>Return Request</h1>
        </div>

        <div class="action">
            <button class='approve-btn' onclick='confirmApproveRequest()'>Approve</button>
            <button class='reject-btn' onclick='confirmMaintenanceRequest()'>Maintenance</button>
        </div>

        <?php
        if ($projects->num_rows > 0) {
            while ($project = $projects->fetch_assoc()) {
                $projectName = $project['project_name'];
                echo "<div class='project-container'>
                        <div class='project-title'>
                            <span>Project: $projectName ></span>
                            <div class='project-title-controls'>
                                <input type='checkbox' class='chk'>
                                <label class='select-all'>Select All</label>
                            </div>
                        </div>
                        <div class='inventory-scrollable'>";

                $sql = "SELECT * FROM return_request WHERE project_name = '$projectName' AND status = 0";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='item' data-id='{$row['id']}' onclick='showDetails(this)'>
                                <img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='{$row['project_holder']}'>
                                <div class='item-name'>{$row['name']}</div>
                                <input type='checkbox'>
                            </div>";
                    }
                } else {
                    echo "<p>No items found for this project</p>";
                }

                echo "</div></div>";
            }
        } else {
            echo "<p>No projects found</p>";
        }
        ?>

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

        <!-- Container for the show images -->
        <div id="images-container">
            <button onclick="hideImages()"
                style="background: #db4f4f; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">X</button>
            <div id="images-content">
                <!-- Images will be appended here -->
                <img src="data:image/jpeg;base64,YOUR_IMAGE_DATA" alt="Image Description">
            </div>
        </div>

        <!-- Enlarged image container -->
        <div id="enlarged-image-container">
            <img id="enlarged-image" src="" alt="Enlarged Image">
        </div>
    </main>

    <!-- Approve Confirmation Overlay -->
    <div id="approve-confirmation-overlay" class="approve-confirmation-overlay">
        <div class="approve-confirmation-content">
            <h3>Are you sure to approve the request?</h3>
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
            <h3>Are you sure to maintenance this return?</h3>
            <button class="btn btn-yes" onclick="submitMaintenanceItem()">Yes</button>
            <button class="btn btn-no" onclick="cancelMaintenanceItem()">No</button>
        </div>
    </div>

    <!-- Reject Success Message -->
    <div id="reject-success-message" class="reject-success-message" style="display: none;">
        <span class="material-icons-sharp success-icon">check_circle</span>
        <h3>Request Maintenance.</h3>
    </div>

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
        // Select all
        document.addEventListener('DOMContentLoaded', function () {
            let selectAllCheckboxes = document.querySelectorAll('.chk');

            selectAllCheckboxes.forEach(function (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    let projectContainer = this.closest('.project-container');
                    let checkboxes = projectContainer.querySelectorAll('.item input[type="checkbox"]');

                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            });
        });

        //approve return
        function confirmApproveRequest() {
            const selectedItems = document.querySelectorAll('.item input[type="checkbox"]:checked');
            if (selectedItems.length === 0) {
                alert('No items selected.');
                return;
            }
            const itemIds = Array.from(selectedItems).map(checkbox => checkbox.closest('.item').getAttribute('data-id'));

            document.getElementById('approve-confirmation-overlay').style.display = 'flex';

            document.querySelector('#approve-confirmation-overlay .btn-yes').onclick = () => submitApproveItems(itemIds);
            document.querySelector('#approve-confirmation-overlay .btn-no').onclick = cancelApproveItem;
        }
        function submitApproveItems(itemIds) {
            fetch('approve_return.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: itemIds })
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        document.getElementById('approve-confirmation-overlay').style.display = 'none';
                        document.getElementById('approve-success-message').style.display = 'block';
                        setTimeout(() => {
                            document.getElementById('approve-success-message').style.display = 'none';
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Error: ' + result);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function cancelApproveItem() {
            document.getElementById('approve-confirmation-overlay').style.display = 'none';
        }


        //return_maintenance
        function confirmMaintenanceRequest() {
            const selectedItems = document.querySelectorAll('.item input[type="checkbox"]:checked');
            if (selectedItems.length === 0) {
                alert('No items selected.');
                return;
            }
            const itemIds = Array.from(selectedItems).map(checkbox => checkbox.closest('.item').getAttribute('data-id'));

            document.getElementById('reject-confirmation-overlay').style.display = 'flex';

            // Set up button click events
            document.querySelector('#reject-confirmation-overlay .btn-yes').onclick = () => submitMaintenanceItem(itemIds);
        }

        // Function to submit maintenance item
        function submitMaintenanceItem(itemIds) {
            fetch('maintenance_return.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: itemIds })
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        document.getElementById('reject-confirmation-overlay').style.display = 'none';
                        document.getElementById('reject-success-message').style.display = 'block';
                        setTimeout(() => {
                            document.getElementById('reject-success-message').style.display = 'none';
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert('Error: ' + result);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Function to cancel maintenance action
        function cancelMaintenanceItem() {
            document.getElementById('reject-confirmation-overlay').style.display = 'none';
        }

        //item display
        function showDetails(element) {
            var itemId = element.getAttribute('data-id');
            $.ajax({
                url: 'get_return_details.php',
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
                        <button class="show-images" data-id="${itemId}">Images</button>
                    </div>
                </div>
            `;
                    $('#item-details').html(detailsHtml).addClass('active');
                }
            });
        }

        //show image
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('show-images')) {
                const itemId = event.target.getAttribute('data-id');
                showImages(itemId);
            }
        });

        // Function to fetch and display images
        function showImages(itemId) {
            $.ajax({
                url: 'get_return_image.php',
                type: 'GET',
                data: { id: itemId },
                success: function (data) {
                    const imagesData = JSON.parse(data);
                    let imagesHtml = '';

                    if (imagesData.length > 0) {
                        imagesData.forEach(function (imageObj) {
                            imagesHtml += `
                            <div class="image-wrapper">
                                <img src="data:image/jpeg;base64,${imageObj.images}" alt="Image for Project ${imageObj.project_id}">
                            </div>
                        `;
                        });
                    } else {
                        imagesHtml = '<p>No images found for this request.</p>';
                    }

                    document.getElementById('images-content').innerHTML = imagesHtml;
                    document.getElementById('images-container').style.display = 'block'; // Show image container
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to hide the image container
        function hideImages() {
            document.getElementById('images-container').style.display = 'none';
        }


        // Function to display the enlarged image
        document.addEventListener('mouseover', function (event) {
            if (event.target.tagName === 'IMG' && event.target.closest('.image-wrapper')) {
                const imageSrc = event.target.src;
                const enlargedImageContainer = document.getElementById('enlarged-image-container');
                const enlargedImage = document.getElementById('enlarged-image');

                enlargedImage.src = imageSrc; // Set the source of the enlarged image
                enlargedImageContainer.style.display = 'block'; // Show the enlarged image container
            }
        });

        // Function to hide the enlarged image when the user clicks away or hovers out
        document.addEventListener('mouseout', function (event) {
            if (event.target.tagName === 'IMG' && event.target.closest('.image-wrapper')) {
                const enlargedImageContainer = document.getElementById('enlarged-image-container');
                enlargedImageContainer.style.display = 'none';
            }
        });
    </script>

</body>

</html>