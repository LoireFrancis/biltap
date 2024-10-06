<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND BINARY passw = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $row['users'];

        echo "<script>localStorage.removeItem('activeLink');</script>";

        echo "<script>localStorage.setItem('userRole', '{$row['users']}');</script>";

        if ($row['users'] == 'admin') {
            echo "<script>window.location.href='dashboard.php';</script>";
            exit(); 
        } elseif ($row['users'] == 'warehouse') {
            echo "<script>window.location.href='inventory.php';</script>";
            exit();
        } else {
            echo "<script>window.location.href='index.php?error=Access Denied.';</script>";
            exit();
        }
    } else {
        echo "<script>window.location.href='index.php?error=Invalid Email or Password.';</script>";
        exit();
    }
}
?>
