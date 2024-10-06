<?php
include "connect.php"; 

$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$position = mysqli_real_escape_string($conn, $_POST['position']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$role = mysqli_real_escape_string($conn, $_POST['role']);

$query = "INSERT INTO `user` (`fullname`, `position`, `email`, `passw`, `users`) 
          VALUES ('$fullname', '$position', '$email', '$password', '$role')";

if (mysqli_query($conn, $query)) {
    echo "Account added successfully!";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
