<?php
include("connection.php"); 

if(isset($_GET['AID'])){
    $AID = $_GET['AID'];

    $deleteQuery = "DELETE from `tbl_assessments` WHERE AID=$AID";

    if($connectDB->query($deleteQuery)){
        $msg = "Successfully Deleted";
        header("location:adminAssessment.php?e=$msg");
    }else{
        $msg = "Failed";
        header("location:adminAssessment.php?e=$msg");
    }
}else{
    echo "No id";
}
?>