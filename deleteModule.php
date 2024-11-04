<?php
include("connection.php"); 

if(isset($_GET['mid'])){
    $MID = $_GET['mid'];

    $deleteQuery = "DELETE from `tbl_modules` WHERE MID=$MID";

    if($connectDB->query($deleteQuery)){
        $msg = "Successfully Deleted";
        header("location:adminModule.php?e=$msg");
    }else{
        $msg = "Failed";
        header("location:adminModule.php?e=$msg");
    }
}else{
    echo "No id";
}
?>