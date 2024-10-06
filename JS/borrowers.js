const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');

menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

function confirmLogout() {
    var confirmLogout = confirm("Are you sure you want to logout?");
    if (confirmLogout) {
        window.location.href = "logout.php";
    }
}

//Aside
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".sidebar-link");

    // Check for saved active link in localStorage
    const savedActiveLink = localStorage.getItem("activeLink");

    if (savedActiveLink) {
        const activeLink = document.querySelector(`.sidebar-link[data-target="${savedActiveLink}"]`);
        if (activeLink) {
            activeLink.classList.add("active");
        }
    } else {
        // Default to dashboard if no saved state
        const defaultLink = document.querySelector('.sidebar-link[data-target="dashboard"]');
        if (defaultLink) {
            defaultLink.classList.add("active");
        }
    }

    links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            // Remove active class from all links
            links.forEach(link => {
                link.classList.remove("active");
            });

            // Add active class to the clicked link
            this.classList.add("active");

            // Save the active link to localStorage
            const target = this.getAttribute("data-target");
            localStorage.setItem("activeLink", target);

            // Optionally, redirect to the href of the clicked link
            window.location.href = this.getAttribute("href");
        });
    });
});







