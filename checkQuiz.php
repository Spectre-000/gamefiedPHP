<?php
session_start();
ob_start();

include("connection.php");

$sessionID = $_SESSION['session_user_id'];
date_default_timezone_set("Asia/Manila");

$QID = $_POST['QID'];
$userAnswer = $_POST['userAnswer'];
$totalScore = isset($_SESSION['current_score']) ? (int)$_SESSION['current_score'] : 0;
$date = date("Y-m-d H:i:s");

$redirect = '';
$message = '';

$selectQry = "SELECT * FROM tbl_quizzes WHERE `QID` = ?";
$stmt = $connectDB->prepare($selectQry);
$stmt->bind_param('i', $QID);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if($row['correct_answer'] == $userAnswer){
        $score = 0;
        switch($row['category']) {
            case 1: $score = 25; break;
            case 2: $score = 50; break;
            case 3: $score = 75; break;
            case 4: $score = 100; break;
        }
        
        $totalScore += $score;
        
        $_SESSION['current_score'] = $totalScore;
        
        $message = "Correct! Added $score points";
        $redirect = "gameQuiz.php";
        
    } else {
        $selectQry = "SELECT * FROM tbl_leaderboards WHERE `UID` = ?";
        $stmt = $connectDB->prepare($selectQry);
        $stmt->bind_param('i', $sessionID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            $data = $result->fetch_assoc();
            $currentHighScore = $data['score'];
            
            if($totalScore > $currentHighScore){
                $updateQry = "UPDATE tbl_leaderboards SET `score` = ?, `date_updated` = ? WHERE `UID` = ?";
                $stmt = $connectDB->prepare($updateQry);
                $stmt->bind_param('iss', $totalScore, $date, $sessionID);
                $stmt->execute();
                $message = "Wrong. New high score: $totalScore!";
            } else {
                $message = "Wrong. Current high score remains: $currentHighScore";
            }
        } else {
            $insertQry = "INSERT INTO tbl_leaderboards (`UID`, `score`, `date_updated`) VALUES (?, ?, ?)";
            $stmt = $connectDB->prepare($insertQry);
            $stmt->bind_param('iis', $sessionID, $totalScore, $date);
            $stmt->execute();
            $message = "Wrong. First score recorded: $totalScore";
        }
        
        unset($_SESSION['current_score']);
        
        echo "<script>
            sessionStorage.clear();
            window.location.href = 'quiz.php?e=" . urlencode($message) . "';
        </script>";
        exit();
    }
}

ob_clean();

$redirectUrl = $redirect . "?e=" . urlencode($message);
header("Location: " . $redirectUrl);
exit();
?>