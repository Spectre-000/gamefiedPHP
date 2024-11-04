<?php
include("connection.php"); 

if(isset($_GET['QID'])){
    $QID = $_GET['QID'];

    $deleteQuery = "DELETE from `tbl_quizzes` WHERE QID=$QID";

    if($connectDB->query($deleteQuery)){
        $msg = "Successfully Deleted";
        header("location:adminQuiz.php?e=$msg");
    }else{
        $msg = "Failed";
        header("location:adminQuiz.php?e=$msg");
    }
}else{
    echo "No id";
}
?>