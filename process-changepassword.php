<?php 
session_start();
include("connection.php");


$email = $_POST['email'];
$password = $_POST['password'];


$newPassword = password_hash($password, PASSWORD_BCRYPT);

$updateQry = "UPDATE tbl_users SET `password` = '$newPassword' WHERE `email` = '$email'";
        
if($result = $connectDB->query($updateQry)){
    $msg = "Password Updated";
    header("location: index.php?e=$msg");
    exit();
}else{
    $msg = "Failed to update";
    header("location: index.php?e=$msg");
    exit();
}
?>