<?php 

include("connection.php");
date_default_timezone_set("Asia/Manila");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['assessmentCategory'];
    $question = $_POST['aQuestion'];
    $problem = $_POST['aProblem'];
    $date = date("Y-m-d H:i:s"); 
    
    $answer1 = !empty($_POST['answer1']) ? $_POST['answer1'] : null;
    $answer2 = !empty($_POST['answer2']) ? $_POST['answer2'] : null;
    $answer3 = !empty($_POST['answer3']) ? $_POST['answer3'] : null;
    $answer4 = !empty($_POST['answer4']) ? $_POST['answer4'] : null;

    $stmt = $connectDB->prepare("INSERT INTO tbl_assessments (assessment_category, given, problem, answer1, answer2, answer3, answer4, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $category, $question, $problem, $answer1, $answer2, $answer3, $answer4, $date); 

    if($stmt->execute()) {
        $msg = "Successfully saved!";
        header("location: adminAssessment.php?e=$msg"); 
    } else {
        $msg = "Failed to save!";
        header("location: adminAssessment.php?e=$msg"); 
    }

    $stmt->close(); // Close the prepared statement
}

$connectDB->close();

?>
