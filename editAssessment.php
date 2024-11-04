<?php 

include("connection.php");
date_default_timezone_set("Asia/Manila");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $AID = $_POST['assessmentID'];
    $category = $_POST['assessmentCategory'];
    $question = $_POST['aQuestion'];
    $problem = $_POST['aProblem'];
    $date = date("Y-m-d H:i:s");
    
    $answer1 = !empty($_POST['answer1']) ? $_POST['answer1'] : null;
    $answer2 = !empty($_POST['answer2']) ? $_POST['answer2'] : null;
    $answer3 = !empty($_POST['answer3']) ? $_POST['answer3'] : null;
    $answer4 = !empty($_POST['answer4']) ? $_POST['answer4'] : null;

    $updateQry = "UPDATE tbl_assessments SET 
        `assessment_category` = ?,
        `given` = ?,
        `problem` = ?,
        `answer1` = ?,
        `answer2` = ?,
        `answer3` = ?,
        `answer4` = ?,
        `date_added` = ?
        WHERE `AID` = ?";

    if ($stmt = $connectDB->prepare($updateQry)) {
        $stmt->bind_param("ssssssssi", $category, $question, $problem, $answer1, $answer2, $answer3, $answer4, $date, $AID);

        if ($stmt->execute()) {
            $msg = "Assessment updated successfully!";
            header("Location: adminAssessment.php?e=$msg");
            exit();
        } else {
            $msg = "Failed to update the assessment!";
            header("Location: adminAssessment.php?e=$msg");
            exit();
        }

        $stmt->close();
    } else {
        $msg = "Error in preparing the SQL query!";
        header("Location: adminAssessment.php?e=$msg");
        exit();
    }
}

$connectDB->close();

?>
