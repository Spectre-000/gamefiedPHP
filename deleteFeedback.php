<?php
include("connection.php"); 

if(isset($_GET['FID'])){
    $FID = $_GET['FID'];

    $deleteQuery = "DELETE from `tbl_feedback` WHERE feedbackID=$FID";

    if($connectDB->query($deleteQuery)){
        $msg = "Successfully Deleted";
        header("location:adminFeedback.php?e=$msg");
    }else{
        $msg = "Failed";
        header("location:adminFeedback.php?e=$msg");
    }
}else{
    echo "No id";
}
?>