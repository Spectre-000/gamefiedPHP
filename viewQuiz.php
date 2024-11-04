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
        <form action="editQuiz.php" method="post" enctype="multipart/form-data" class="viewAssessment">
            <span class="popUpTitle">Edit Quiz</span>
            <?php
                if(isset($_GET['QID'])){
                    $QID = $_GET['QID'];
                    $selectQuery = "SELECT * from tbl_quizzes WHERE QID = '$QID'";
                    $result = $connectDB->query($selectQuery);

                    if($result->num_rows > 0){
                        $data = mysqli_fetch_array($result);

                        if($data['category'] == "1"){
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='1'>Easy</option>
                                <option value='2'>Medium</option>
                                <option value='3'>Hard</option>
                                <option value='4'>Expert</option>
                            </select>";
                        }elseif($data['category'] == "2"){
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='2'>Medium</option>
                                <option value='1'>Easy</option>
                                <option value='3'>Hard</option>
                                <option value='4'>Expert</option>
                            </select>";
                        }elseif($data['category'] == "3"){
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='3'>Hard</option>
                                <option value='1'>Easy</option>
                                <option value='2'>Medium</option>
                                <option value='4'>Expert</option>
                            </select>";
                        }else {
                            echo "
                            <select name='assessmentCategory' id='assessmentType'  class='select-module-category'>
                                <option value='4'>Expert</option>
                                <option value='1'>Easy</option>
                                <option value='3'>Medium</option>
                                <option value='2'>Hard</option>
                            </select>";
                        }

                        echo "
                        <div id='completeTheCodeInputs' class='assessmentInput'>
                            <input type='text' value='" . $data['question'] ."' name='aQuestion' class='select-module-name' placeholder='ENTER YOUR GIVEN' required>
                            <div id='answerInputs' class='answerInputs'>
                            <input type='hidden' name='quizID' value='" . $data['QID'] . "'>;";                        

                            if($data['correct_answer'] == $data['answer1']){
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='1' required checked>
                                    <input type='text' value='" . $data['answer1'] . "' name='answer1' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }else {
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='1' required>
                                    <input type='text' value='" . $data['answer1'] . "' name='answer1' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }
                            if($data['correct_answer'] == $data['answer2']){
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='2' required checked>
                                    <input type='text' value='" . $data['answer2'] . "' name='answer2' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }else {
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='2' required>
                                    <input type='text' value='" . $data['answer2'] . "' name='answer2' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }
                            if($data['correct_answer'] == $data['answer3']){
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='3' required checked>
                                    <input type='text' value='" . $data['answer3'] . "' name='answer3' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }else {
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='3' required>
                                    <input type='text' value='" . $data['answer3'] . "' name='answer3' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }
                            if($data['correct_answer'] == $data['answer4']){
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='4' required checked>
                                    <input type='text' value='" . $data['answer4'] . "' name='answer4' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
                            }else {
                                echo "
                                <div id='answerInput1' class='multipleAnswers'>
                                    <input type='radio' name='correctanswer' value='4' required>
                                    <input type='text' value='" . $data['answer4'] . "' name='answer4' placeholder='ENTER OPTION 1' required>
                                </div>
                                ";
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
                newAnswerInput.className = 'select-module-name';
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