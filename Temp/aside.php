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
            <a href="dashboard.php" class="sidebar-link" data-target="dashboard">
                <span class="material-icons-sharp">
                    dashboard
                </span>
                <h3>Dashboard</h3>
            </a>
            <a href="projects.php" class="sidebar-link" data-target="project">
                <span class="material-icons-sharp">
                    folder_open
                </span>
                <h3>Projects</h3>
            </a>
            <a href="account.php" class="sidebar-link" data-target="account">
                <span class="material-icons-sharp">
                    account_circle
                </span>
                <h3>Accounts</h3>
            </a>
            <a href="websites.php" class="sidebar-link" data-target="website">
                <span class="material-icons-sharp">
                    web
                </span>
                <h3>Website</h3>
            </a>
            <a href="report.php" class="sidebar-link" data-target="report">
                <span class="material-icons-sharp">
                    report_gmailerrorred
                </span>
                <h3>Reports</h3>
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