<?php
include("connection.php");

session_start();

function getAssessmentsByCategory($connectDB, $category) {
    $selectQry = "SELECT * FROM tbl_assessments WHERE assessment_category = $category";
    $result = $connectDB->query($selectQry);
    $assessments = array();

    if ($result->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            $assessments[] = $data;
        }
    }

    return $assessments;
}

$introAssessments = getAssessmentsByCategory($connectDB, 1);
$beginnerAssessments = getAssessmentsByCategory($connectDB, 2);
$advancedAssessments = getAssessmentsByCategory($connectDB, 3);
$extraAssessments = getAssessmentsByCategory($connectDB, 4);
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
            <h2>Manage Assessments</h2>
        </div>

        <span class="module-category">Beginner</span>
        <table class="assessmentList">
            <thead>
                <tr>
                    <th>Assessment</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($introAssessments as $assessment) { ?>
                <tr>
                    <td><?php echo $assessment['given']; ?></td>
                    <td><?php echo $assessment['date_added']; ?></td>
                    <td>
                        <a href="viewAssessment.php?AID=<?php echo $assessment['AID']; ?>"><span class='action-btn'>Edit</span></a>  
                        <a href="deleteAssessment.php?AID=<?php echo $assessment['AID']; ?>" onclick="return confirm('Are you sure you want to delete this assessment?');"><span class='action-btn'>Delete</span></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <span class="module-category">Intermediate</span>
        <table class="assessmentList">
            <thead>
                <tr>
                    <th>Assessment</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($beginnerAssessments as $assessment) { ?>
                <tr>
                    <td><?php echo $assessment['given']; ?></td>
                    <td><?php echo $assessment['date_added']; ?></td>
                    <td>
                        <a href="viewAssessment.php?AID=<?php echo $assessment['AID']; ?>"><span class='action-btn'>Edit</span></a>  
                        <a href="deleteAssessment.php?AID=<?php echo $assessment['AID']; ?>" onclick="return confirm('Are you sure you want to delete this assessment?');"><span class='action-btn'>Delete</span></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <span class="module-category">Advanced</span>
        <table class="assessmentList">
            <thead>
                <tr>
                    <th>Assessment</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($advancedAssessments as $assessment) { ?>
                <tr>
                    <td><?php echo $assessment['given']; ?></td>
                    <td><?php echo $assessment['date_added']; ?></td>
                    <td>
                        <a href="viewAssessment.php?AID=<?php echo $assessment['AID']; ?>"><span class='action-btn'>Edit</span></a> 
                        <a href="deleteAssessment.php?AID=<?php echo $assessment['AID']; ?>" onclick="return confirm('Are you sure you want to delete this assessment?');"><span class='action-btn'>Delete</span></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="addModule" title="Add assessment" id="showModulePopUp">
        <i class="fas fa-plus"></i>
    </div>

    <div class="assessmentPopUp" id="assessmentPopup">
        <span class="popUpTitle">Add Assessment</span>
        <form action="addAssessment.php" method="post" enctype="multipart/form-data">
            <select name="assessmentCategory" id="assessmentType" class="select-module-category">
                <option value="0">-- Select Category --</option>
                <option value="1">Beginner</option>
                <option value="2">Intermediate</option>
                <option value="3">Advanced</option>
            </select>
            
            <div id="completeTheCodeInputs" class="assessmentInput">
                <input type="text" name="aQuestion" class="select-module-name" placeholder="Enter the Assessment Question" required>
                <input type="text" name="aProblem" class="select-module-name" placeholder="Enter the Assessment Problem" required>
                <div id="answerInputs" class="answerInputsAssessments">
                    <button type="button" id="addAnswerBtn" class='addAnswer'>Add Another Answer</button>
                    <input type="text" name="answer1" class="assessmentAnswer" placeholder="Enter Correct Answer 1" required>
                </div>
            </div>
            
            <input type="submit" value="Save" class="saveModule" id="submitBtn" disabled>
            <div class="closeBtn" id="closeBtn">
                &times;
            </div>
        </form>
    </div>
    <script>
        let answerCount = 1;

        document.getElementById('addAnswerBtn').addEventListener('click', function() {
            if (answerCount < 4) { 
                answerCount++; 
                const newAnswerInput = document.createElement('input');
                newAnswerInput.type = 'text';
                newAnswerInput.name = `answer${answerCount}`;
                newAnswerInput.className = 'assessmentAnswer';
                newAnswerInput.placeholder = `Enter Correct Answer ${answerCount}`;
                newAnswerInput.required = true;
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