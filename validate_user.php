<?php
include("connection.php");
date_default_timezone_set("Asia/Manila");
$date = date("Y-m-d H:i:s");

ob_start(); session_start(); 

$username = $_POST['username'];
$pass = $_POST['password'];

$selectQry = "SELECT * from tbl_users WHERE username = '$username'";
$result = $connectDB->query($selectQry);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        if(password_verify($pass, $row['password'])){

            $updateQry = "UPDATE tbl_users SET `last_login` = '$date' WHERE `username` = '$username'";

            if($result = $connectDB->query($updateQry)){

                $_SESSION['session_user'] = $username;
                $_SESSION['session_user_id'] = $row['UID'];
    
                if($row['username'] == "admin"){
                    header("location: adminhome.php");
                }else{
                    header("location: loader.php");
                }
            }

        }else{
            $msg = "Wrong credentials. Try again!";
            header("location: index.php?e=$msg");
        }
    }
}else{
    $msg = "Wrong credentials. Try again!";
    header("location: index.php?e=$msg");
}
?>