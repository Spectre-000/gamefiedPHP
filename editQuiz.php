<?php 

include("connection.php");
date_default_timezone_set("Asia/Manila");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $QID = $_POST['quizID'];
    $category = $_POST['assessmentCategory'];
    $question = $_POST['aQuestion'];
    $answer1 = $_POST['answer1'];
    $answer2 = $_POST['answer2'];
    $answer3 = $_POST['answer3'];
    $answer4 = $_POST['answer4'];
    $correctValue = $_POST['correctanswer'];
    $date = date("Y-m-d H:i:s");

    if($correctValue == 1){
        $correctAnswer = $answer1;
    }elseif($correctValue == 2){
        $correctAnswer = $answer2;
    }elseif($correctValue == 3){
        $correctAnswer = $answer3;
    }elseif($correctValue == 4){
        $correctAnswer = $answer4;
    }else {
        echo "No correct answer";
    }

    $insertQry = $connectDB->prepare("UPDATE tbl_quizzes SET 
    `category` = ?,
    `question` = ?,
    `answer1` = ?,
    `answer2` = ?,
    `answer3` = ?,
    `answer4` = ?,
    `correct_answer` = ?,
    `date_added` = ?
    WHERE `QID` = ?");
    $insertQry->bind_param('issssssss', $category, $question, $answer1, $answer2, $answer3, $answer4, $correctAnswer, $date, $QID);

    if($insertQry->execute()){
        $msg = "Quiz updated";
        header("location: adminQuiz.php?e=$msg");
    }else{
        $msg = "Failed";
        header("location: adminQuiz.php?e=$msg");
    }
}

?>