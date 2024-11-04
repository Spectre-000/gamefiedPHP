<?php
include("connection.php");

ob_start(); session_start(); 
$sessionUsername = $_SESSION['session_user'];
$sessionID = $_SESSION['session_user_id'];
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
    <?php
        if(isset($_GET['FID'])){
            $FID = $_GET['FID'];
            $selectQuery = "SELECT * 
            from tbl_feedback
            INNER JOIN tbl_users ON tbl_users.UID = tbl_feedback.senderID
            WHERE feedbackID = '$FID'";
            $result = $connectDB->query($selectQuery);

            if($result->num_rows > 0){
                $data = mysqli_fetch_array($result);

                if($data['feedback_type'] = "General"){
                    $feedbackType = "General Feedback";
                }elseif($data['feedback_type'] = "bug"){
                    $feedbackType = "Bug Report";
                }elseif($data['feedback_type'] = "content"){
                    $feedbackType = "Content Improvement";
                }else{
                    $feedbackType = "Feature Request";
                }

                if($data['rating'] == 1){
                    $rating = "Poor";
                }elseif($data['rating'] == 2){
                    $rating = "Fair";
                }elseif($data['rating'] == 3){
                    $rating = "Good";
                }elseif($data['rating'] == 4){
                    $rating = "Very Good";
                }elseif($data['rating'] == 5){
                    $rating = "Excellent";
                }
                        
            }
        }
    ?>
    <div class="modulePage">
            <span class="popUpTitle"><?php echo $data['username']?>'s  Feedback</span>

            <label for="feedbackType">Feedback Type</label>
            <input type="text" class="feedbackType" value="<?php echo $feedbackType;?>" readonly>

            <label for="Rating">Rating</label>
            <input type="text" class="feedbackType" value="<?php echo $rating;?>" readonly>
            
            <label for="Rating">Feedback</label>
            <div class="feedbackText"><?php echo $data['feedback_text']?></div>
            
            <a href="adminFeedback.php"><button type="button" class="cancelBtn">Cancel</button></a>  
            <a href="deleteFeedback.php?FID=<?php echo $data['feedbackID']?>"><button type="button" class="saveModule">Delete</button></a>        
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