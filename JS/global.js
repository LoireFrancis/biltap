const sideMenu = document.querySelector("aside");
const menuBtn = document.getElementById("menu-btn");
const closeBtn = document.getElementById("close-btn");

menuBtn.addEventListener("click", () => {
  sideMenu.style.display = "block";
});

closeBtn.addEventListener("click", () => {
  sideMenu.style.display = "none";
});

function confirmLogout() {
  var confirmLogout = confirm("Are you sure you want to logout?");
  if (confirmLogout) {
    window.location.href = "logout.php";
  }
}

//darkmode
document.addEventListener("DOMContentLoaded", () => {
  const darkMode = document.querySelector(".dark-mode");
  const body = document.body;

  // Check for saved user preference
  const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";

  if (darkModeEnabled) {
    body.classList.add("dark-mode-variables");
    darkMode.querySelector("span:nth-child(1)").classList.remove("active");
    darkMode.querySelector("span:nth-child(2)").classList.add("active");
  }

  darkMode.addEventListener("click", () => {
    body.classList.toggle("dark-mode-variables");
    darkMode.querySelector("span:nth-child(1)").classList.toggle("active");
    darkMode.querySelector("span:nth-child(2)").classList.toggle("active");

    // Save user preference
    if (body.classList.contains("dark-mode-variables")) {
      localStorage.setItem("darkMode", "enabled");
    } else {
      localStorage.removeItem("darkMode");
    }
  });
});

// Aside
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll(".sidebar-link");

  // Check for saved active link in localStorage
  const savedActiveLink = localStorage.getItem("activeLink");
  const userRole = localStorage.getItem("userRole"); 

  links.forEach((link) => {
    link.addEventListener("click", function (event) {
      event.preventDefault();

      // Remove active class from all links
      links.forEach((link) => {
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

  if (savedActiveLink) {
    const activeLink = document.querySelector(
      `.sidebar-link[data-target="${savedActiveLink}"]`
    );
    if (activeLink) {
      activeLink.classList.add("active");
    }
  } else {

    if (userRole === 'admin') {
      const defaultAdminLink = document.querySelector(
        '.sidebar-link[data-target="dashboard"]'
      );
      if (defaultAdminLink) {
        defaultAdminLink.classList.add("active");
      }
    } else if (userRole === 'warehouse') {
      const defaultWarehouseLink = document.querySelector(
        '.sidebar-link[data-target="inventory"]'
      );
      if (defaultWarehouseLink) {
        defaultWarehouseLink.classList.add("active");
      }
    }
  }
});

//remimder
const addReminderButton = document.getElementById("addReminder");
const reminderList = document.getElementById("reminderList");

// Save reminders to localStorage
function saveReminders() {
  const reminders = reminderList.querySelectorAll(".reminder-item");
  const remindersData = [];

  reminders.forEach((reminderItem) => {
    const reminderText = reminderItem.querySelector(".reminder-text").value;
    const reminderDateTime = reminderItem.querySelector(".reminder-datetime").value;
    remindersData.push({ text: reminderText, datetime: reminderDateTime });
  });

  localStorage.setItem("reminders", JSON.stringify(remindersData));
}

// Load reminders from localStorage
function loadReminders() {
  const remindersData = JSON.parse(localStorage.getItem("reminders"));

  if (remindersData) {
    remindersData.forEach((reminder) => {
      addReminder(reminder.text, reminder.datetime);
    });
  }
}

// Add a reminder item
function addReminder(text = "", datetime = "") {
  const reminderItem = document.createElement("div");
  reminderItem.classList.add("notification", "reminder-item");

  reminderItem.innerHTML = `
      <textarea class="reminder-text" placeholder="Enter reminder text">${text}</textarea>
      <div class="reminder-footer">
          <input type="datetime-local" class="reminder-datetime" value="${datetime}">
          <span class="material-icons-sharp delete-reminder">delete</span>
      </div>
  `;

  reminderList.appendChild(reminderItem);

  const deleteButton = reminderItem.querySelector(".delete-reminder");
  deleteButton.addEventListener("click", () => {
      if (confirm("Are you sure you want to delete this reminder?")) {
          reminderItem.remove();
          saveReminders(); // Save reminders after deletion
      }
  });
}

// Load reminders when the page loads
window.addEventListener("load", loadReminders);

// Add reminder button click event
addReminderButton.addEventListener("click", () => {
  addReminder();
});

// Save reminders when the page is unloaded (e.g., refreshed or closed)
window.addEventListener("beforeunload", saveReminders);

// Call saveReminders when deleting a reminder
reminderList.addEventListener("click", function (event) {
  if (event.target.classList.contains("delete-reminder")) {
    const reminderItem = event.target.closest(".reminder-item");
    reminderItem.remove(); // Remove reminder from DOM
    saveReminders(); // Update localStorage after deletion
  }
});



