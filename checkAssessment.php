<?php 

session_start();
ob_start();

include("connection.php");
date_default_timezone_set("Asia/Manila");

$sessionID = $_SESSION['session_user_id'];

$AID = $_POST['AID'];
$answer1 = !empty($_POST['answer1']) ? $_POST['answer1'] : null;
$answer2 = !empty($_POST['answer2']) ? $_POST['answer2'] : null;
$answer3 = !empty($_POST['answer3']) ? $_POST['answer3'] : null;
$answer4 = !empty($_POST['answer4']) ? $_POST['answer4'] : null;
$date = date("Y-m-d H:i:s");

$selectQry = "SELECT * FROM tbl_assessments WHERE `AID` = '$AID'";
$result = $connectDB->query($selectQry);

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $correctAnswer1 = $row['answer1'];
    $correctAnswer2 = $row['answer2'];
    $correctAnswer3 = $row['answer3'];
    $correctAnswer4 = $row['answer4'];

    if(isset($correctAnswer1)){
        if(strcasecmp($answer1, $correctAnswer1) === 0){
            echo "Answer1 correct";
            $is1Correct = true;
        }else{
            echo "Answer1 Wrong";
            $is1Correct = false;
        }
    }else {
        echo "No answer";
        $is1Correct = true;
    }
    if(isset($correctAnswer2)){
        if(strcasecmp($answer2, $correctAnswer2) === 0){
            echo "Answer2 correct";
            $is2Correct = true;
        }else{
            echo "Answer2 Wrong";
            $is2Correct = false;
        }
    }else {
        echo "No answer";
        $is2Correct = true;
    }
    if(isset($correctAnswer3)){
        if(strcasecmp($answer3, $correctAnswer3) === 0){
            echo "Answer3 correct";
            $is3Correct = true;
        }else{
            echo "Answer3 Wrong";
            $is3Correct = false;
        }
    }else {
        echo "No answer";
        $is3Correct = true;
    }
    if(isset($correctAnswer4)){
        if(strcasecmp($answer4, $correctAnswer4) === 0){
            echo "Answer4 correct";
            $is4Correct = true;
        }else{
            echo "Answer4 Wrong";
            $is4Correct = false;
        }
    }else {
        echo "No answer";
        $is4Correct = true;
    }
    
    if(
        $is1Correct && 
        $is2Correct &&
        $is3Correct &&
        $is4Correct
    ){
        // Insert taken assessment
        $stmt = $connectDB->prepare("INSERT INTO `tbl_userassessmentprogress`(`UID`,`AID`,`date_taken`) VALUES(?,?,?)");
        $stmt->bind_param("iis", $sessionID, $AID, $date);

        if($stmt->execute()){
            // Update user ranking
            $selectQry = "SELECT * FROM tbl_userrank WHERE `UID` = $sessionID";
            $result = $connectDB->query($selectQry);

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $level = $row['level'];
                if($level % 9 == 0){
                    $newRank = $row['rank'] + 1;
                    $newLevel = $level + 1;

                    $updateQry = "UPDATE tbl_userrank SET rank = '$newRank', `level` = '$newLevel', `date_updated` = '$date' WHERE `UID` = '$sessionID'";
                    if($connectDB->query($updateQry) === true){
                        $msg = "Correct!";
                        header("location: gameAssessment.php?e=$msg");
                    }
                }else {
                    $newLevel = $level + 1;
                    $updateQry = "UPDATE tbl_userrank SET `level` = '$newLevel', `date_updated` = '$date' WHERE `UID` = '$sessionID'";
                    if($connectDB->query($updateQry) === true){
                        $msg = "Correct!";
                        header("location: gameAssessment.php?e=$msg");
                    }
                }
            }else {
                // Insert new ranking
                $stmt = $connectDB->prepare("INSERT INTO `tbl_userrank`(`UID`, `rank`, `level`, `date_updated`) VALUES (?, ?, ?, ?)");
                $rank = 1;
                $level = 1;
                $stmt->bind_param("iiis", $sessionID, $rank, $level, $date);

                if($stmt->execute()){
                    $msg = "Correct!";
                    header("location: gameAssessment.php?e=$msg");
                }
            }
        }
    }else{
        $msg = "Wrong!";
        header("location: gameAssessment.php?e=$msg");
    }
}


?>