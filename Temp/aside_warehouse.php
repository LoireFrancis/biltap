<div class="container">
    <!-- Sidebar Section -->
    <aside>
        <div class="toggle">
            <div class="logo">
                <img src="Picture/biltapLOGO.png">
                <h2>Biltap<span class="danger">Creations</span></h2>
            </div>
            <div class="close" id="close-btn">
                <span class="material-icons-sharp">
                    close
                </span>
            </div>
        </div><br><br>

        <div class="sidebar">
            <a href="warehouse_profile.php" class="sidebar-link" data-target="profile">
                <span class="material-icons-sharp">
                    person
                </span>
                <h3>Profile</h3>
            </a>
            <a href="inventory.php" class="sidebar-link" data-target="inventory">
                <span class="material-icons-sharp">
                    inventory
                </span>
                <h3>Equipment
                    Machine
                </h3>
            </a>
            <a href="prevent.php" class="sidebar-link" data-target="prevent">
                <span class="material-icons-sharp">
                    insights
                </span>
                <h3>Preventive
                    Maintenance</h3>
                <div class="notifications-container">
                    <span id="notifications-badge" class="notifications-badge"></span>
                </div>
            </a>
            <a href="maintenance.php" class="sidebar-link" data-target="maintenance">
                <span class="material-icons-sharp">
                    build
                </span>
                <h3>Current
                    Maintenance</h3>
            </a>
            <a href="borrow.php" class="sidebar-link" data-target="borrow">
                <span class="material-icons-sharp">
                    request_quote
                </span>
                <h3>Current Borrow</h3>
            </a>
            <a href="request.php" class="sidebar-link" data-target="borrow request">
                <span class="material-icons-sharp">assignment</span>
                <h3>Borrow Requests</h3>
                <div class="notification-container">
                    <span id="notification-badge" class="notification-badge"></span>
                </div>
            </a>
            <a href="return_request.php" class="sidebar-link" data-target="return request">
                <span class="material-icons-sharp">assignment</span>
                <h3>Return Requests</h3>
                <div class="notificationss-container">
                    <span id="notificationss-badge" class="notificationss-badge"></span>
                </div>
            </a>
            <a href="return_emergency.php" class="sidebar-link" data-target="return emergency">
                <span class="material-icons-sharp">warning</span>
                <h3>Borrowed
                    Maintenance
                </h3>
                <div class="notificationsss-container">
                    <span id="notificationsss-badge" class="notificationsss-badge"></span>
                </div>
            </a>
            <a class="btn btn-secondary" onclick="confirmLogout()">
                <span class="material-icons-sharp">
                    logout
                </span>
                <h3>Logout</h3>
            </a>
        </div>
    </aside>
    <!-- End of Sidebar Section -->

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