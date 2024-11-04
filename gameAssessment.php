<?php 
session_start();
ob_start();

$sessionID = $_SESSION['session_user_id'];

include('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Page</title>
    <link rel="stylesheet" href="css/asssessmentGame.css">
    <link rel="stylesheet" href="css/mobileUI.css">
</head>
<body>
<?php
    if (isset($_GET['e'])) {
        $msg = htmlspecialchars($_GET['e']);
        echo "
        <div class='eMsg' id='alertpopup'>
            <p class='msg'>$msg</p>
        </div>
        ";
        echo "<script>document.addEventListener('DOMContentLoaded', function() { alertpopup(); });</script>";
    }
    ?>
    <a href="assessment.php">
        <img src="img/back.svg" alt="Back" class="backbutton">
    </a>
    <?php
    
    $selectQry = "SELECT rank FROM tbl_userrank WHERE `UID` = '$sessionID'";
    $result = $connectDB->query($selectQry);

    if ($result->num_rows > 0) {
        $rankData = $result->fetch_assoc();
        $rank = $rankData['rank'];
    } else {
        $rank = 1;
    }
    
    $RandomAID = null;
    $found = false;
    $attempts = 0;
    $maxAttempts = 10; 

    while (!$found && $attempts < $maxAttempts) {
        $attempts++;

        $selectQry = "SELECT AID FROM tbl_assessments WHERE `assessment_category` = '$rank' ORDER BY rand() LIMIT 1";
        $result = $connectDB->query($selectQry);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $RandomAID = $row['AID'];

            
            $checkQry = "SELECT AID FROM tbl_userassessmentprogress WHERE `UID` = '$sessionID' AND `AID` = '$RandomAID'";
            $checkResult = $connectDB->query($checkQry);

            if ($checkResult->num_rows == 0) {
                $found = true;
            }else {
                echo " <div class='questionBox'>
                            <span class='question'>No new assessment for your rank.</span>
                        </div>
                        <div class='problemBox'>
                            <span class='problem'>Please wait for the future update.</span>
                        </div>";
                break;
            }
        } else {
            echo " <div class='questionBox'>
                        <span class='question'>No new assessment for your rank.</span>
                    </div>
                    <div class='problemBox'>
                        <span class='problem'>Please wait for the future update.</span>
                    </div>";
            break;
        }
    }
    
    if($found) {
        $selectQry = "SELECT * FROM tbl_assessments WHERE `AID` = '$RandomAID'";
        $result = $connectDB->query($selectQry);

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "
            <div class='questionBox'>
                <span class='question'>" . $row['given'] . "</span>
            </div>
            <div class='problemBox'>
                <span class='problem'>" . $row['problem'] . "</span>
            </div>
            <form action='checkAssessment.php' method='post'>
            <input type='hidden' name='AID' class='AID' value='" . $row['AID'] . "'>
            <div class='AnswerBox'>
            ";
            if($row['answer1']) {
                echo "<input type='text' class='answer-input' placeholder='Answer 1' name='answer1' required/>";
            }
            if($row['answer2']) {
                echo "<input type='text' class='answer-input' placeholder='Answer 2' name='answer2' required/>";
            }
            if($row['answer3']) {
                echo "<input type='text' class='answer-input' placeholder='Answer 3' name='answer3' required/>";
            }
            if($row['answer4']) {
                echo "<input type='text' class='answer-input' placeholder='Answer 4' name='answer4' required/>";
            }
            echo "
            </div>
            <button class='confirmBtn' type='submit'>Confirm Answers</button>
            </form>";
        }
    }

    ?>
    <script>
        function alertpopup() {
            setTimeout(function(){
                const popup = document.getElementById('alertpopup');
                popup.classList.add('showEMsg');
                setTimeout(function(){
                    popup.classList.remove('showEMsg');
                },1500 );
            },50);     
        }
    </script>
</body>
</html>
