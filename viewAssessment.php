<?php
include("connection.php");

ob_start(); session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamefied Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <div class="sidebar">
        <div class="gameTitle">
            <p>GAMEFIED</p>
        </div>
        <div class="sideNavs">
            <ul>
                <a href="adminhome.php"><li><i class="fas fa-home"></i> Dashboard</li></a>
                <a href="adminFeedback.php"><li><i class="fas fa-comment-dots"></i> Feedbacks</li></a>
                <a href="adminAssessment.php"><li><i class="fas fa-tasks"></i> Assessments</li></a>
                <a href="adminQuiz.php"><li><i class="fas fa-question-circle"></i> Quizzes</li></a>
                <a href="adminUser.php"><li><i class="fas fa-users"></i> Users</li></a>
                <a href="adminLeaderboard.php"><li><i class="fas fa-trophy"></i> Leaderboards</li></a>
                <a href="homepage.php"><li><i class="fas fa-check-circle"></i> User Page</li></a>
                <a href="homepage.php"><li><i class="fas fa-sign-out"></i> Sign Out</li></a>
            </ul>
        </div>
    </div>
    <div class="modulePage">
        <form action="editAssessment.php" method="post" enctype="multipart/form-data" class="viewAssessment">
            <span class="popUpTitle">Edit Assessments</span>
            <?php
                if(isset($_GET['AID'])){
                    $AID = $_GET['AID'];
                    $selectQuery = "SELECT * from tbl_assessments WHERE AID = '$AID'";
                    $result = $connectDB->query($selectQuery);

                    if($result->num_rows > 0){
                        $data = mysqli_fetch_array($result);

                        if($data['assessment_category'] == "1"){
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='1'>INTRODUCTION</option>
                                <option value='2'>BEGINNER</option>
                                <option value='3'>ADVANCED</option>
                                <option value='4'>EXTRA</option>
                            </select>";
                        }elseif($data['assessment_category'] == "2"){
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='2'>BEGINNER</option>
                                <option value='1'>INTRODUCTION</option>
                                <option value='3'>ADVANCED</option>
                                <option value='4'>EXTRA</option>
                            </select>";
                        }elseif($data['assessment_category'] == "3"){
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='3'>ADVANCED</option>
                                <option value='1'>INTRODUCTION</option>
                                <option value='2'>BEGINNER</option>
                                <option value='4'>EXTRA</option>
                            </select>";
                        }else {
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='4'>EXTRA</option>
                                <option value='1'>INTRODUCTION</option>
                                <option value='3'>ADVANCED</option>
                                <option value='2'>BEGINNER</option>
                            </select>";
                        }

                        echo "
                        <div id='completeTheCodeInputs' class='answerInputs'>
                            <input type='text' value='" . $data['given'] ."' name='aQuestion' class='select-module-name' placeholder='ENTER YOUR GIVEN' required>
                            <input type='text' value='" . $data['problem'] ."' name='aProblem' class='select-module-name' placeholder='ENTER THE PROBLEM' required>
                            <div id='answerInputs' class='answerInputsAssessments'>
                            <input type='hidden' name='assessmentID' value='" . $data['AID'] . "'>;
                            <button type='button' id='addAnswerBtn' class='addAnswer'>Add Another Answer</button>";

                            if ($data['answer4']) {
                                echo "<input type='hidden' value='4' id='answerCount'>";
                            } elseif ($data['answer3']) {
                                echo "<input type='hidden' value='3' id='answerCount'>";
                            } elseif ($data['answer2']) {
                                echo "<input type='hidden' value='2' id='answerCount'>";
                            } else {
                                echo "<input type='hidden' value='1' id='answerCount'>";
                            }
                            

                            if($data['answer1']){
                                echo "<input type='text' class='assessmentAnswer' value=" . $data['answer1'] . " name='answer1' placeholder='ENTER CORRECT ANSWER' >";
                            }if($data['answer2']){
                                echo "<input type='text' class='assessmentAnswer' value=" . $data['answer2'] . " name='answer2' placeholder='ENTER CORRECT ANSWER' >";
                            }if($data['answer3']){
                                echo "<input type='text' class='assessmentAnswer' value=" . $data['answer3'] . " name='answer3' placeholder='ENTER CORRECT ANSWER' >";
                            }if($data['answer4']){
                                echo "<input type='text' class='assessmentAnswer' value=" . $data['answer4'] . " name='answer4' placeholder='ENTER CORRECT ANSWER' >";
                            }

                            
                        echo "</div>
                        </div>";
                    }
                }
            ?>          
            
            <input type="submit" value="Save" class="saveModule" id="submitBtn">
        </form>
    </div>
    <script>
        let answerCountElement = document.getElementById('answerCount');
        let answerCount = parseInt(answerCountElement.value);

        document.getElementById('addAnswerBtn').addEventListener('click', function() {

            if (answerCount < 4) { 
                answerCount++; 
                const newAnswerInput = document.createElement('input');
                newAnswerInput.type = 'text';
                newAnswerInput.name = `answer${answerCount}`;
                newAnswerInput.className = 'assessmentAnswer';
                newAnswerInput.placeholder = `ENTER CORRECT ANSWER ${answerCount}`;
                document.getElementById('answerInputs').appendChild(newAnswerInput); 
            } else {
                alert('You can only add up to 4 answers.'); 
            }
        });

        document.addEventListener('DOMContentLoaded' ,function (){
            const showModuleBtn = document.getElementById('showModulePopUp');
            const modulePopUp = document.getElementById('assessmentPopup');
            const closeBtn = document.getElementById('closeBtn');

            showModuleBtn.addEventListener('click', function(){
               modulePopUp.classList.add('showPopup');
               console.log("ButtonClicked");

               closeBtn.addEventListener('click', function(){
                modulePopUp.classList.remove('showPopup');
               });
            });
        });
        document.getElementById('assessmentType').addEventListener('change', function () {
            var assessmentType = this.value;
            const submitBtn = document.getElementById('submitBtn');
            const completeCodeInputs = document.getElementById('completeTheCodeInputs');

            if (assessmentType == "0") {
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false; 
            }
        });

        document.getElementById('assessmentType').dispatchEvent(new Event('change'));
    </script>
</body>
</html>