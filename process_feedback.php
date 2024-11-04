<?php
session_start();
ob_start();

$sessionUsername = $_SESSION['session_user'];
$sessionID = $_SESSION['session_user_id'];

include("connection.php");
date_default_timezone_set("Asia/Manila");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $feedbackType = $_POST['feedbackType'];
    $rating = $_POST['rating'];
    $feedbackText = $_POST['feedbackText'];
    $date = date('Y-m-d H:i:s');

    $stmt = $connectDB->prepare("INSERT INTO tbl_feedback (`senderID`,`feedback_type`,`rating`,`feedback_text`,`date_submitted`) VALUES(?,?,?,?,?)");
    $stmt->bind_param('isiss', $sessionID, $feedbackType, $rating, $feedbackText, $date);

    if($stmt->execute()){
        $msg = "Feedback successfully submited";
        header("location: homepage.php?e=$msg");
    }else {
        $msg = "Failed to submit feedback";
        header("location: homepage.php?e=$msg");
    }
}

?>