<?php
include("connection.php");

ob_start(); 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamefied Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/adminquiz.css">
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
    <div class="mainPage">
        <div class="topbar">
            <h2>Manage Quizzes</h2>
        </div>
        <div class="module-list">
            <span class="module-category">Introduction</span>
            <table class="assessmentList">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $selectQry = "SELECT * from tbl_quizzes WHERE category = 1";
                        $result = $connectDB->query($selectQry);

                        if($result->num_rows > 0){
                            while($data = mysqli_fetch_array($result)){
                                if($data['category'] == '1'){
                                    $difficulty = "Easy";
                                }elseif($data['category'] == '2'){
                                    $difficulty = "Medium";
                                }elseif($data['category'] == '3'){
                                    $difficulty = "Hard";
                                }elseif($data['category'] == '4'){
                                    $difficulty = "Expert";
                                }
                                echo "<tr>
                                        <td>" . $data['question'] . "</td>
                                        <td>" . $data['date_added'] . "</td>
                                        <td>
                                            <a href='viewQuiz.php?QID=" . $data['QID'] . "' class='action-btn'>Edit</a>
                                            <a href='deleteQuiz.php?QID=" . $data['QID'] . "' class='action-btn' onclick=\"return confirm('Are you sure you want to delete this Quiz?');\">Delete</a>
                                        </td>
                                    </tr>";
                            }
                        }else{
                            echo "<tr><td colspan='3'>No results found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>

            <span class="module-category">Beginner</span>
            <table class="assessmentList">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $selectQry = "SELECT * from tbl_quizzes WHERE category = 2";
                        $result = $connectDB->query($selectQry);

                        if($result->num_rows > 0){
                            while($data = mysqli_fetch_array($result)){
                                echo "<tr>
                                        <td>" . $data['question'] . "</td>
                                        <td>" . $data['date_added'] . "</td>
                                        <td>
                                            <a href='viewQuiz.php?QID=" . $data['QID'] . "' class='action-btn'>Edit</a>
                                            <a href='deleteQuiz.php?QID=" . $data['QID'] . "' class='action-btn' onclick=\"return confirm('Are you sure you want to delete this Quiz?');\">Delete</a>
                                        </td>
                                    </tr>";
                            }
                        }else{
                            echo "<tr><td colspan='3'>No results found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>

            <span class="module-category">Advanced</span>
            <table class="assessmentList">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $selectQry = "SELECT * from tbl_quizzes WHERE category = 3";
                        $result = $connectDB->query($selectQry);

                        if($result->num_rows > 0){
                            while($data = mysqli_fetch_array($result)){
                                echo "<tr>
                                        <td>" . $data['question'] . "</td>
                                        <td>" . $data['date_added'] . "</td>
                                        <td>
                                            <a href='viewQuiz.php?QID=" . $data['QID'] . "' class='action-btn'>Edit</a>
                                            <a href='deleteQuiz.php?QID=" . $data['QID'] . "' class='action-btn' onclick=\"return confirm('Are you sure you want to delete this Quiz?');\">Delete</a>
                                        </td>
                                    </tr>";
                            }
                        }else{
                            echo "<tr><td colspan='3'>No results found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>

            <span class="module-category">Extras</span>
            <table class="assessmentList">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Date Added</th>       
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $selectQry = "SELECT * from tbl_quizzes WHERE category = 4";
                        $result = $connectDB->query($selectQry);

                        if($result->num_rows > 0){
                            while($data = mysqli_fetch_array($result)){
                                echo "<tr>
                                        <td>" . $data['question'] . "</td>
                                        <td>" . $data['date_added'] . "</td>
                                        <td>
                                            <a href='viewQuiz.php?QID=" . $data['QID'] . "' class='action-btn'>Edit</a>
                                            <a href='deleteQuiz.php?QID=" . $data['QID'] . "' class='action-btn' onclick=\"return confirm('Are you sure you want to delete this Quiz?');\">Delete</a>
                                        </td>
                                    </tr>";
                            }
                        }else{
                            echo "<tr><td colspan='3'>No results found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="addModule" title="Add module" id="showModulePopUp">
        +
    </div>
    <div>
        <form action="addQuiz.php" method="post" enctype="multipart/form-data" class="assessmentPopUp" id="assessmentPopup">
            <span class="popUpTitle">ADD QUIZ</span>
            <select name="assessmentCategory" id="assessmentType" class="select-module-category">
                <option value="0">--SELECT CATEGORY--</option>
                <option value="1">Easy</option>
                <option value="2">Medium</option>
                <option value="3">Hard</option>
                <option value="4">Expert</option>
            </select>
            
            <div id="completeTheCodeInputs" class="assessmentInput">
                <input type="text" name="aQuestion" class="select-module-name" placeholder="ENTER YOUR QUESTION" required>
                <div id="answerInput1" class="answerInputs">
                    <input type="radio" name="correctanswer" value="1" required>
                    <input type="text" name="answer1" placeholder="ENTER OPTION 1" required>
                </div>
                <div id="answerInput2" class="answerInputs">
                    <input type="radio" name="correctanswer" value="2" required>
                    <input type="text" name="answer2" placeholder="ENTER OPTION 2" required>
                </div>
                <div id="answerInput3" class="answerInputs">
                    <input type="radio" name="correctanswer" value="3" required>
                    <input type="text" name="answer3" placeholder="ENTER OPTION 3" required>
                </div>
                <div id="answerInput4" class="answerInputs">
                    <input type="radio" name="correctanswer" value="4" required>
                    <input type="text" name="answer4" placeholder="ENTER OPTION 4" required>
                </div>
            </div>
            
            <input type="submit" value="Save" class="saveModule" id="submitBtn" disabled>
            <div class="closeBtn" id="closeBtn">
                &times;
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded' ,function (){
            const showModuleBtn = document.getElementById('showModulePopUp');
            const modulePopUp = document.getElementById('assessmentPopup');
            const closeBtn = document.getElementById('closeBtn');

            showModuleBtn.addEventListener('click', function(){
               modulePopUp.classList.add('showPopup');
            });

            closeBtn.addEventListener('click', function(){
                modulePopUp.classList.remove('showPopup');
            });
        });

        document.getElementById('assessmentType').addEventListener('change', function () {
            var assessmentType = this.value;
            const submitBtn = document.getElementById('submitBtn');

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