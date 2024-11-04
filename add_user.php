<?php

include("connection.php");
date_default_timezone_set("Asia/Manila");

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$dateToday = date('Y-m-d H:i:s');

$password = password_hash($password, PASSWORD_BCRYPT);

$selectQry = "SELECT * FROM tbl_users WHERE username = '$username' OR email = '$email'";
$results = $connectDB->query($selectQry);
$data = $results->fetch_assoc();

if ($data) {
    if ($data['username'] == $username && $data['email'] == $email) {
        $msg = "Username and email are already taken!";
    } elseif ($data['username'] == $username) {
        $msg = "Username is already taken!";
    } elseif ($data['email'] == $email) {
        $msg = "Email is already taken!";
    }
    header("location: index.php?e=$msg");
    exit();
} else {
    $insertQry = "INSERT INTO `tbl_users`(`username`,`email`,`password`,`date_added`,`last_login`)
                  VALUES('$username','$email','$password','$dateToday','$dateToday')";

    if ($connectDB->query($insertQry)) {
        $msg = "Successfully created";
    } else {
        $msg = "Failed to create";
    }
    header("location: index.php?e=$msg");
}

?>