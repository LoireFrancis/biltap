//notification prevent count
function fetchNotificationsPrevent() {
  fetch("notification_prevent.php")
    .then((response) => response.text())
    .then((count) => {
      const badge = document.getElementById("notifications-badge");
      if (parseInt(count) > 0) {
        badge.textContent = count;
        badge.style.display = "inline";

        const length = count.length;
        if (length === 1) {
          badge.style.fontSize = "1rem";
          badge.style.width = "15px";
          badge.style.height = "15px";
        } else if (length === 2) {
          badge.style.fontSize = "1rem";
          badge.style.width = "25px";
          badge.style.height = "25px";
        } else {
          badge.style.fontSize = "1rem";
          badge.style.width = "30px";
          badge.style.height = "30px";
        }
      } else {
        badge.style.display = "none";
      }
    })
    .catch((error) =>
      console.error("Error fetching notification count:", error)
    );
}

document.addEventListener("DOMContentLoaded", fetchNotificationsPrevent);
setInterval(fetchNotificationsPrevent, 1000);

//notification count for request
function fetchNotificationCount() {
  fetch("notification_count.php")
    .then((response) => response.text())
    .then((count) => {
      const badge = document.getElementById("notification-badge");
      if (parseInt(count) > 0) {
        badge.textContent = count;
        badge.style.display = "inline";

        const length = count.length;
        if (length === 1) {
          badge.style.fontSize = "1rem";
          badge.style.width = "15px";
          badge.style.height = "15px";
        } else if (length === 2) {
          badge.style.fontSize = "1rem";
          badge.style.width = "25px";
          badge.style.height = "25px";
        } else {
          badge.style.fontSize = "1rem";
          badge.style.width = "30px";
          badge.style.height = "30px";
        }
      } else {
        badge.style.display = "none";
      }
    })
    .catch((error) =>
      console.error("Error fetching notification count:", error)
    );
}

document.addEventListener("DOMContentLoaded", fetchNotificationCount);
setInterval(fetchNotificationCount, 1000);

//notification count for return request
function fetchNotificationReturn() {
  fetch("notification_return.php")
    .then((response) => response.text())
    .then((count) => {
      const badge = document.getElementById("notificationss-badge");
      if (parseInt(count) > 0) {
        badge.textContent = count;
        badge.style.display = "inline";

        const length = count.length;
        if (length === 1) {
          badge.style.fontSize = "1rem";
          badge.style.width = "15px";
          badge.style.height = "15px";
        } else if (length === 2) {
          badge.style.fontSize = "1rem";
          badge.style.width = "25px";
          badge.style.height = "25px";
        } else {
          badge.style.fontSize = "1rem";
          badge.style.width = "30px";
          badge.style.height = "30px";
        }
      } else {
        badge.style.display = "none";
      }
    })
    .catch((error) =>
      console.error("Error fetching notification count:", error)
    );
}

document.addEventListener("DOMContentLoaded", fetchNotificationReturn);
setInterval(fetchNotificationReturn, 1000);

//notification count for emergency request
function fetchNotificationEmergency() {
  fetch("notification_emergency.php")
    .then((response) => response.text())
    .then((count) => {
      const badge = document.getElementById("notificationsss-badge");
      if (parseInt(count) > 0) {
        badge.textContent = count;
        badge.style.display = "inline";

        const length = count.length;
        if (length === 1) {
          badge.style.fontSize = "1rem";
          badge.style.width = "15px";
          badge.style.height = "15px";
        } else if (length === 2) {
          badge.style.fontSize = "1rem";
          badge.style.width = "25px";
          badge.style.height = "25px";
        } else {
          badge.style.fontSize = "1rem";
          badge.style.width = "30px";
          badge.style.height = "30px";
        }
      } else {
        badge.style.display = "none";
      }
    })
    .catch((error) =>
      console.error("Error fetching notification count:", error)
    );
}

document.addEventListener("DOMContentLoaded", fetchNotificationEmergency);
setInterval(fetchNotificationEmergency, 1000);
