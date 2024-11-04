<?php

include("connection.php");

$email = $_POST['email'];

$selectQry = "SELECT * FROM tbl_users WHERE email = '$email'";
$result = $connectDB->query($selectQry);

if($result->num_rows > 0){
    header("location: changePassword.php?email=$email");
}else {
    $msg = "No email found";
    header("location: forgotpassword.php?e=$msg");
    exit();
}

?>