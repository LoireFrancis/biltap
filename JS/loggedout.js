//logout
function confirmLogout() {
  document.getElementById("confirmation-overlay").style.display = "flex";
}

function cancelLogout() {
  document.getElementById("confirmation-overlay").style.display = "none";
}

function logout() {
  document.getElementById("confirmation-overlay").style.display = "none";
  setTimeout(function () {
    document.getElementById("success-message").style.display = "block";
    setTimeout(function () {
      document.getElementById("success-message").style.display = "none";
      window.location.href = "logout.php";
    }, 1000);
  }, 100);
}